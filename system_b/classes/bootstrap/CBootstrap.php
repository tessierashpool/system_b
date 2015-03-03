<?
class CBootstrap
{
	private static $path_css = '/system_b/classes/bootstrap/bootstrap-3.2.0-dist/css/';
	private static $path_glyphicon_css = '/system_b/classes/bootstrap/bootstrap-3.2.0-dist/glyphicon/css/';
	private static $path_js = '/system_b/classes/bootstrap/bootstrap-3.2.0-dist/js/';
	
	public static function getFiles()
	{
		f_js_file(self::$path_js.'bootstrap.min.js');
		f_css_file(self::$path_css.'bootstrap.min.css');
		f_css_file(self::$path_css.'bootstrap-theme.min.css');
	}
	
	public static function getCss()
	{
		f_css_file(self::$path_css.'bootstrap.min.css');
	}
	public static function getCssTheme()
	{
		f_css_file(self::$path_css.'bootstrap-theme.min.css');
	}
	

	public static function getJs()
	{
		f_js_file(self::$path_js.'bootstrap.min.js');
	}	

	public static function getIcon()
	{
		f_css_file(self::$path_glyphicon_css.'bootstrap.css');
	}		
}
?>