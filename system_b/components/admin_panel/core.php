<?
global $USER;
if($USER->hr('admin_page_access')):
$arData['cache_update_url'] = currentPageWithParams(array('cache_update'=>'Y'),array('cache_update'));/*
if(isset($_REQUEST['cache_update'])&&($_REQUEST['cache_update']=='Y'))
{
	CCache::removeAllCache();
	redirect(currentPageWithParams(array(),array('cache_update')));
}*/
require('/template/'.$TEMPLATE_NAME.'/index.php');
endif;
?>