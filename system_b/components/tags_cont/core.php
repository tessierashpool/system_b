<?
if(CCache::startCache())
{
	while($i<5000000)
	{
		$i++;
	}
	require('/template/'.$TEMPLATE_NAME.'/index.php');
}

?>