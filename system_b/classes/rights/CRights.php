<?
class CRights{
	private static $table_name = 't_rights';
	private static $categoty_table = 't_rights_category';
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
		return $result;
	}	
	
	public static function update($arFields = array(), $id)	
	{
		CMain::includeClass('db.CDateBase');
		$result = CDateBase::update($arFields,self::$table_name,$id);
		return $result;
	}	
	
	public static function delete($id)	
	{
		$id = intval($id);		
		$sql = "DELETE FROM `".self::$table_name."` WHERE `id`='$id' ";
		$result = CDateBase::deleteQuery($sql);
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

	public static function getCategoriesList()	
	{
		CMain::includeClass('db.CDateBase');
		$sql = "SELECT * FROM `".self::$categoty_table."` ORDER BY `order` ASC";
		$result = CDateBase::selectQuery($sql);
		return $result;
	}	
}
?>