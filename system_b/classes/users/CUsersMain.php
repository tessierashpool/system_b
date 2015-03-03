<?
class CUsersMain{
	//private static $t_users_name = 't_users';
	private static $table_name = 't_users';
	private static $table_useres_in_groups = 't_users_in_groups';
	private static $last_id = 0;
	//This function registrate new user 
	//Функция регистрирует нового пользователя
	public static function registNewUser($login,$password, $email='', $active_confirmation=1, $active='Y')
	{
		$login = safeStr($login,true);
		$u_password = self::passwordHash($password);
		if($email!='')
			$email = safeStr($email,true);
		if($active_confirmation!='')
			$active_confirmation = intval($active_confirmation);			
		$sql = "INSERT INTO `".self::$table_name."` (`id`, `login`, `pass`, `email`, `active_confirmation`, `date_create`, `date_update`, `active`) VALUES (NULL, '".$login."', '".$u_password."', '".$email."', '".$active_confirmation."', ".time().", ".time().",'".$active."')";
		$result = mysql_query($sql);	
		self::$last_id =  mysql_insert_id();
		return $result;
	}

	public static function add($arFields = array())	
	{
		CMain::includeClass('db.CDateBase');
		$arFields['pass'] = self::passwordHash($arFields['pass']);
		$arFields['login'] = safeStr($arFields['login'],true);
		$arFields['email'] = safeStr($arFields['email'],true);
		$arFields['date_create'] = time();
		$arFields['date_update'] = time();
		if($arFields['active'] != 'Y')
			$arFields['active'] = 'N';
		if($arFields['active_confirmation'] != 1)
			$arFields['active_confirmation'] = 0;			
		$result = CDateBase::insert($arFields,self::$table_name);
		self::$last_id =  mysql_insert_id();
		return $result;
	}
	
	public static function update($arFields=array(), $id)	
	{
		CMain::includeClass('db.CDateBase');
		if($arFields['pass']!='')
			$arFields['pass'] = self::passwordHash($arFields['pass']);
		else
			unset($arFields['pass']);
		$arFields['login'] = safeStr($arFields['login'],true);
		$arFields['email'] = safeStr($arFields['email'],true);
		$arFields['date_update'] = time();
		if($arFields['active'] != 'Y')
			$arFields['active'] = 'N';
		$result = CDateBase::update($arFields,self::$table_name,$id);
		return $result;
	}	
	
	public static function lastId()	
	{
		return self::$last_id;
	}		
	
	public static function signInUser($login,$password, $stay_sigin=0)
	{
		$login = safeStr($login,true);
		$password = safeStr($password,true);
		$sql = "SELECT * FROM `".self::$table_name."` WHERE `login`='$login' AND `active_confirmation`='1' AND `active`='Y'";
		$result = mysql_query($sql);	
		if($myrow = mysql_fetch_array($result))
		{
			$user_password = $myrow['pass'];
			$salt = substr($user_password, -6);
			$hash = substr($user_password, 0, -6);
			$session_hash = substr($user_password, -32);
			$session_hash = $session_hash.$myrow['login'];
			$session_hash = $session_hash.$myrow['active'];
			if(hash('sha256', $password.$salt)==$hash)
			{
				$_SESSION['userid'] = $myrow['id'];
				$_SESSION['hashcode'] = md5($myrow['id'].strrev($session_hash));
				if($stay_sigin==1)
				{
					setcookie("userid", $myrow['id'], time()+9999999);
					setcookie("hashcode", md5($myrow['id'].strrev($session_hash)), time()+9999999);				
					setcookie("staysignin", '1', time()+9999999);				
				}
				self::clearBadLogin();
				CMain::dbTrashClean();
				return true;
			}
			else
			{
				self::badLogin();
				return false;
			}
		}
		else
		{
			self::badLogin();
			return false;
		}
	}	
	
	public static function checkNewLogin($login)
	{
		CMain::includeClass('db.CDateBase');
		$login = safeStr($login,true);
		$sql = "SELECT id FROM `".self::$table_name."` WHERE `login`='$login' AND `active_confirmation`='1'";
		$result = CDateBase::selectQuery($sql);
		$myrow = mysql_fetch_array($result);
		return (!empty($myrow['id']));
	}	
	
	public static function checkNewEmail($email)
	{
		CMain::includeClass('db.CDateBase');
		$email = safeStr($email,true);
		$sql = "SELECT id FROM `".self::$table_name."` WHERE `email`='$email' AND `active_confirmation`='1'";
		$result = CDateBase::selectQuery($sql);	
		$myrow = mysql_fetch_array($result);
		return (!empty($myrow['id']));
	}
	
