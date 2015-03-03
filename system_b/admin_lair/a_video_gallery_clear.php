<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('videos.CVGallery');
CMain::getLangFile();
global $USER;
if($USER->hr('clear_video_gallery')):

//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'s',
	'value'=>CMain::getM('BTN_CLEAR'),
	'name'=>'a_clear_gallery',
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
//====================================================================Обработка запроса=============================================================
if(isset($_REQUEST['a_clear_gallery'])&&isset($_REQUEST['id']))
{
	if(CVGallery::clear($_REQUEST['id']))
		CAdminPage::successMsg(CMain::getM('SUCCESS_VGALLERY_CLEAR'));
}
//================================================================Визуальная часть страницы=========================================================
?>
<?CAdminPage::setPageTitleIcon('clear_video_gallery.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>

<?CAdminPage::startPage()?>
	<div class="a_gallery_clear_cont">
		<p>Видео роликов в галерее: <?=CVGallery::size(intval($_REQUEST['id']))?></p>
	</div>
			
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>