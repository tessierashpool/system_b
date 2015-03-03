<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('images.CGallery');
CMain::getLangFile();
global $USER;
if($USER->hr('edit_gallery')):

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
	
	$name = safeStr($_REQUEST['name']);
	if($name=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_EMPTY'));
	}		
		
	
	if(!CAdminPage::haveErrors())
	{
		$arFields['id'] = $_REQUEST['id'];
		$code = $_REQUEST['code'];
		$arFields['name'] = $name;
		$arFields['active'] = $_REQUEST['active'];
		if(CGallery::update($arFields, $arFields['id']))
		{
			CAdminPage::successMsg(CMain::getM('GALLERY_UPDATE_1').$code.CMain::getM('GALLERY_UPDATE_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('GALLERY_UPDATE_1').$code.CMain::getM('GALLERY_UPDATE_2'))));
		}
	}
}
//============Получение данных галереи по Id==================
$resGallery = CGallery::getById($_REQUEST['id']);
$arGalleryVal = mysql_fetch_array($resGallery);
	
if($_REQUEST['name']!='')
	$arGalleryVal['name'] = $_REQUEST['name'];
if($_REQUEST['active']!='')
	$arGalleryVal['active'] = $_REQUEST['active'];
//================================================================Визуальная часть страницы=========================================================
?>
<?CAdminPage::setPageTitleIcon('edit_gallery.gif');?>
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
			<td align="left"><input disabled="disabled" type="text" value="<?=$arGalleryVal['code']?>" /><input  name="code" type="hidden" value="<?=$arGalleryVal['code']?>" /></td>
		</tr>
		<tr>
			<td align="left"><p><?=CMain::getM('T_NAME')?></p></td>
			<td align="left"><input type="text" name="name" value="<?=$arGalleryVal['name']?>" /></td>
		</tr>
		<tr>
			<td align="left"><p><?=CMain::getM('T_ACTIVEN')?></p></td>
			<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'active',$arGalleryVal['active'],'','','Y')?></td>
		</tr>
	
	</table>		
			
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>