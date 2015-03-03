<?
//Get site settings from db
//Получаем настройки сайта из базы дынных
CMain::includeClass('main.CSettings');
global $S_SETTINGS;
$S_SETTINGS = CSettings::getSettings();
global $USER;
$USER = new CUserAuth();

?>