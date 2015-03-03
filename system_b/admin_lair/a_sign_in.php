<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/prolog.php');?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="/system_b/js/jquery/jquery.min.js"></script>


<?CMain::getAdFiles();?>
<?CMain::getLangFile();?>
<title>ADMIN LAIR</title>
</head>
<body>
<p style ="text-align:center;color:red;"><?=CMain::getM('ACCESS_DENIED');?></p>
<?CMain::GC('reg_form',array(),'admin_page');?>
</body>
</html>  
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/epilog.php');?>