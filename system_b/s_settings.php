<?
//Get site settings from db
//�������� ��������� ����� �� ���� ������
CMain::includeClass('main.CSettings');
global $S_SETTINGS;
$S_SETTINGS = CSettings::getSettings();
global $USER;
$USER = new CUserAuth();

?>