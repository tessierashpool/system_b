<?
class CMain
{
	private static $arLangMsg = array();
	public static $arMsg = array();
	private static $classesIncluded = array();
	private static $componentPath = '';
	static function GC($name='',$arParams = array(),$TEMPLATE_NAME=''){
		if($TEMPLATE_NAME=='')
			$TEMPLATE_NAME = 'default';
		$site_lang = self::getSiteLang();
		
		$cacheParam = array(
			"NAME"=>$name,
			"TEMPLATE"=>$TEMPLATE_NAME,
			"LANG"=>$site_lang
		);	
		
		$site_lang = self::getSiteLang();
		$path = $_SERVER["DOCUMENT_ROOT"].'/system_b/components/'.$name.'/core.php';
		self::$componentPath = '/system_b/components/'.$name.'/template/'.$TEMPLATE_NAME.'/';
		$js_path = $_SERVER["DOCUMENT_ROOT"].'/system_b/components/'.$name.'/template/'.$TEMPLATE_NAME.'/script.js';
		$css_path = $_SERVER["DOCUMENT_ROOT"].'/system_b/components/'.$name.'/template/'.$TEMPLATE_NAME.'/style.css';
		$lang_path = $_SERVER["DOCUMENT_ROOT"].'/system_b/components/'.$name.'/template/'.$TEMPLATE_NAME.'/lang/'.$site_lang.'.php';		
		CCache::setCacheParams($cacheParam);	
		
		if(file_exists($path))
		{		
			if(file_exists($js_path))
				f_js_file('/system_b/components/'.$name.'/template/'.$TEMPLATE_NAME.'/script.js');
							
			if(file_exists($css_path))
				f_css_file('/system_b/components/'.$name.'/template/'.$TEMPLATE_NAME.'/style.css');
				
			self::getLangFile($lang_path);	
			if(file_exists($lang_path))
				require($lang_path);	
				
			require($path);					
		} 
		else 
		{
			return false;
		}

		CCache::stopCache($cacheParam);

	}	
		
	static function componentPath()
	{
		return self::$componentPath;
	}
	static function getAdFiles(){
		echo "{ADITIONALINFO}";
	}	
	static function getTitle(){
		echo "{TITLE}";
	}		
	static function deferredFunctions()
	{
		$content=ob_get_clean();
		global $a_js_file;
		global $a_css_file;
		global $a_msg_array;
		$ADITIONAL_SCRIPT = '';
		$TITLE = '';
		
		preg_match_all('#{!CSS_STYLE}(.+?){CSS_STYLE!}#is', $content, $arrCSS);
		$content=preg_replace("#{!CSS_STYLE}(.+?){CSS_STYLE!}#is",'',$content);		
		if(count($arrCSS[1])>0)
		{
			foreach($arrCSS[1] as $css_style)
				$ADITIONAL_SCRIPT .= "<link href='".$css_style."' rel='stylesheet' type='text/css' />\r\n";
		}		

		preg_match_all('#{!JS_SCRIPT}(.+?){JS_SCRIPT!}#is', $content, $arrJS);
		$content=preg_replace("#{!JS_SCRIPT}(.+?){JS_SCRIPT!}#is",'',$content);	
		if(count($arrJS[1])>0)
		{			
			foreach($arrJS[1] as $jscript)
				$ADITIONAL_SCRIPT .="<script type='text/javascript' src='".$jscript."'></script>\r\n";	
		}

		preg_match_all('#{!TITLE}(.+?){TITLE!}#is', $content, $arrTitle);
		$content=preg_replace("#{!TITLE}(.+?){TITLE!}#is",'',$content);	
		
		$content=preg_replace("!{ADITIONALINFO}!", $ADITIONAL_SCRIPT, $content);	
		if(count($arrTitle)>1)
			$TITLE = $arrTitle[1][0];	
			
		$content=preg_replace("!{TITLE}!", $TITLE, $content);	
			
	
		echo $content;	
	}
	
	static function getSiteLang()
	{
		return 'ru';
	}
	
	public static function dbTrashClean()
	{		
		//Delete from DB "restore hash" with expired time(24 hour)
		$sql = "DELETE FROM `t_pass_restore` WHERE `date_create` < ".(time()-60*60*24)." ";
		$result = @mysql_query($sql);		
	}
	
	static function includeClass($class){
		$path = explode('.',$class);
		include_once($_SERVER["DOCUMENT_ROOT"].'/system_b/classes/'.$path[0].'/'.$path[1].'.php');
		self::$classesIncluded[$class] = $class;
	}	
	
	static function getICList(){
		echo"<pre>";
		print_r(self::$classesIncluded);
		echo"</pre>";
	}
	
	public static function getLangFile($path=''){
		$M = array();
		if($path!='')
		{
			if(file_exists($path))
				require($path);
		}
		else
		{
			$lang_file =  explode('admin_lair/',$_SERVER['PHP_SELF']);
			$lang_file_path =  $_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/lang/'.self::getSiteLang().'/'.$lang_file[1];
			if(file_exists($lang_file_path))
				require($lang_file_path);
		}
		self::$arLangMsg = array_merge(self::$arLangMsg,$M);
	}
	
	public static function getM($key){
		if(!empty(self::$arLangMsg))
		{
			if(array_key_exists($key,self::$arLangMsg))
				return self::$arLangMsg[$key];	
			else
				return "#NOT EXIST#";
		}	
		else
			echo "#LANG ARRAY EMPTY#";
	}
}
?>