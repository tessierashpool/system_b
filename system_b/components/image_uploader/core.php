<?
//CMain::includeClass('images.CImages');
/*
Возможные параметры:
$arParams['ACTIVATED'](false/true) - загружаемые параметры будут сразу активироваными
$arParams['GALLERY_ID'] - id галлереи в которую буде добавлено изображение
$arParams['ANIMATED_ALLOW'](false/true) - разрешает загружать анимированые gif
$arParams['ALLOW_FORMAT']array('gif','jpg','png') - разрешенные форматы для загрузки
*/

//CImages::removeNotUsedImages();
if(isset($_REQUEST['ajax_image_upload'])) {
	ob_get_clean();

	$fileElementName = 'my-file';
	$name_l = mb_strlen($_FILES[$fileElementName]['name']);
	if($name_l>=50)
		$namef = mb_substr($_FILES[$fileElementName]['name'],0,44).mb_substr($_FILES[$fileElementName]['name'],$name_l-5,$name_l);
	else	
		$namef = $_FILES[$fileElementName]['name'];
	$name = $namef;

	//=============Получаем информацию об изображение=================
	if($arrInfo = getimagesize($_FILES[$fileElementName]['tmp_name'])	)
	{
		$_FILES[$fileElementName]['type'] = $arrInfo['mime'];
	}
	else
	{
		echo json_encode(array('error' => "Неверный тип файла, либо файл поврежден"));
		exit();
	}	
	
	$arAllowTypes = array('gif','jpg','png');
	if(isset($arParams['ALLOW_FORMAT']))
		$arAllowTypes = $arParams['ALLOW_FORMAT'];
	$file_type = '';
	if($_FILES[$fileElementName]['type']=='image/jpeg'||$_FILES[$fileElementName]['type']=='image/jpg')
		$file_type = 'jpg';
	if($_FILES[$fileElementName]['type']=='image/gif')
		$file_type = 'gif';
	if($_FILES[$fileElementName]['type']=='image/png')
		$file_type = 'png';
	
	if(!in_array($file_type,$arAllowTypes))
	{
		echo json_encode(array('error' => "Загрузка недопустимого типа файла"));
		exit();
	}	
	
	if($_FILES[$fileElementName]['type']!='image/jpeg'&&$_FILES[$fileElementName]['type']!='image/jpg'&&$_FILES[$fileElementName]['type']!='image/gif'&&$_FILES[$fileElementName]['type']!='image/png')
		$_FILES[$fileElementName]['error'] = '200';
		
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;
			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '200':
				$error = 'Данный тип не поддерживается!';
				break;				
			case '999':
			default:
				$error = 'No error code avaiable';
		}
		echo json_encode(array('error' => $error));
	}
	elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
		echo json_encode(array('error' => $error));
	}
	else 
	{
		CMain::includeClass('images.CImages');
		CMain::includeClass('images.CGallery');
		//==============Получение параметров============================
		$activated = 'N';
		if($arParams['ACTIVATED'])
			$activated = 'Y';
			
		$gallery_id = 0;	
		if($arParams['GALLERY_ID']!='')
			$gallery_id = intval($arParams['GALLERY_ID']);
		//==============Проверка анимированости=========================
		$animated="N";
		$animatedAllow=true;
		if(isset($arParams['ANIMATED_ALLOW']))
			$animatedAllow = $arParams['ANIMATED_ALLOW'];
		if($file_type=='gif')
			if(CImages::isAnimatedGif($_FILES[$fileElementName]['tmp_name']))
				$animated="Y";
		if(!$animatedAllow&&$animated=="Y")		
		{
			echo json_encode(array('error' => "Загрузка анимированных gif запрещена"));
			exit();
		}

		$size = $_FILES[$fileElementName]['size'];
		//==============Формируем путь к файлу, создаем 3 уровня папок используя md5 файла================
		$md5 = md5_file($_FILES[$fileElementName]['tmp_name']);
		$dir_1depht = mb_substr($md5,0,2);
		$dir_2depht = mb_substr($md5,2,2);
		$dir_3depht = mb_substr($md5,4,2);
		$dir_path = $_SERVER["DOCUMENT_ROOT"].'/system_b/upload/images/'.$dir_1depht.'/'.$dir_2depht.'/'.$dir_3depht;
		if (!is_dir($dir_path)) {
			mkdir($dir_path, 0777, true);
		}	
		//===========Путь к файлу===========
		$file_path=$dir_path.'/'.$md5.mb_substr($name,-4,4);
		$url='/system_b/upload/images/'.$dir_1depht.'/'.$dir_2depht.'/'.$dir_3depht.'/'.$md5.mb_substr($name,-4,4);
		
		$file['URL'] = $url;
		$file['NAME'] = safeStr($name);
		$file['TYPE'] = $file_type;
		$file['SIZE'] = $size;
		$file['HEIGHT'] = $arrInfo[1];
		$file['WIDTH'] = $arrInfo[0];
		$file['MD5'] = $md5;
		$file['ANIMATED'] = $animated;
		$file['ACTIVATED'] = $activated;
		$file['GALLERY_ID'] = $gallery_id;
		//==========================
		if(copy($_FILES[$fileElementName]['tmp_name'],$file_path))
		{
			if($id = CImages::saveInDb($file))
				echo json_encode(array('id' => $id));
			else
				echo json_encode(array('error' => "Не удалось сохранить данные файла в базе даных"));
		}
		else
		{
			echo json_encode(array('error' => "Не удалось сохранить файл"));
		}
		//==========================


	}
	exit();
}

require('/template/'.$TEMPLATE_NAME.'/index.php');
?>