<?
class CGallery
{
	private static $table_name='t_gallery_list';
	private static $t_group_rights='t_gallery_rights';
	private static $last_id = 0;
	
	static function getTableName()
	{	
		return self::$table_name;	
	}
	
	public static function getId($code) {
		$code = SafeStr($code);
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT id FROM `".self::$table_name."` WHERE `code`='$code'";
		$result = CDateBase::selectQuery($sql);	
		$id = 0;
		if($result)
			if($id = mysql_fetch_array($result))
				$id = intval($id);
		return $id;
	}		
	
	public static function isSystem($id)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT id, type FROM `".self::$table_name."` WHERE `id`='$id'";
		$result = CDateBase::selectQuery($sql);
		if($result&&$myrow = mysql_fetch_array($result))
		{
			if($myrow['type']=='s')
				return true;
			else
				return false;
		}
		else
		{
			return true;
		}
	}

	public static function getById($id)
	{	
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT * FROM `".self::$table_name."` WHERE `".self::$table_name."`.`id` = $id";	
		$result = CDateBase::selectQuery($sql);
		return $result;
	}
	
	static function getList($filter=array(), $sort=array(), $from_count = array())
	{	
		return CDateBase::getListFromTable(self:: $table_name, $filter, $sort, $from_count);	
	}	
	
	public static function delete($id)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);		
		$sql = "DELETE FROM `".self::$table_name."` WHERE `id`='$id' ";
		$result = CDateBase::deleteQuery($sql);
		return $result;
	}	
	
	public static function update($arFields = array(), $id)	
	{
		CMain::includeClass('db.CDateBase');
		$result = CDateBase::update($arFields,self::$table_name,$id);
		return $result;
	}
	
	public static function add($arFields = array())	
	{
		CMain::includeClass('db.CDateBase');
		$result = CDateBase::insert($arFields,self::$table_name);
		self::$last_id =  mysql_insert_id();
		return $result;
	}
	
	public static function isValidCode($str)	
	{
		return preg_match('/^[A-z0-9_]+$/i', $str);
	}

	public static function codeExist($code)
	{	
		CMain::includeClass('db.CDateBase');
		$code = safeStr($code,true);
		$sql = "SELECT id FROM `".self::$table_name."` WHERE `code`='$code'";
		$result = CDateBase::selectQuery($sql);
		$myrow = mysql_fetch_array($result);
		return (!empty($myrow['id']));
	}	

	public static function notEmpty($id)	
	{
		return (intval(self::size($id))>0);
	}		
	
	public static function size($id)	
	{
		CMain::includeClass('db.CDateBase');
		CMain::includeClass('images.CImages');
		$id = intval($id);
		$sql = "SELECT COUNT(*) FROM `".CImages::getTableName()."` WHERE `gallery_id`='$id'";	
		$result = CDateBase::selectQuery($sql);	
		$result = mysql_result($result, 0);
		return $result;
	}		

	public static function getGroupRights($id)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT * FROM `".self::$t_group_rights."`  WHERE `group_id`='$id'";	
		$result = CDateBase::selectQuery($sql);	
		return $result;
	}	
	
	public static function addGroupRight($group_id, $gallery_id)	
	{
		CMain::includeClass('db.CDateBase');
		$gallery_id = intval($gallery_id);		
		$group_id = intval($group_id);			
		$sql = "INSERT INTO `".self::$t_group_rights."` (`id`, `group_id`, `gallery_id`) VALUES (NULL, ".$group_id.",  ".$gallery_id.")";			
		$result = CDateBase::insertQuery($sql);
		return $result;
	}	
	
	public static function deleteGroupRight($group_id,$id=0)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);		
		$group_id = intval($group_id);	
		if($id==0)
		{
			$sql = "DELETE FROM `".self::$t_group_rights."` WHERE `group_id`='$group_id'";
			$result = CDateBase::deleteQuery($sql);		
		}
		else
		{
			$sql = "DELETE FROM `".self::$t_group_rights."` WHERE `group_id`='$group_id' AND `gallery_id`='$id'";
			$result = CDateBase::deleteQuery($sql);				
		}
		return $result;
	}	
}
?>