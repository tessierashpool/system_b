<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('groups.CGroups');
CMain::getLangFile();
global $USER;
if($USER->hr('user_edit')):

$site_lang = CMain::getSiteLang();
//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'s',
	'value'=>CMain::getM('BTN_SAVE'),
	'name'=>'a_save_user_btn',
	'class'=>'a_button_fancy'	
);
if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
{
	$arAdminButtons[] = array(
		'type'=>'s',
		'value'=>CMain::getM('BTN_APPLY'),
		'link'=>$_REQUEST['back_url'],
		'name'=>'a_save_apply_btn',
		'class'=>'a_button_custom'	
	);
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
if(isset($_REQUEST['a_save_user_btn'])||isset($_REQUEST['a_save_apply_btn']))
{
	$login = safeStr($_REQUEST['login']);
	if($login!='')
	{
		if (strlen($login) < intval($data['s']['login_min_len'])) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_LONG_LOGIN').$data['s']['login_min_len']);
		}	
		elseif ( strlen($login) > intval($data['s']['login_max_len'])) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_SHORT_LOGIN').$data['s']['login_max_len']);
		}
		elseif(!CUsersMain::isValidLogin($login)) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_INVALID_LOGIN'));
		}		
	}		
	else
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_LOGIN_EMPTY'));
	}

	if($_REQUEST['password']!='')
	{
		$password = safeStr($_REQUEST['password']);
		if (strlen($password) < intval($data['s']['password_min_len'])) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_PASS_LONG').$data['s']['password_min_len']);
		}	
		elseif ( strlen($password) > intval($data['s']['password_max_len'])) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_PASS_SHORT').$data['s']['password_max_len']);
		}		
	}		
	
	if($_REQUEST['email']!='')
	{
		$email = safeStr($_REQUEST['email']);
		if (!CUsersMain::isValidEmail($email)) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_EMAIL_INVALID'));
		}
	}
	
	$rang = intval($_REQUEST['rang']);
	if(1>$rang||999<$rang)
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_RANG_OUT_RANGE'));
	}	
	
	if(!CAdminPage::haveErrors())
	{
		$arFields['login'] = $login;
		$arFields['pass'] = $password;
		$arFields['email'] = $email;
		$arFields['active'] = $_REQUEST['active'];
		$arFields['rang'] = $rang;	
		if(CUsersMain::update($arFields, $_REQUEST['id']))
		{
			$userId = $_REQUEST['id'];
			
			if($userId!=0)
			{
				CUsersMain::deleteGroup($userId);
				if(isset($_REQUEST['groups']))
				{					
					foreach($_REQUEST['groups'] as $groupId)
					{	
						if(!CUsersMain::addGroup($userId,$groupId))
							CAdminPage::errorMsg(CMain::getM('ERROR_GROUP_ADD').$groupId);
					}
				}	
			}		
			CAdminPage::successMsg(CMain::getM('USER_UPDATED_1').$login.CMain::getM('USER_UPDATED_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!=''&&!isset($_REQUEST['a_save_apply_btn']))
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('USER_UPDATED_1').$login.CMain::getM('USER_UPDATED_2'))));
		}
	}
}
//=======Подготовка данных для вывода=============
//Список групп пользователя
$userGroupsRes = CUsersMain::getUserGroupsList($_REQUEST['id']);
$arUserGroups = array();
while($userGroupsFetch = mysql_fetch_array($userGroupsRes))
	$arUserGroups[$userGroupsFetch['group_id']] = $userGroupsFetch['group_id'];
	
//Список групп в системе	
$groupResult = CGroups::getList();
$arGroups = array();
while($arGroupFetch = mysql_fetch_array($groupResult))
	$arGroups[$arGroupFetch['id']] = $arGroupFetch;
$arRequestGroups = $arUserGroups;
//Даные пользователя
$resUser = CUsersMain::getById($_REQUEST['id']);
$arUser = array();
if($resUser = mysql_fetch_array($resUser))
	$arUser = $resUser;
	
if($_REQUEST['login']!='')
	$arUser['login'] = $_REQUEST['login'];
if($_REQUEST['email']!='')
	$arUser['email'] = $_REQUEST['email'];
if($_REQUEST['active']!='')
	$arUser['active'] = $_REQUEST['active'];	
if($_REQUEST['rang']!='')
	$arUser['rang'] = $_REQUEST['rang'];		
	
if(isset($_REQUEST['groups']))
	$arRequestGroups = $_REQUEST['groups'];
//===========================================================Визуальная часть страницы=====================================================
?>
<?CAdminPage::setPageTitleIcon('user_edit.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?
	$drop_list_value[0] = "N";
	$drop_list_value[1] = "Y";
	$drop_list_title[0] = CMain::getM('LIST_NO');
	$drop_list_title[1] = CMain::getM('LIST_YES');	
?>
<?CAdminPage::startPage()?>
<?CAdminPage::getTabsMenu()?>
<?CAdminPage::startTabCont('main',true)?>
		<table width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<tr>
				<td align="left"><p><?=CMain::getM('T_LOGIN')?></p></td>
				<td align="left"><input type="text" name="login" value="<?=$arUser['login']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_PASS')?></p></td>
				<td align="left"><input type="text" name="password" value="" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_EMAIL')?></p></td>
				<td align="left"><input type="text" name="email" value="<?=$arUser['email']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_RANG')?></p></td>
				<td align="left"><input type="text" name="rang" value="<?=$arUser['rang']?>" /></td>
			</tr>				
			<tr>
				<td align="left"><p><?=CMain::getM('T_ACTIVEN')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'active',$arUser['active'])?></td>
			</tr>				
		</table>		
<?CAdminPage::endTabCont()?>	
<?CAdminPage::startTabCont('group')?>
		<table width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<?foreach($arGroups as $key=>$arGroup):?>
				<tr>
					<?if($arGroup['id']=='2'||$arGroup['id']=='3'):?>
						<td width="50%" align="left"><label for="group_check_<?=$arGroup['id']?>"><p><?=$arGroup['name_'.$site_lang]?> [<?=$arGroup['code']?>]</p><p class="a_add_user_group_descr"><?=$arGroup['description_'.$site_lang]?></p></label></td>
						<td width="50%" align="left"><input checked="checked" type="checkbox" disabled /></td>					
					<?else:?>
						<td width="50%" align="left"><label for="group_check_<?=$arGroup['id']?>"><p><?=$arGroup['name_'.$site_lang]?> [<?=$arGroup['code']?>]</p><p class="a_add_user_group_descr"><?=$arGroup['description_'.$site_lang]?></p></label></td>
						<td width="50%" align="left"><input <?if(in_array($arGroup['id'],$arRequestGroups)) echo  "checked";?> id="group_check_<?=$arGroup['id']?>" type="checkbox" name="groups[<?=$arGroup['id']?>]" value="<?=$arGroup['id']?>" /></td>
					<?endif;?>
				</tr>
			<?endforeach;?>	
		</table>	
<?CAdminPage::endTabCont()?>				
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>