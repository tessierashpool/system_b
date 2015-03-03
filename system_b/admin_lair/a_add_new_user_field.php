<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('groups.CGroups');
CMain::getLangFile();
$site_lang = CMain::getSiteLang();
//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'s',
	'value'=>CMain::getM('BTN_SAVE'),
	'name'=>'a_save_new_field',
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
//Начало формирования вкладок
$arTabs = array(
	'main'=>CMain::getM('TABS_MAIN'),
	'group'=>CMain::getM('TABS_GROUP'),
);	
CAdminPage::setTabs($arTabs);
//Проверка данных и добавление нового пользователя
$data['s'] = CSettings::getSettings();
if(isset($_REQUEST['a_save_new_field']))
{
	$code = safeStr($_REQUEST['code']);
	if($login!='')
	{

	}		
	else
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_CODE_EMPTY'));
	}

	$name_ru = safeStr($_REQUEST['name_ru']);
	if($name_ru=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_RU_EMPTY'));
	}	
	
	$name_en = safeStr($_REQUEST['name_en']);
	if($name_en=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_EN_EMPTY'));
	}		
	
	$lenght = intval($_REQUEST['lenght']);
	$type = safeStr($_REQUEST['type']);
	if($type!='TEXT'&&$lenght=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_LENGHT_EMPTY'));
	}
	
	$default_value = safeStr($_REQUEST['default_value']);
	$default = safeStr($_REQUEST['default']);
	if($default=='NOT NULL DEFAULT'&&$default_value=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_DEFAULT_EMPTY'));
	}	

	$name_ru = safeStr($_REQUEST['name_ru']);
	if($name_ru=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_RU_EMPTY'));
	}		
	
	if($_REQUEST['email']!='')
	{
		$email = safeStr($_REQUEST['email']);
		if (!CUsersMain::isValidEmail($email)) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_EMAIL_INVALID'));
		}
		elseif(CUsersMain::checkNewEmail($email))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_EMAIL_EXIST'));
		}	
	}		
	
	if(!CAdminPage::haveErrors())
	{
		$query = '';
		$queryType = '';
		if($type!="TEXT")
			$queryType = ' '.$type.'( '.$lenght.' )';
		else
			$queryType = ' '.$type.'';
			
		$queryType = '';
		if($type!="TEXT")
			$queryType = ' '.$type.'( '.$lenght.' )'; 
			
		$query = "ALTER TABLE `".CUsersMain::getTableName()."` ADD `".$code."`".$queryType." ".$default.""
		if(CUsersMain::registNewUser($login, $password, $email,'1', $_REQUEST['active']))
		{
			$lastUserId = CUsersMain::lastId();
			
			if($lastUserId!=0)
			{
				if(isset($_REQUEST['groups']))
					foreach($_REQUEST['groups'] as $groupId)
					{	
						if(!CUsersMain::addGroup($lastUserId,$groupId))
							CAdminPage::errorMsg(CMain::getM('ERROR_GROUP_ADD').$groupId);
					}
			}		
			CAdminPage::successMsg(CMain::getM('USER_CREATED_1').$login.CMain::getM('USER_CREATED_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('USER_CREATED_1').$login.CMain::getM('USER_CREATED_2'))));
		}
	}
}

$groupResult = CGroups::getList();
$arGroups = array();
while($arGroupFetch = mysql_fetch_array($groupResult))
	$arGroups[$arGroupFetch['id']] = $arGroupFetch;
$arRequestGroups = array();	
if(isset($_REQUEST['groups']))
	$arRequestGroups = $_REQUEST['groups'];
//===========================================================Визуальная часть страницы=====================================================
?>
<?CAdminPage::setPageTitleIcon('user_add.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?
	$drop_list_value[0] = "N";
	$drop_list_value[1] = "Y";
	$drop_list_title[0] = CMain::getM('LIST_NO');
	$drop_list_title[1] = CMain::getM('LIST_YES');
	$drop_list_type[] = "INT";
	$drop_list_type[] = "VARCHAR";
	$drop_list_type[] = "TEXT";
	$drop_list_default[] = "NOT NULL";
	$drop_list_default[] = "NULL DEFAULT NULL";
	$drop_list_default[] = "NOT NULL DEFAULT";
?>
<?CAdminPage::startPage()?>
<?CAdminPage::getTabsMenu()?>
<?CAdminPage::startTabCont('main',true)?>
		<table width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<tr>
				<td align="left"><p><?=CMain::getM('T_CODE')?></p></td>
				<td align="left"><input type="text" name="code" value="<?=$_REQUEST['code']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_RU')?></p></td>
				<td align="left"><input type="text" name="name_ru" value="<?=$_REQUEST['name_ru']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_EN')?></p></td>
				<td align="left"><input type="text" name="name_en" value="<?=$_REQUEST['name_en']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_TYPE')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_type,$drop_list_type,'type',$_REQUEST['type'])?></td>
			</tr>		
			<tr>
				<td align="left"><p><?=CMain::getM('T_LENGHT')?></p></td>
				<td align="left"><input type="text" name="lenght" value="<?=$_REQUEST['lenght']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DEFAULT')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_default,$drop_list_default,'default',$_REQUEST['default'])?></td>
			</tr>	
			<tr>
				<td align="left"><p><?=CMain::getM('T_DEFAULT_VALUE')?></p></td>
				<td align="left"><input type="text" name="default_value" value="<?=$_REQUEST['default_value']?>" /></td>
			</tr>		
			<tr>
				<td align="left"><p><?=CMain::getM('T_INDEX')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'f_index',$_REQUEST['f_index'])?></td>
			</tr>				
			<tr>
				<td align="left"><p><?=CMain::getM('T_VIEW_IN_LIST')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'view_in_list',$_REQUEST['view_in_list'])?></td>
			</tr>				
		</table>		
<?CAdminPage::endTabCont()?>	
<?CAdminPage::startTabCont('group')?>
		<table width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<?foreach($arGroups as $key=>$arGroup):?>
				<tr>
					<td width="50%" align="left"><label for="group_check_<?=$arGroup['id']?>"><p><?=$arGroup['name_'.$site_lang]?> [<?=$arGroup['code']?>]</p><p class="a_add_user_group_descr"><?=$arGroup['description_'.$site_lang]?></p></label></td>
					<td width="50%" align="left"><input <?if(in_array($arGroup['id'],$arRequestGroups)) echo  "checked";?> id="group_check_<?=$arGroup['id']?>" type="checkbox" name="groups[]" value="<?=$arGroup['id']?>" /></td>
				</tr>
			<?endforeach;?>	
		</table>	
<?CAdminPage::endTabCont()?>				
<?CAdminPage::endPage()?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>