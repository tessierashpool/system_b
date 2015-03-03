<?
$errors = array();
global $USER;
if($USER->isAuthorized())
	$arData['isAuthorized'] = true;
else
	$arData['isAuthorized'] = false;	
require('/template/'.$TEMPLATE_NAME.'/index.php');
?>