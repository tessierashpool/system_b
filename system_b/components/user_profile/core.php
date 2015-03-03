<?
if(isset($_REQUEST['logout']))
{
	global $USER;
	$USER->logout();
	redirect(currentPage());
}
require('/template/'.$TEMPLATE_NAME.'/index.php');
?>