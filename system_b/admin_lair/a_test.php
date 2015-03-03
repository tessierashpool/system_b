<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?


if(CCache::cachePage())
{
	while($i<5000000)
	{
		$i++;
	}
	$arr = "sdsdsdsdsd";
	echo "wwwwwwwwwwwwwwwwwwwwwwwwwwwssdsdsdsd";
}

CCache::stopCachePage();
	echo $arr;
?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>