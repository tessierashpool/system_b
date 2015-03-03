<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('images.CGallery');
CMain::getLangFile();
global $USER;
if($USER->hr('image_add_to_gallery')&&$USER->hgr(intval($_REQUEST['id']))):
//Создание кнопок для страницы
$arAdminButtons = array();
if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
{
	$arAdminButtons[] = array(
		'type'=>'l',
		'value'=>CMain::getM('BTN_BACK'),
		'link'=>$_REQUEST['back_url'],
		'class'=>'a_button_fancy'	
	);
}

CAdminPage::adminButtons($arAdminButtons);

$qResult = CGallery::getById($_REQUEST['id']);
if($qResult)
	$arGroup = mysql_fetch_array($qResult);
//================================================================Визуальная часть страницы=========================================================
?>
<?CAdminPage::setPageTitleIcon('gallery_list.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE').' "'.$arGroup['name'].'"',CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::startPage()?>
		<?
			CMain::GC('image_uploader',array('ACTIVATED'=>true,'GALLERY_ID'=>$_REQUEST['id']));	
		?>	
<?CAdminPage::endPage()?>
<?else:?>
	<?if(!$USER->hr('image_add_to_gallery')):?>
		<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
	<?else:?>
		<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT_TO_GALLERY'))?>
	<?endif;?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>