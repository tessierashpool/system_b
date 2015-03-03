<?
class CBDInstall
{
	public static function createAllTables()
	{
		$sql = "CREATE TABLE `randombox`.`t_site_settings` (`id` INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(20) NOT NULL, `value` TINYTEXT NOT NULL, `description` TINYTEXT NOT NULL, UNIQUE (`name`)) ENGINE = MyISAM;";
		$sql = "CREATE TABLE `randombox`.`t_email_settings` (`id` INT(2) NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(20) NOT NULL, `value` TINYTEXT NOT NULL, UNIQUE (`name`)) ENGINE = MyISAM;";	
		$sql = "CREATE TABLE `randombox`.`t_bad_login` (`id` INT(12) NOT NULL AUTO_INCREMENT PRIMARY KEY, `ip` VARCHAR(20) NOT NULL, `last_timestamp` INT(10) NOT NULL, `count` INT(1) NOT NULL) ENGINE = MyISAM;";	
	}
}
?>