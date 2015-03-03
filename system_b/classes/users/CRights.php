<?
class CUserAuth{
	public $isAuthorized = 0;
	public $info = array();
	//This function registrate new user 
	//Функция регестрирует нового пользователя
	function __construct()
	{
		if(isset($_COOKIE['userid'])&&isset($_COOKIE['hashcode']))
		{
			$_SESSION['userid'] = $_COOKIE['userid'];
			$_SESSION['hashcode'] = $_COOKIE['hashcode'];
		}
		if(isset($_SESSION['userid'])&&isset($_SESSION['hashcode'])&&$_SESSION['userid']!=''&&$_SESSION['hashcode']!='')
		{
			CMain::includeClass('db.CDateBase');
			$id = safeStr($_SESSION['userid']);
			$hashcode = safeStr($_SESSION['hashcode']);
			
			$sql = "SELECT * FROM `t_users` WHERE `id`='$id'";
			$result = CDateBase::selectQuery($sql);	
			if($myrow = mysql_fetch_array($result))
			{
				$session_hash = substr($myrow['pass'], -32);
				if(md5($id.strrev($session_hash))==$hashcode)
				{
					$this->info = $myrow;
					$this->isAuthorized = 1;
				}
				else
				{
					$this->logout();
					$this->isAuthorized = 0;
				}
			}
			else
			{
				$this->logout();
				$this->isAuthorized = 0;
			}
		}
		else
		{
			$this->logout();
			$this->isAuthorized = 0;
		}
	}
	
	public function isAuthorized()
	{

		if($this->isAuthorized==1)
			return true;
		else
			return false;
	}	
	
	public function logout()
	{
		unset($_SESSION['userid']);
		unset($_SESSION['hashcode']);
		setcookie ("userid", "", time() - 3600);
		setcookie ("hashcode", "", time() - 3600);
	}


}
?>