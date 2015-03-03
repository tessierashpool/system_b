<?
class CGroups{
	private static $table_name = 't_groups';
	private static $table_rights_list = 't_rights_list';
	private static $last_id = 0;
	static function getList($filter=array(), $sort=array(), $from_count = array())
	{	
		return CDateBase::getListFromTable(self:: $table_name, $filter, $sort, $from_count);	
	}	

	static function getTableName()
	{	
		return self::$table_name;	
	}
	
	static function getById($id)
	{	
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT * FROM `".self::$table_name."` WHERE `".self::$table_name."`.`id` = $id";	
		$result = CDateBase::selectQuery($sql);
		return $result;
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
	
	public static function isValidCode($str)	
	{
		return preg_match('/^[A-z0-9_]+$/i', $str);
	}

	public static function add($arFields = array())	
	{
		CMain::includeClass('db.CDateBase');
		$result = CDateBase::insert($arFields,self::$table_name);
		self::$last_id =  mysql_insert_id();
		return $result;
	}
	
	public static function update($arFields = array(), $id)	
	{
		CMain::includeClass('db.CDateBase');
		$result = CDateBase::update($arFields,self::$table_name,$id);
		return $result;
	}		
	
	public static function lastId()	
	{
		return self::$last_id;
	}	
	
	public static function delete($id)	
	{
		$id = intval($id);		
		$sql = "DELETE FROM `".self::$table_name."` WHERE `id`='$id' ";
		$result = CDateBase::deleteQuery($sql);
		self::deleteRight($id);
		return $result;
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
	
	public static function addRight($group_id,$id)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);		
		$group_id = intval($group_id);		
		//$sql = "INSERT INTO `".$table_rights_list."` (`id`, ".$titleLine.") VALUES (NULL, ".$valueLine.")";	
		$sql = "INSERT INTO `".self::$table_rights_list."` (`id`, `group_id`, `right_id`) VALUES (NULL, ".$group_id.",  ".$id.")";			
		$result = CDateBase::insertQuery($sql);
		return $result;
	}	
	
	public static function getRightsList($id)	
	{
		CMain::includeClass('db.CDateBase');
		CMain::includeClass('rights.CRights');
		$id = intval($id);		
		$sql = "SELECT * FROM `".self::$table_rights_list."` LEFT JOIN `".CRights::getTableName()."` ON `".self::$table_rights_list."`.`right_id` = `".CRights::getTableName()."`.`id` WHERE `group_id` = $id";			
		$result = CDateBase::insertQuery($sql);
		return $result;
	}	
	
	public static function deleteRight($group_id,$id=0)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);		
		$group_id = intval($group_id);	
		if($id==0)
		{
			$sql = "DELETE FROM `".self::$table_rights_list."` WHERE `group_id`='$group_id'";
			$result = CDateBase::deleteQuery($sql);		
		}
		else
		{
			$sql = "DELETE FROM `".self::$table_rights_list."` WHERE `group_id`='$group_id' AND `right_id`='$id'";
			$result = CDateBase::deleteQuery($sql);				
		}
		return $result;
	}	

	public static function getRang($id)	
	{
		CMain::includeClass('db.CDateBase');
		$id = intval($id);
		$sql = "SELECT rang FROM `".self::$table_name."` WHERE `id`='$id'";
		$result = CDateBase::selectQuery($sql);	
		$result = mysql_result($result, 0);
		return $result;
	}		
}
?>