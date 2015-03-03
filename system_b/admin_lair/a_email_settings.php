<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('email.CEmailSettings');
CMain::getLangFile();
global $USER;
if($USER->hr('email_settings')):

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
	CEmailSettings::update($_REQUEST['SETTINGS']);
}

if(CCache::cachePage()):
	$email_settings = CEmailSettings::getSettings();	
	
?>
<?CAdminPage::setPageTitleIcon('email_settings.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::startPage()?>
		<table  width="100%"  cellpadding="0" cellspacing="0" border="0">	
				<tr>
					<td align="left"><p><?=CMain::getM('email_name')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[email_name]" value="<?=$email_settings['email_name']?>" /></td>
				</tr>		
				<tr>
					<td align="left"><p><?=CMain::getM('confirm_email')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[confirm_email]" value="<?=$email_settings['confirm_email']?>" /></td>
				</tr>
				<tr>
					<td align="left"><p><?=CMain::getM('fp_email')?></p></td>
					<td align="left"><input type="text" name="SETTINGS[fp_email]" value="<?=$email_settings['fp_email']?>" /></td>
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