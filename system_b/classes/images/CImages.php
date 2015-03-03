<?
class CImages
{
	private static $table_name='t_images';
	private static $gallery_table_name='t_gallery_list';
	private static $image_db;
	private static $last_id=0;
	
	public static function saveInDb($file=array(),$table_name='')
	{
		CMain::includeClass('db.CDateBase');
		$url = $file['URL'];
		$name = $file['NAME'];
		$type = $file['TYPE'];
		$size = $file['SIZE'];
		$height = $file['HEIGHT'];
		$width = $file['WIDTH'];
		$md5 = $file['MD5'];
		$activated = $file['ACTIVATED'];
		$animated = $file['ANIMATED'];
		$gallery_id = $file['GALLERY_ID'];
		$date_insert = time();
		$resul_id = 0;
		$sql = "INSERT INTO `".self::$table_name."` (`id`, `url`, `name`, `type`, `size`, `height`, `width`, `md5`, `animated`, `date_insert`, `activated`, `gallery_id`) VALUES (NULL, '".$url."', '".$name."', '".$type."', '".$size."', '".$height."', '".$width."', '".$md5."', '".$animated."', '".$date_insert."', '".$activated."', '".$gallery_id."');";
		$result = CDateBase::insertQuery($sql);
		if($result)
			$resul_id = self::$last_id =  mysql_insert_id();


		return $resul_id;
	}
	
	public static function lastId()	
	{
		return self::$last_id;
	}	
	
	public static function exist($md5,$name)	
	{
		CMain::includeClass('db.CDateBase');
		$login = safeStr($login,true);
		$sql = "SELECT id FROM `".self::$table_name."` WHERE `name`='$name' AND `md5`='$md5'";
		$result = CDateBase::selectQuery($sql);
		$myrow = mysql_fetch_array($result);
		return intval($myrow['id']);
	}
	
	public static function galleryTableName()	
	{
		return self::$gallery_table_name;
	}	
	
	public static function isAnimatedGif($filename) {
		return (bool)preg_match('#(\x00\x21\xF9\x04.{4}\x00\x2C.*){2,}#s', file_get_contents($filename));
	}
	
	public static function getGalleryId($code) {
		$code = SafeStr($code);
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT id FROM `".self::$gallery_table_name."` WHERE `code`='$code'";
		$result = CDateBase::selectQuery($sql);	
		$id = 0;
		if($result)
			if($id = mysql_fetch_array($result))
				$id = intval($id);
		return $id;
	}	
	
	static function getList($filter=array(), $sort=array(), $from_count = array())
	{	
		return CDateBase::getListFromTable(self:: $table_name, $filter, $sort, $from_count);	
	}	
	
	static function notOne($md5)
	{	
		CMain::includeClass('db.CDateBase');
		$sql = "SELECT COUNT(*) FROM `".self::$table_name."` WHERE `md5`='$md5'";
		$result = CDateBase::selectQuery($sql);	
		$result = mysql_result($result, 0);
		return (intval($result)>1);
	}		

	static function removeFromDb($id)
	{	
		$id = intval($id);
		CMain::includeClass('db.CDateBase');
		$sql = "DELETE FROM `".self::$table_name."` WHERE `id`='$id'";
		$result = CDateBase::deleteQuery($sql);		
		return $result;
	}	
	
	static function deleteWinkWink($id)
	{	
		CMain::includeClass('db.CDateBase');
		$arField = array('deleted'=>'Y');
		$result = CDateBase::update($arField, self::$table_name, $id);		
		return $result;
	}	
	static function restoreWinkWink($id)
	{	
		CMain::includeClass('db.CDateBase');
		$arField = array('deleted'=>'N');
		$result = CDateBase::update($arField, self::$table_name, $id);		
		return $result;
	}		

	static function getById($id)
	{	
		$id = intval($id);
		CMain::includeClass('db.CDateBase');
		$sql = "SELECT * FROM `".self::$table_name."` WHERE `id`='$id'";
		$result = CDateBase::selectQuery($sql);	
		return $result;
	}
	
	static function delete($id)
	{	
		CMain::includeClass('db.CDateBase');
		$result = self::getById($id);	
		$result = mysql_fetch_array($result);
		if(!self::notOne($result['md5']))
		{
			@unlink($_SERVER["DOCUMENT_ROOT"].$result['url']);
		}	
		self::removeFromDb($id);			
		return $result;
	}		
	
	static function activate($id, $active = 'Y')
	{	
		CMain::includeClass('db.CDateBase');
		if($active != 'N')
			$active = 'Y';
		$arField = array('active'=>$active);
		$result = CDateBase::update($arField, self::$table_name, $id);		
		return $result;
	}	
	
	static function getTableName()
	{	
		return self::$table_name;	
	}	
	public static function removeNotUsedImages() {
		$result = self::getList(array(0=>array('NAME'=>'activated','VALUE'=>'N', 'OPERATOR'=>'='),1=>array('NAME'=>'date_insert','VALUE'=>(time()-(60*60*24)), 'OPERATOR'=>'<')));
		while($resFetch = mysql_fetch_array($result))
		{
			if(self::notOne($resFetch['md5']))
			{
				self::removeFromDb($resFetch['id']);
			}
			else	
			{
				if(self::removeFromDb($resFetch['id']))
					unlink($_SERVER["DOCUMENT_ROOT"].$resFetch['url']);
			}			
		}	
	}		
}
?>