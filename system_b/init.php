<?
//Cache start and start page generate timer
//��������� ����������� � ��������� ������ ������� ��������� ��������
session_start();/*
if (file_exists('2.cache')) {
// ������ � ������� ����
readfile('2.cache');
exit();
} */
ob_start();
$page_gen_time_start = microtime(1);
	
//initiate constants
define("DB_ADRESS","localhost");
define("DB_USER","root");
define("DB_PASS","");
define("DB_NAME","randombox");

?>