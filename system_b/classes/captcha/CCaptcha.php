<?
class CCaptcha
{
	public static $session_var = 'captcha';
	public static $solt = 'soltXD';
	static function getCaptcha($name="captcha"){
		
		return '<img src="/system_b/classes/captcha/captcha.php?captcha_n='.$name.'" id="'.$name.'" />';
	}	
	static function getReloadLink($name="captcha"){
		return "document.getElementById('".$name."').src='/system_b/classes/captcha/captcha.php?'+Math.random()+'&captcha_n=".$name."';";
	}	
	static function isRightCaptcha($request,$name){
		if(empty($_SESSION[$name]) || md5(trim(strtolower($request)).self::$solt) != $_SESSION[$name])
			return false;
		else
			return true;
	}		
}
?>