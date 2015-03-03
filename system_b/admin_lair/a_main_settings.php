<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::getLangFile();
global $USER;
if($USER->hr('main_settings')):

$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'s',
	'value'=>CMain::getM('SAVE_BTN'),
	'class'=>'a_button_fancy'
);
$arAdminButtons[] = array(
	'type'=>'c',
	'value'=>CMain::getM('CANCEL_BTN'),
	'name'=>'a_cancel_btn'
);
CAdminPage::adminButtons($arAdminButtons);

//Update value of settings and remove all cache
if(isset($_REQUEST['a_save_btn'])&&(count($_REQUEST['SETTINGS'])>0))
{
	CSettings::update($_REQUEST['SETTINGS']);
}

if(CCache::cachePage()):
	$site_settings = CSettings::getSettings();

	$drop_list_value[0] = 0;
	$drop_list_value[1] = 1;
	$drop_list_title[0] = CMain::getM('drop_list_no');
	$drop_list_title[1] = CMain::getM('drop_list_yes');	
	
?>
<?CAdminPage::setPageTitleIcon('settings.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::startPage()?>
		<table width="100%"  cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left"><p><?=CMain::getM('login_max_len')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[login_max_len]" value="<?=$site_settings['login_max_len']?>" /></td>
				</tr>		
				<tr>
					<td align="left"><p><?=CMain::getM('password_max_len')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[password_max_len]" value="<?=$site_settings['password_max_len']?>" /></td>
				</tr>
				<tr>
					<td align="left"><p><?=CMain::getM('login_min_len')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[login_min_len]" value="<?=$site_settings['login_min_len']?>" /></td>
				</tr>
				<tr>
					<td align="left"><p><?=CMain::getM('password_min_len')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[password_min_len]" value="<?=$site_settings['password_min_len']?>" /></td>
				</tr>
				<tr>
					<td align="left"><p><?=CMain::getM('use_email')?></p></td>
					<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'SETTINGS[use_email]',$site_settings['use_email'])?></td>
				</tr>
				<tr>
					<td align="left"><p><?=CMain::getM('activation_timer')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[activation_timer]" value="<?=$site_settings['activation_timer']?>" /></td>
				</tr>
				<tr>
					<td align="left"><p><?=CMain::getM('use_captcha')?></p></td>
					<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'SETTINGS[use_captcha]',$site_settings['use_captcha'])?></td>
				</tr>		
		</table>	
<?CAdminPage::endPage()?>
<?
endif;
CCache::stopCachePage();
?>	
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>