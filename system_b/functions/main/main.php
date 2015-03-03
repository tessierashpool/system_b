<?
//Function include classes into file

function f_js_file($e)
{
	echo '{!JS_SCRIPT}'.$e.'{JS_SCRIPT!}';
}

function f_css_file($e)
{
	echo '{!CSS_STYLE}'.$e.'{CSS_STYLE!}';
}

function setTitle($e)
{
	echo '{!TITLE}'.$e.'{TITLE!}';
}

function msgLoader($e,$c='r')
{
	echo '<script type="text/javascript">msgBox("'.$e.'","'.$c.'");</script>';
}			

function safeStr($str,$stripTags=false)
{
	$str = @trim($str);
	if($stripTags)
		$str = strip_tags($str);	
	$str = htmlspecialchars($str);
	$str =  mysql_real_escape_string($str);	
	return $str;
}

function errorLoader($arr=array())
{	
	foreach($arr as $msg)
	{
		msgLoader($msg,'r');
	}
}

function redirect($url)
{	
	header('Location: '.$url.'');
}

function currentPage()
{	
	$tmp = explode('?',$_SERVER['REQUEST_URI']);
	return $tmp[0];
}

function currentPageWithParams($arAddParams=array(),$arDelParams=array())
{	
	$tmp = explode('?',$_SERVER['REQUEST_URI']);
	$tmp_params = array();
	if(isset($tmp[1]))
		$tmp_params = explode('&',$tmp[1]);
		
	$arParams = array();
	foreach($tmp_params as $param)
	{
		$paramNameValAr = explode('=',$param);
		if(!in_array($paramNameValAr[0],$arDelParams))
			$arParams[$paramNameValAr[0]] = $paramNameValAr[1];
	}

	$arParamsMerged = array_merge($arAddParams,$arParams);
	foreach($arParamsMerged as $key=>$value)
	{
		if($value!='')
			$newUrlParams[] = $key.'='.$value;
		else
			$newUrlParams[] = $key;
	}
	if(count($newUrlParams)>0)
	{
		$newUrlParams = implode('&',$newUrlParams);
		$newUrl = $tmp[0].'?'.$newUrlParams;
	}
	else
	{
		$newUrl = $tmp[0];
	}
	return $newUrl;
}

function transformUrlWithParams($url, $arAddParams=array(),$arDelParams=array())
{	
	$tmp = explode('?',$url);
	$tmp_params = array();
	if(isset($tmp[1]))
		$tmp_params = explode('&',$tmp[1]);
		
	$arParams = array();
	foreach($tmp_params as $param)
	{
		$paramNameValAr = explode('=',$param);
		if(!in_array($paramNameValAr[0],$arDelParams))
			$arParams[$paramNameValAr[0]] = $paramNameValAr[1];
	}

	$arParamsMerged = array_merge($arAddParams,$arParams);
	foreach($arParamsMerged as $key=>$value)
	{
		if($value!='')
			$newUrlParams[] = $key.'='.$value;
		else
			$newUrlParams[] = $key;
	}
	if(count($newUrlParams)>0)
	{
		$newUrlParams = implode('&',$newUrlParams);
		$newUrl = $tmp[0].'?'.$newUrlParams;
	}
	else
	{
		$newUrl = $tmp[0];
	}
	return $newUrl;
}

function start_log($text)
{	
	file_put_contents($_SERVER["DOCUMENT_ROOT"]."/system_b/log.txt",$text."\r\n",FILE_APPEND); 
}
?>