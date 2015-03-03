<?
class CCache
{
	public static $cacheExist =false;
	private static $cacheType = 'C';
	private static $cacheTime = 86400;
	private static $timeNotExpired = true;
	private static $tableName = '';
	private static $componentName = '';
	private static $templateName = '';
	private static $filePath = '';
	private static $timePath = '';
	private static $cacheInProcess = false;
	private static $cacheDisabled = false;
	private static $folderHash = '';
	private static $innerTableNames = array();

	public static function setCacheParams($parametrs = array(), $type='C', $time=86400)
	{
		//Откючение кеширования
		if(isset($_REQUEST['cache_disabled'])&&($_REQUEST['cache_disabled']=='Y'))
		{
			self::$cacheDisabled = true;
		}
		
		//Если в даный момент не происходит кеширование другого компонента,страницы и т.д.		
		if(!self::$cacheInProcess&&!self::$cacheDisabled)
		{
			self::$cacheType = $type;
			self::$componentName = $parametrs['NAME'];
			self::$templateName = $parametrs['TEMPLATE'];
			
			self::$cacheTime = $time+time();
			$code_for_md5 = $parametrs['TEMPLATE'].$parametrs['LANG'];
			self::$folderHash = md5($code_for_md5);
			
			self::$filePath = $_SERVER["DOCUMENT_ROOT"]."/system_b/cache/".self::$cacheType."/".self::$componentName."/".self::$folderHash."/cache.cache";
			self::$timePath = $_SERVER["DOCUMENT_ROOT"]."/system_b/cache/".self::$cacheType."/".self::$componentName."/".self::$folderHash."/time.time";		
			self::$cacheExist = file_exists(self::$filePath);				
		}
		
		//Удаление кэша с данными параметрами кэша
		if(isset($_REQUEST['cache_update'])&&($_REQUEST['cache_update']=='Y'))
		{
			CMain::includeClass('main.CAdditional');
			//start_log(time()."#".$_SERVER["DOCUMENT_ROOT"]."/system_b/cache/".self::$cacheType."/".self::$componentName."/".self::$folderHash."/");
			@CAdditional::deleteDir($_SERVER["DOCUMENT_ROOT"]."/system_b/cache/".self::$cacheType."/".self::$componentName."/".self::$folderHash."/");
			self::$cacheExist = false;
		}	
		
	}
	public static function resetParams()
	{
		self::$cacheType = 'C';
		self::$componentName = '';
		self::$templateName = '';
		echo self::$filePath = '';
		echo self::$timePath = '';
		self::$cacheExist = false;
		self::$cacheTime = 86400;
		$code_for_md5 = $parametrs['TEMPLATE'].$parametrs['LANG'];
		self::$folderHash = '';		
		self::$cacheInProcess = false;	
	}	
	
	//Кеширование компонента
	public static function startCache($time=86400)
	{		
		//Если в даный момент не происходит кеширование другого компонента,страницы и т.д.
		if(!self::$cacheInProcess&&!self::$cacheDisabled)
		{
			//Проверка срока годности кеша
			$timeForCache = intval(@file_get_contents(self::$timePath));
			//start_log("timeForCache-".$timeForCache);
			self::$timeNotExpired = (time()<$timeForCache);
		//	start_log("timeNotExpired-".self::$timeNotExpired);
			//Если кеш существует и срок его не прошел
			if(self::$cacheExist&&self::$timeNotExpired)
			{
				include($_SERVER["DOCUMENT_ROOT"]."/system_b/cache/".self::$cacheType."/".self::$componentName."/".self::$folderHash."/cache.cache");
				return false;
			}
			else
			{
				self::$cacheTime = $time+time();
				//start_log(time()."#".self::$cacheTime);
				ob_start();
				self::$cacheInProcess = true;
				return true;
			}
		}
		else
		{
			return true;
		}
	}
	//Конец кеширования компонента
	public static function stopCache($parametrs = array())
	{
		if((self::$componentName==$parametrs['NAME'])&&(self::$templateName==$parametrs['TEMPLATE'])&&self::$cacheInProcess&&(!self::$cacheDisabled))
		{
			if(!self::$cacheExist||!self::$timeNotExpired)
			{
				@mkdir($_SERVER["DOCUMENT_ROOT"]."/system_b/cache/".self::$cacheType."/".self::$componentName."/".self::$folderHash."/",0777,true);
				$content = ob_get_clean();
				file_put_contents(self::$filePath,$content, LOCK_EX); 
				file_put_contents(self::$timePath,self::$cacheTime, LOCK_EX); 
				echo $content;
				self::resetParams();
			}

		}	
	}	
	//Кеширование части страницы
	public static function cachePage()
	{	
		$md5Page = md5(currentPage());
		$cacheParam = array(
			"NAME"=>$md5Page,
			"TEMPLATE"=>$md5Page,
			"LANG"=>CMain::getSiteLang()
		);			
		self::setCacheParams($cacheParam,'P');
		return self::startCache();
	}
	//Конец кеширования страницы
	public static function stopCachePage()
	{	
		$md5Page = md5(currentPage());
		$cacheParam = array(
			"NAME"=>$md5Page,
			"TEMPLATE"=>$md5Page,
			"LANG"=>CMain::getSiteLang()
		);			
		self::stopCache($cacheParam);
	}	
	
	//Удалить весь кэш. Remove all cache
	public static function removeAllCache()
	{	
		CMain::includeClass('main.CAdditional');
		@CAdditional::deleteDir($_SERVER["DOCUMENT_ROOT"]."/system_b/cache/C/");
		@CAdditional::deleteDir($_SERVER["DOCUMENT_ROOT"]."/system_b/cache/P/");
		@CAdditional::deleteDir($_SERVER["DOCUMENT_ROOT"]."/system_b/cache/T/");
	}	
}
?>