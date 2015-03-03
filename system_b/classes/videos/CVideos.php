<?
class CVideos
{
	private static $table_name='t_videos';
	private static $gallery_table_name='t_video_gallery_list';
	private static $image_db;
	private static $last_id=0;
	
	public static function add($video=array())
	{
		CMain::includeClass('db.CDateBase');
		$url = safeStr($video['url']);
		$url_video_id = safeStr($video['url_video_id']);
		$name = safeStr($video['name']);
		$source = safeStr($video['source']);
		$moderator_id = 0;
		if($video['moderator_id']>0)
			$moderator_id = intval($video['moderator_id']);
		$gallery_id = intval($video['gallery_id']);
		$active = safeStr($video['active']);
		$date_insert = time();
		$resul_id = 0;
		$sql = "INSERT INTO `".self::$table_name."` (`id`, `url`, `url_video_id`, `name`, `source`, `gallery_id`, `active`, `deleted`, `moderator_id`, `date_insert`) VALUES (NULL, '".$url."', '".$url_video_id."', '".$name."', '".$source."', '".$gallery_id."', '".$active."', 'N', '".$moderator_id."', '".$date_insert."');";
		$result = CDateBase::insertQuery($sql);
		if($result)
			$resul_id = self::$last_id =  mysql_insert_id();

		return $resul_id;
	}
	
	public static function lastId()	
	{
		return self::$last_id;
	}	
	
	public static function galleryTableName()	
	{
		return self::$gallery_table_name;
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
		$result = self::removeFromDb($id);			
		return $result;
	}		

	static function getYoutubeVideo($url='',$width='600', $height='337')
	{	
		$url = 'https://www.youtube.com/v/'.$url.'?version=3';
		echo '<object width="600" height="337">';
		echo '<param name="movie" value="'.$url.'"></param>';
		echo '<param name="allowFullScreen" value="true"></param>';
		echo '<param name="allowScriptAccess" value="always"></param>';
		echo ' <embed src="'.$url.'" wmode="opaque" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="600" height="337"></embed>';
		echo '</object>';
	}
	
	static function getTableName()
	{	
		return self::$table_name;	
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
		
}
?>