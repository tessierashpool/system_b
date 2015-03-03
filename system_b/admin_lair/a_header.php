<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/prolog.php');?>
<?
global $USER;
if(!$USER->hr('admin_page_access'))
	redirect('/system_b/admin_lair/a_sign_in.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="./styles/styles.css" rel="stylesheet" type="text/css" />
<link href="./images/icon.png" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript" src="/system_b/js/jquery/jquery.min.js"></script>

 <link rel="stylesheet" href="./js/jquery-ui/jquery-ui.css">
<script src="./js/jquery-ui/jquery-ui.js"></script>
<script src="./js/jquery-ui/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="/system_b/admin_lair/js/main_script.js"></script>

<?CMain::getAdFiles();?>
<title>ADMIN LAIR</title>
</head>
<body>
<div class="a_loader"></div>
<div class="a_too_longMenuTitle_wraper"></div>
<?CMain::includeClass('admin.CAdminPage');?>
<?//===========================Connect the admin panel=============================================================?>
<?CMain::GC('admin_panel', array(), 'admin_side');?>
<?
CMain::includeClass('bootstrap.CBootstrap');
CBootstrap::getIcon();

?>
<?//==Here we require PHP file that will automatic create global menu from array in menu_generator.php=========?>
<?require('/menu/global_menu.php')?>

<div class="admin_global_menu_underline"></div>
<table width="100%" class="admin_body_cont" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="admin_sub_menu_cont">
			<div class="admin_sub_menu">
<?//============================The place for sub-menu======================================?>			
				<?require('/menu/sub_menu.php')?>			
			</div>
		</td>		
		<td class="admin_sub_separ">
		</td>	
		<td class="admin_sub_right_column">
			<form enctype="multipart/form-data" id="admin_form" method="post" action="">
				<div class="admin_sub_main_place">
				<?
					$path_to_lang_file = $_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/lang/'.CMain::getSiteLang().'/'.basename($_SERVER['PHP_SELF']);
					if(file_exists($path_to_lang_file))
						require_once($path_to_lang_file);
				?>