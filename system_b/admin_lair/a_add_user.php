<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('groups.CGroups');
CMain::getLangFile();
global $USER;
if($USER->hr('add_user')):

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
if(isset($_REQUEST['a_save_user_btn']))
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
		elseif(CUsersMain::checkNewLogin($login))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_LOGIN_EXIST'));
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
	else
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_PASS_EMPTY'));
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
		$arFields['active_confirmation'] = 1;
		$arFields['rang'] = $rang;

		if(CUsersMain::add($arFields))
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
	
$rang_def = 1;
if(isset($_REQUEST['rang']))
	$rang_def = $_REQUEST['rang'];	
//===========================================================Визуальная часть страницы=====================================================
?>
<?CAdminPage::setPageTitleIcon('user_add.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?
	$drop_list_value[0] = "N";
	$drop_list_value[1] = "Y";
	$drop_list_title[0] = CMain::getM('LIST_NO');
	$drop_list_title[1] = CMain::getM('LIST_YES');;	
?>
<?CAdminPage::startPage()?>
<?CAdminPage::getTabsMenu()?>
<?CAdminPage::startTabCont('main',true)?>
		<table width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<tr>
				<td align="left"><p><?=CMain::getM('T_LOGIN')?></p></td>
				<td align="left"><input type="text" name="login" value="<?=$_REQUEST['login']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_PASS')?></p></td>
				<td align="left"><input type="text" name="password" value="<?=$_REQUEST['password']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_EMAIL')?></p></td>
				<td align="left"><input type="text" name="email" value="<?=$_REQUEST['email']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_RANG')?></p></td>
				<td align="left"><input type="text" name="rang" value="<?=$rang_def?>" /></td>
			</tr>				
			<tr>
				<td align="left"><p><?=CMain::getM('T_ACTIVEN')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'active',$_REQUEST['active'])?></td>
			</tr>				
		</table>		
<?CAdminPage::endTabCont()?>	
<?CAdminPage::startTabCont('group')?>
		<table width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<?foreach($arGroups as $key=>$arGroup):?>
				<tr>
					<?if($arGroup['id']=='2'||$arGroup['id']=='3'):?>
						<td width="50%" align="left"><label for="group_check_<?=$arGroup['id']?>"><p><?=$arGroup['name_'.$site_lang]?> [<?=$arGroup['code']?>]</p><p class="a_add_user_group_descr"><?=$arGroup['description_'.$site_lang]?></p></label></td>
						<td width="50%" align="left"><input  checked="checked" type="checkbox" disabled /></td>
					<?else:?>
						<td width="50%" align="left"><label for="group_check_<?=$arGroup['id']?>"><p><?=$arGroup['name_'.$site_lang]?> [<?=$arGroup['code']?>]</p><p class="a_add_user_group_descr"><?=$arGroup['description_'.$site_lang]?></p></label></td>
						<td width="50%" align="left"><input <?if(in_array($arGroup['id'],$arRequestGroups)) echo  "checked";?> id="group_check_<?=$arGroup['id']?>" type="checkbox" name="groups[]" value="<?=$arGroup['id']?>" /></td>
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