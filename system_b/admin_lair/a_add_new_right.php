<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('rights.CRights');
CMain::getLangFile();
global $USER;
if($USER->hr('new_right_add')):
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
		if(!CRights::isValidCode($code)) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_INVALID_CODE'));
		}		
		elseif(CRights::codeExist($code))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_CODE_EXIST'));
		}
	}		
	else
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_CODE_EMPTY'));
	}
	
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
		$arFields['code'] = $code;
		$arFields['name_en'] = $name_en;
		$arFields['description_en'] = $_REQUEST['description_en'];
		$arFields['name_ru'] = $name_ru;
		$arFields['description_ru'] = $_REQUEST['description_ru'];
		$arFields['type'] = $_REQUEST['type'];
		$arFields['category'] = $_REQUEST['category'];
		if(CRights::add($arFields))
		{
			CAdminPage::successMsg(CMain::getM('RIGHT_CREATED_1').$code.CMain::getM('RIGHT_CREATED_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('RIGHT_CREATED_1').$code.CMain::getM('RIGHT_CREATED_2'))));
		}
	}
}
//Визуальная часть страницы
?>
<?CAdminPage::setPageTitleIcon('right_add.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?
	$drop_list_value[0] = "s";
	$drop_list_value[1] = "c";
	$drop_list_title[0] = CMain::getM('LIST_SYSTEM');		
	$drop_list_title[1] = CMain::getM('LIST_CUSTOM');
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
				<td align="left"><input type="text" name="code" value="<?=$_REQUEST['code']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_EN')?></p></td>
				<td align="left"><input type="text" name="name_en" value="<?=$_REQUEST['name_en']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_EN')?></p></td>
				<td align="left"><input type="text" name="description_en" value="<?=$_REQUEST['description_en']?>" /></td>
			</tr>		
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_RU')?></p></td>
				<td align="left"><input type="text" name="name_ru" value="<?=$_REQUEST['name_ru']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_RU')?></p></td>
				<td align="left"><input type="text" name="description_ru" value="<?=$_REQUEST['description_ru']?>" /></td>
			</tr>	
			<tr>
				<td align="left"><p><?=CMain::getM('T_CATEGORY')?></p></td>
				<td align="left"><?CAdminPage::getDropList($dl_r_categoies_value,$dl_r_categoies_title,'category',$_REQUEST['category'])?></td>
			</tr>				
			<tr>
				<td align="left"><p><?=CMain::getM('T_TYPE')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'type',$_REQUEST['type'])?></td>
			</tr>			
		</table>		
			
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>