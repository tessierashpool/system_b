<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('rights.CRights');
CMain::getLangFile();
global $USER;
if($USER->hr('right_edit')):
//Проверка данных и добавление нового пользователя
$data['s'] = CSettings::getSettings();
if(isset($_REQUEST['a_save_right_btn']))
{
	
	$name_en = safeStr($_REQUEST['name_en']);
	if($name_en=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_EN_EMPTY'));
	}	

	$name_ru = safeStr($_REQUEST['name_ru']);
	if($name_ru=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_RU_EMPTY'));
	}		
		
	
	if(!CAdminPage::haveErrors())
	{
		$id = $_REQUEST['id'];
		$arFields['name_en'] = $name_en;
		$arFields['description_en'] = $_REQUEST['description_en'];
		$arFields['name_ru'] = $name_ru;
		$arFields['description_ru'] = $_REQUEST['description_ru'];
		$arFields['category'] = $_REQUEST['category'];
		if(CRights::update($arFields,$id))
		{
			CAdminPage::successMsg(CMain::getM('RIGHT_UPDATE_1').$_REQUEST['code'].CMain::getM('RIGHT_UPDATE_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('RIGHT_UPDATE_1').$_REQUEST['code'].CMain::getM('RIGHT_UPDATE_2'))));
		}
	}
}

$resRight = CRights::getById($_REQUEST['id']);
$arRightVal = mysql_fetch_array($resRight);

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

//Подготовка данных

$arFieldsDefaultVal = $arRightVal;	
if(isset($_REQUEST['name_en']))
	$arFieldsDefaultVal['name_en'] = $_REQUEST['name_en'];
if(isset($_REQUEST['description_en']))
	$arFieldsDefaultVal['description_en'] = $_REQUEST['description_en'];
if(isset($_REQUEST['name_ru']))
	$arFieldsDefaultVal['name_ru'] = $_REQUEST['name_ru'];
if(isset($_REQUEST['description_ru']))
	$arFieldsDefaultVal['description_ru'] = $_REQUEST['description_ru'];	
if(isset($_REQUEST['active']))
	$arFieldsDefaultVal['active'] = $_REQUEST['active'];
if(isset($_REQUEST['category']))
	$arFieldsDefaultVal['category'] = $_REQUEST['category'];	
	
//==============================================================Визуальная часть страницы==================================================
?>
<?CAdminPage::setPageTitleIcon('rights_edit.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?
	$drop_list_value[0] = "c";
	$drop_list_value[1] = "s";
	$drop_list_title[0] = CMain::getM('LIST_CUSTOM');
	$drop_list_title[1] = CMain::getM('LIST_SYSTEM');	
	$catResult = CRights::getCategoriesList();
	$site_lang = CMain::getSiteLang();
	while($arCat = mysql_fetch_array($catResult))
	{
		$dl_r_categoies_value[] = $arCat['id'];
		$dl_r_categoies_title[] = $arCat['name_'.$site_lang];
	}
?>
<?CAdminPage::startPage()?>
		<table  width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<tr>
				<td align="left"><p><?=CMain::getM('T_CODE')?></p></td>
				<td align="left"><input  disabled="disabled" type="text" value="<?=$arFieldsDefaultVal['code']?>" /><input type="hidden" name="code" value="<?=$arFieldsDefaultVal['code']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_EN')?></p></td>
				<td align="left"><input type="text" name="name_en" value="<?=$arFieldsDefaultVal['name_en']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_EN')?></p></td>
				<td align="left"><input type="text" name="description_en" value="<?=$arFieldsDefaultVal['description_en']?>" /></td>
			</tr>		
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_RU')?></p></td>
				<td align="left"><input type="text" name="name_ru" value="<?=$arFieldsDefaultVal['name_ru']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_RU')?></p></td>
				<td align="left"><input type="text" name="description_ru" value="<?=$arFieldsDefaultVal['description_ru']?>" /></td>
			</tr>	
			<tr>
				<td align="left"><p><?=CMain::getM('T_CATEGORY')?></p></td>
				<td align="left"><?CAdminPage::getDropList($dl_r_categoies_value,$dl_r_categoies_title,'category',$arFieldsDefaultVal['category'])?></td>
			</tr>				
			<tr>
				<td align="left"><p><?=CMain::getM('T_TYPE')?></p></td>
				<td align="left"><input disabled="disabled" type="text" value="<?=$drop_list_title[array_search($arFieldsDefaultVal['type'], $drop_list_value)]?>" /></td>
			</tr>				
		</table>		
			
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>