	public static function emailAccountActivation($login, $email_title, $email_text, $login_mark = "{LOGIN}", $link_mark="{LINK}")
	{
		$sql = "SELECT id, email FROM `".self::$table_name."` WHERE `login`='$login'";
		$result = mysql_query($sql);	
		if($myrow = mysql_fetch_array($result))
		{
			$md5 = md5($myrow['id']).md5($login);
			$link = "http://".$_SERVER['HTTP_HOST']."/activation.php?login=".$login."&code=".$md5;
			$e_title = $email_title;
			$e_text = preg_replace("!".$link_mark."!", $link, $email_text);
			$e_text = preg_replace("!".$login_mark."!", $login, $e_text);
			CMain::includeClass('email.CEmailSettings');
			$email_settings = CEmailSettings::getSettings();
			$headers  = "Content-type: text/html; charset=utf-8 \r\n";
			$headers .= "From: ".$email_settings['email_name']." <".$email_settings['confirm_email'].">\r\n";
			$res = mail($myrow['email'], $e_title, $e_text, $headers);
			return $res;
		}
		else
		{
			return false;
		}
	}
	
	public static function isValidLogin($str)	
	{
		return preg_match('/^[A-z0-9_]+$/i', $str);
	}
	
	public static function isValidEmail($str)	
	{
		return preg_match('/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/', $str);
	}	
	
	private static function generateRandomString($length = 6) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	public static function getClientIp()
	{
     $ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress; 
	}
	
	public static function badLogin()
	{
		$user_ip = safeStr(self::getClientIp());

		$sql = "SELECT * FROM `t_bad_login` WHERE `ip`='$user_ip'";
		$result = mysql_query($sql);	
		if($myrow = mysql_fetch_array($result))
		{
			$id = $myrow['id'];
			if($myrow['last_timestamp']<(time()-120))
			{
				$sql = "UPDATE `t_bad_login` SET `last_timestamp` = ".time().", `count` = 1 WHERE `id` = $id LIMIT 1";
				$res = mysql_query($sql) or die("Invalid query: " . mysql_error());	
			}
			else
			{
				$count = intval($myrow['count']);
				$count++;
				$sql = "UPDATE `t_bad_login` SET `last_timestamp` = ".time().", `count` = $count WHERE `id` = $id LIMIT 1";
				$res = mysql_query($sql) or die("Invalid query: " . mysql_error());					
			}
		}	
		else
		{
			$sql = "INSERT INTO `t_bad_login` (`id`, `ip`, `last_timestamp`, `count`) VALUES (NULL, '".$user_ip."', ".time().", '1')";
			$result = mysql_query($sql);				
		}	
	}	
	
	public static function isBadLoginer()
	{
		$user_ip = safeStr(self::getClientIp());

		$sql = "SELECT * FROM `t_bad_login` WHERE `ip`='$user_ip' AND `count` >='5'";
		$result = mysql_query($sql);	
		if($myrow = mysql_fetch_array($result))
		{
			return true;
		}	
		else
		{
			return false;			
		}	
	}	

	private static function clearBadLogin()
	{
		$user_ip = safeStr(self::getClientIp());	
		$sql = "DELETE FROM `t_bad_login` WHERE `ip`='$user_ip' ";
		$result = @mysql_query($sql);	
	}

	private static function clearPassRestore($user_id)
	{	
		$user_id = intval($user_id);
		$sql = "DELETE FROM `t_pass_restore` WHERE `user_id`='$user_id' ";
		$result = @mysql_query($sql);	
	}
	
	private static function createPassRestore($user_id)
	{	
		$hash_code = md5(self::generateRandomString().time());
		$sql = "INSERT INTO `t_pass_restore` (`id`, `user_id`, `hash_code`, `date_create`) VALUES (NULL, '".$user_id."', '".$hash_code."', ".time().")";
		$result = mysql_query($sql);
		return $hash_code;
	}	
	
