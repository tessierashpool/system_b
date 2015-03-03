<?
class CEmailSettings
{
	
	public static function getSettings()
	{
		$sql = "SELECT * FROM `t_email_settings`";
		$res = mysql_query($sql) or die("Invalid query: " . mysql_error());	
		$result = array();
		while($arRes = mysql_fetch_array($res))
		{
			$result[$arRes['name']] = $arRes['value'];
		}
		return $result;
	}
	
	public static function add($name,$value)
	{
		$name = safeStr($name);
		$value = safeStr($value);
		//$sql = "INSERT INTO `t_site_settings` (`id`, `name`, `value`) VALUES (NULL, '".$name."', '".$value."')";
		$sql = "INSERT IGNORE INTO `t_email_settings` SET `name` = '".$name."', `value` = '".$value."'";
		$res = mysql_query($sql) or die("Invalid query: " . mysql_error());	
		return $res;
	}

	public static function update($settings = array())
	{
		foreach($settings as $name => $value)
		{
			$name = safeStr($name);
			$value = safeStr($value);		
			$sql = "UPDATE `t_email_settings` SET `value` = '".$value."' WHERE `name` = '".$name."' LIMIT 1";
			$res = mysql_query($sql) or die("Invalid query: " . mysql_error());	
		}
		CCache::removeAllCache();		
	}	

}
?>