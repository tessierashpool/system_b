<?
//This is the main file, it will be "require" on all pages
require_once('init.php');
require_once('bd.php');
require_once('/classes/cache/CCache.php');
require_once('/classes/main/CMain.php');
CMain::includeClass('db.CDateBase');
require_once('/functions/main/main.php');
require_once('/classes/users/CUserAuth.php');
require_once('s_settings.php');

?>