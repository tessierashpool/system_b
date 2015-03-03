<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('avito.CAvito');
CMain::getLangFile();
//================================================================Визуальная часть страницы=========================================================
?>
<?CAdminPage::setPageTitleIcon('add_gallery.gif');?>
<?CAdminPage::pageTitle("Авито","")?>

<?CAdminPage::startPage()?>
<pre>
<?CAvito::getPages();?>
</pre>
<?CAdminPage::endPage()?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>