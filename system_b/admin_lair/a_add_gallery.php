<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('images.CGallery');
CMain::getLangFile();
global $USER;
if($USER->hr('add_gallery')):

//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'s',
	'value'=>CMain::getM('BTN_SAVE'),
	'name'=>'a_save_right_btn',
	'class'=>'a_button_fancy'	
);
if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
{
	$arAdminButtons[] = array(
		'type'=>'l',
		'value'=>CMain::getM('BTN_CANCEL'),
		'link'=>$_REQUEST['back_url'],
		'class'=>'a_button_custom'	
	);
}
CAdminPage::adminButtons($arAdminButtons);
//Проверка данных и добавление нового пользователя
$data['s'] = CSettings::getSettings();
if(isset($_REQUEST['a_save_right_btn']))
{
	$code = safeStr($_REQUEST['code']);
	if($code!='')
	{
		if(!CGallery::isValidCode($code)) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_INVALID_CODE'));
		}		
		elseif(CGallery::codeExist($code))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_CODE_EXIST'));
		}
	}		
	else
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_CODE_EMPTY'));
	}
	
	$name = safeStr($_REQUEST['name']);
	if($name=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_EMPTY'));
	}		
		
	
	if(!CAdminPage::haveErrors())
	{
		$arFields['code'] = $code;
		$arFields['name'] = $name;
		$arFields['active'] = $_REQUEST['active'];
		$arFields['type'] = 'c';
		if(CGallery::add($arFields))
		{
			CAdminPage::successMsg(CMain::getM('GALLERY_CREATED_1').$code.CMain::getM('GALLERY_CREATED_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('GALLERY_CREATED_1').$code.CMain::getM('GALLERY_CREATED_2'))));
		}
	}
}
//================================================================Визуальная часть страницы=========================================================
?>
<?CAdminPage::setPageTitleIcon('add_gallery.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?
	$drop_list_value[0] = "N";
	$drop_list_value[1] = "Y";
	$drop_list_title[0] = CMain::getM('LIST_NO');
	$drop_list_title[1] = CMain::getM('LIST_YES');	
		
?>
<?CAdminPage::startPage()?>
	<table  width="100%"  cellpadding="0" cellspacing="0" border="0">	
		<tr>
			<td align="left"><p><?=CMain::getM('T_CODE')?></p></td>
			<td align="left"><input type="text" name="code" value="<?=$_REQUEST['code']?>" /></td>
		</tr>
		<tr>
			<td align="left"><p><?=CMain::getM('T_NAME')?></p></td>
			<td align="left"><input type="text" name="name" value="<?=$_REQUEST['name']?>" /></td>
		</tr>
		<tr>
			<td align="left"><p><?=CMain::getM('T_ACTIVEN')?></p></td>
			<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'active',$_REQUEST['active'],'','','Y')?></td>
		</tr>
	
	</table>		
			
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>