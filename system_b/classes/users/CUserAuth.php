<?
class CUserAuth{
	public $isAuthorized = 0;
	public $info = array();
	private $arRights = array();
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
				$session_hash = $session_hash.$myrow['login'];
				$session_hash = $session_hash.$myrow['active'];
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
	
	public function getId()
	{
		return $this->info['id'];
	}	

	public function getRightsArray()
	{
		if(count($this->arRights)>0)
		{
			return  $this->arRights;
		}
		else
		{
			CMain::includeClass('groups.CGroups');
			CMain::includeClass('users.CUsersMain');
			$user_id = $this->getId();
			$queryResultGroups = CUsersMain::getUserGroupsList($user_id);
			$arRights = array();
			while($arGroup = mysql_fetch_array($queryResultGroups))
			{
				$queryResultRights = CGroups::getRightsList($arGroup['group_id']);
				while($arRight = mysql_fetch_array($queryResultRights))
					$arRights[$arRight['code']]=$arRight['code'];
			}
			//Все пользователи
			$queryResultRights = CGroups::getRightsList('3');
			while($arRight = mysql_fetch_array($queryResultRights))
				$arRights[$arRight['code']]=$arRight['code'];	
			//Зарегестрированные
			if($this->isAuthorized())	
				$queryResultRights = CGroups::getRightsList('2');
				while($arRight = mysql_fetch_array($queryResultRights))
					$arRights[$arRight['code']]=$arRight['code'];		
			$this->arRights = $arRights; 		
			return  $arRights;
		}
	}
	
	public function getGalleryRightsArray()
	{
		CMain::includeClass('images.CGallery');
		CMain::includeClass('users.CUsersMain');
		$user_id = $this->getId();
		$queryResultGroups = CUsersMain::getUserGroupsList($user_id);
		$arRights = array();
		while($arGroup = mysql_fetch_array($queryResultGroups))
		{
			$queryResultRights = CGallery::getGroupRights($arGroup['group_id']);
			while($arRight = mysql_fetch_array($queryResultRights))
				$arRights[$arRight['gallery_id']]=$arRight['gallery_id'];
		}
		return  $arRights;
	}

	public function getVideoGalleryRightsArray()
	{
		CMain::includeClass('videos.CVGallery');
		CMain::includeClass('users.CUsersMain');
		$user_id = $this->getId();
		$queryResultGroups = CUsersMain::getUserGroupsList($user_id);
		$arRights = array();
		while($arGroup = mysql_fetch_array($queryResultGroups))
		{
			$queryResultRights = CVGallery::getGroupRights($arGroup['group_id']);
			while($arRight = mysql_fetch_array($queryResultRights))
				$arRights[$arRight['gallery_id']]=$arRight['gallery_id'];
		}
		return  $arRights;
	}	
	
	public function haveRight($right_code)
	{
		$arRights = $this->getRightsArray();
		return in_array($right_code , $arRights);
	}
	
	public function hr($right_code)
	{
		return ($this->haveRight($right_code)||$this->omnipotent());
	}
	
	public function hgr($gallery_id)
	{
		$arRights = $this->getGalleryRightsArray();
		return (in_array($gallery_id , $arRights)||$this->omnipotent());
	}	
	
	public function hvgr($video_gallery_id)
	{
		$arRights = $this->getVideoGalleryRightsArray();
		return (in_array($video_gallery_id , $arRights)||$this->omnipotent());
	}		
	
	public function omnipotent()
	{
		return ($this->info['id']==1);
	}		
	
	public function getRang()
	{
		CMain::includeClass('users.CUsersMain');
		if($this->omnipotent())
			return 1000;
		else
			return CUsersMain::getRang($this->info['id']);
	}	
	public function getInfo()
	{
		return $this->info;
	}	
}
?>