	public static function fpEmailSend($email, $email_title, $email_text, $login_mark = "{LOGIN}", $link_mark="{LINK}")
	{
		$email = safeStr($email);
		$sql = "SELECT id, email, login FROM `t_users` WHERE `email`='$email' AND `active_confirmation`='1'";
		$result = mysql_query($sql);	
		if($myrow = mysql_fetch_array($result))
		{
			self::clearPassRestore($myrow['id']);
			$hash_code = self::createPassRestore($myrow['id']);
			$fake_1 = md5(self::generateRandomString().time());
			$fake_2 = md5(self::generateRandomString());
			$id = $myrow['id'];
			$link = "http://".$_SERVER['HTTP_HOST']."/password_restore.php?a=".$id."&b=".$hash_code."&c=".$fake_1."&d=".$fake_2;
			$login = $myrow['login'];
			$e_title = $email_title;
			$e_text = preg_replace("!".$link_mark."!", $link, $email_text);
			$e_text = preg_replace("!".$login_mark."!", $login, $e_text);
			CMain::includeClass('email.CEmailSettings');
			$email_settings = CEmailSettings::getSettings();
			$headers  = "Content-type: text/html; charset=utf-8 \r\n";
			$headers .= "From: ".$email_settings['email_name']." <".$email_settings['fp_email'].">\r\n";
			$res = mail($myrow['email'], $e_title, $e_text, $headers);
			return $res;
		}
		else
		{
			return false;
		}
	}

	private static function passwordHash($password)
	{
		$password = safeStr($password,true);
		$salt = self::generateRandomString();
		$u_password = hash('sha256', $password.$salt).$salt;	
		return $u_password;
	}
	public static function resetPassword($id, $hash, $password)
	{
		$sql = "SELECT id, user_id FROM `t_pass_restore` WHERE `user_id`='$id' AND `hash_code`='$hash'";
		$result = mysql_query($sql);		
		if($myrow = mysql_fetch_array($result))
		{
		//	self::clearPassRestore($myrow['user_id']);
			$id = $myrow['user_id'];
			$u_password = self::passwordHash($password);
			$sql = "UPDATE `".self::$table_name."` SET `pass` = '$u_password', `date_update` = ".time()." WHERE `id` = $id LIMIT 1";
			$result = mysql_query($sql);	
			return $result;
		}
		else
		{
			return false;
		}		
	}
	
	//Get users table name
	public static function getTableName()
	{	
		return self::$table_name;
	}	
	public static function getUsersInGroupTableName()
	{	
		return self::$table_useres_in_groups;
	}		

	public static function deleteUserById($id)
	{
		$id = intval($id);		
		$sql = "DELETE FROM `".self::$table_name."` WHERE `id`='$id' ";
		self::deleteGroup($id);
		$result = CDateBase::deleteQuery($sql);
	}
	
	public static function addGroup($user_id,$group_id)
	{
		CMain::includeClass('db.CDateBase');
		$group_id = intval($group_id);		
		$user_id = intval($user_id);		
		//$sql = "INSERT INTO `".$table_rights_list."` (`id`, ".$titleLine.") VALUES (NULL, ".$valueLine.")";	
		$sql = "INSERT INTO `".self::$table_useres_in_groups."` (`id`, `user_id`, `group_id`) VALUES (NULL, ".$user_id.",  ".$group_id.")";			
		$result = CDateBase::insertQuery($sql);
		return $result;
	}	
	
	public static function deleteGroup($user_id,$group_id=0)	
	{
		CMain::includeClass('db.CDateBase');
		$group_id = intval($group_id);		
		$user_id = intval($user_id);	
		if($group_id==0)
		{
			$sql = "DELETE FROM `".self::$table_useres_in_groups."` WHERE `user_id`='$user_id'";
			$result = CDateBase::deleteQuery($sql);		
		}
		else
		{
			$sql = "DELETE FROM `".self::$table_useres_in_groups."` WHERE `user_id`='$user_id' AND `group_id`='$group_id'";
			$result = CDateBase::deleteQuery($sql);				
		}
		return $result;
	}		
	
	public static function getById($id)
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT * FROM `".self::$table_name."` WHERE `".self::$table_name."`.`id` = $id";	
		$result = CDateBase::selectQuery($sql);
		return $result;
	}	

	public static function getUserGroupsList($id)
	{
		CMain::includeClass('db.CDateBase');
		CMain::includeClass('groups.CGroups');
		$id = intval($id);		
		$sql = "SELECT * FROM `".self::$table_useres_in_groups."` LEFT JOIN `".CGroups::getTableName()."` ON `".self::$table_useres_in_groups."`.`group_id` = `".CGroups::getTableName()."`.`id` WHERE `user_id` = $id";			
		$result = CDateBase::insertQuery($sql);
		return $result;
	}
	
	public static function getRang($id)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT rang FROM `".self::$table_name."` WHERE `id`='$id'";
		$result = CDateBase::selectQuery($sql);	
		$result = mysql_result($result, 0);
		
		$qResult = self::getUserGroupsList($id);
		while($arGroup = mysql_fetch_array($qResult))
		{
			if($result < $arGroup['rang'])
				$result = $arGroup['rang'];
		}
		return $result;
	}

	public static function isOmnipotent($id)	
	{
		return (intval($id)==1);
	}	
}
?>