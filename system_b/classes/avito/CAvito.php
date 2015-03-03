<?
class CAvito{
	private static $page_url = "http://www.avito.ru/dagestan";
	private static $avito_doman = "http://www.avito.ru";
	private static $pages_links = array();
	static function getPages()
	{	
		$path = $_SERVER["DOCUMENT_ROOT"]."/system_b/avito/avito.txt";
		//$xmlPath = file_get_contents(self::$page_url);
		//file_put_contents($path,$xmlPath);
		self::parsePagesURL($path);
		$pages_links = self::$pages_links;
		foreach($pages_links as $key=>$link)
		{
			$detail_page_url = self::$avito_doman.$link;
			echo $detail_page_url."<br />";
			//$page_contant = file_get_contents($detail_page_url);
			$save_path = $_SERVER["DOCUMENT_ROOT"]."/system_b/avito/page_".$key.".txt";
			//file_put_contents($save_path,$page_contant);
		}
	}	

	static function parsePagesURL($URL)
	{	
		$title = 'Not found';
		$DOM = new doMDocument();
		if(@$DOM -> loadHTMLFile($URL))
		{
			$Xpath = new doMXPath($DOM);
			$a_tags = $DOM -> getElementsByTagName('h3');
		}
		$pages_links = array();
		foreach($a_tags as $a_tag)	
		{
			if($a_tag->getAttribute('class')=='title')
			{
				$nodes = $a_tag->getElementsByTagName('a');
				$pages_links[] = $nodes->item(0)->getAttribute('href');	
				
			}			
		}
		self::$pages_links = $pages_links;
	}

	static function getPagesLink()
	{	
		return self::$pages_links;		
	}	
}
?>