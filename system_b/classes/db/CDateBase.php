<?
class CDateBase
{
	private static $table_name = '';
	private static $n_page = 0;
	private static $n_elements_on_page = 20;
	private static $ar_elements_on_page = array(5,10,20,50,100,200);
	private static $filter = array();
	private static $n_elements_total = 0;
	private static $n_total_pages = 0;
	
	static function selectQuery($query, $t_name_for_cache='')
	{
		$result = mysql_query($query);
		return $result;
	}
	
	static function deleteQuery($query, $t_name_for_cache='')
	{
		$result = mysql_query($query);
		return $result;
	}	
	
	static function updateQuery($query, $t_name_for_cache='')
	{
		$result = mysql_query($query);
		return $result;
	}
	
	static function insertQuery($query, $t_name_for_cache='')
	{
		$result = mysql_query($query);
		return $result;
	}	

	static function insert($arFields = array(), $table_name)
	{
		$arTitleLine = array();
		$arValueLine = array();
		foreach($arFields as $key=>$value)
		{
			$value = safeStr($value);
			$key = safeStr($key);
			$arTitleLine[] = "`".$key."`";
			$arValueLine[] = "'".$value."'";
		}	
		$titleLine = implode(',',$arTitleLine);
		$valueLine = implode(',',$arValueLine);
		
		$sql = "INSERT INTO `".$table_name."` (`id`, ".$titleLine.") VALUES (NULL, ".$valueLine.")";
		
		$result = self::insertQuery($sql);
		return $result;		
	}	
	static function update($arFields = array(), $table_name, $id)
	{
		$id = intval($id);
		$setLine ='';
		$setLineAr =array();
		foreach($arFields as $key=>$value)
		{
			$value = safeStr($value);
			$key = safeStr($key);
			$setLineAr[] = "`".$key."` = '".$value."'";  
		}	
		$setLine = implode(',',$setLineAr);
			
		$sql = "UPDATE `".$table_name."` SET ".$setLine." WHERE `id` = $id LIMIT 1";
		$result = self::updateQuery($sql);					
		return $result;		
	}		

	public static function getListFromTable($tableName, $filter=array(), $sort=array(), $from_count = array(),$join=array())
	{	
		self::$table_name = $tableName;
		self::$filter = $filter;
		if(count($filter)>0)
		{
			$arJoin = array();
			if(isset($filter['JOIN']))
			{
				//print_r($filter);
				$arJoin = $filter['JOIN'];
				unset($filter['JOIN']);
			}	
			
			foreach($filter as $where)
			{
				$whereArr[] = "`".$where['NAME']."` ".$where['OPERATOR']." '".$where['VALUE']."'";
			}
			if(count($whereArr)>0)
				$whereStr = implode(' AND ',$whereArr);
		}
			
		if(count($sort)>0)	
		{
			foreach($sort as $key=>$direction)
			{
				$sortArr[] = "`".$tableName."`.`".safeStr($key)."` ".safeStr($direction);
			}	
			$sortStr = implode(', ',$sortArr);
		}
		else
		{
			$sortStr.= "`".$tableName."`.`id` ASC";
		}
		
		if(count($from_count)>0)
		{
			$page = intval($from_count[0]);
			$elements_on_page = intval($from_count[1]);
			if(in_array($elements_on_page,self::$ar_elements_on_page))
			{
				self::$n_elements_on_page = $elements_on_page;		
			
			}
			else
			{	
				self::$n_elements_on_page = $elements_on_page = self::$ar_elements_on_page[0];	
			}
			self::$n_elements_total = $elements_total = self::totalCount(); 
			self::$n_total_pages = $total_page = ceil($elements_total/$elements_on_page);
			if(empty($page) or $page < 1) 
				$page = 1; 
			if($page > $total_page)
				$page = $total_page;				
			self::$n_page = $page;			
			$page=$page-1;
			$limitStr = $elements_on_page*$page." , ".$elements_on_page;
		}
		
		if(count($arJoin)>0)
			$join = $arJoin;
		if(count($join)>0)
		{
			$join_line = '';
			foreach($join as $join_params)
			{
				if(isset($join_params['TABLE_NAME'])&&isset($join_params['TABLE_COLUMN']))
				{
					$pt_col = 'id';
					if(isset($join_params['PRIME_TABLE_COLUMN']))
						$pt_col = $join_params['PRIME_TABLE_COLUMN'];
					$join_line .= " JOIN `".$join_params['TABLE_NAME']."` ON `".$tableName."`.`".$pt_col."`=`".$join_params['TABLE_NAME']."`.`".$join_params['TABLE_COLUMN']."`";
					if(isset($join_params['TABLE_WHERE']))
						foreach($join_params['TABLE_WHERE'] as $join_table_where)
							$join_line .= " AND `".$join_params['TABLE_NAME']."`.`".$join_table_where['NAME']."` ".$join_table_where['OPERATOR']." '".$join_table_where['VALUE']."'";
				}
			}
		}
		
		$sql = "SELECT *, `".$tableName."`.`id` as `id` FROM `".$tableName."`";
	
		if($join_line!='')
			$sql .= $join_line;	
			
		if($whereStr!='')
			$sql .= " WHERE ".$whereStr;	
			
		$sql .= " ORDER BY ".$sortStr;		
		
		if($limitStr!='')
			$sql .= " LIMIT ".$limitStr;	

		$result = self::selectQuery($sql);
		return $result;
	}
	
	public static function totalCount()
	{	
		if(self::$n_elements_total>0)
		{
			$result = self::$n_elements_total;
		}
		else
		{
			$filter = self::$filter;
			if(count($filter)>0)
			{
			
				$arJoin = array();
				if(isset($filter['JOIN'])&&is_array($filter['JOIN']))
				{
					$arJoin = $filter['JOIN'];
					unset($filter['JOIN']);
				}
				
				foreach($filter as $where)
				{
					$whereArr[] = "`".$where['NAME']."` ".$where['OPERATOR']." '".$where['VALUE']."'";
				}
				
				if(count($whereArr)>0)
					$whereStr = implode(' AND ',$whereArr);
					
				if(count($arJoin)>0)
				{
					$join_line = '';
					foreach($arJoin as $join_params)
					{
						if(isset($join_params['TABLE_NAME'])&&isset($join_params['TABLE_COLUMN']))
						{
							$pt_col = 'id';
							if(isset($join_params['PRIME_TABLE_COLUMN']))
								$pt_col = $join_params['PRIME_TABLE_COLUMN'];
							$join_line .= " JOIN `".$join_params['TABLE_NAME']."` ON `".self::$table_name."`.`".$pt_col."`=`".$join_params['TABLE_NAME']."`.`".$join_params['TABLE_COLUMN']."`";
							if(isset($join_params['TABLE_WHERE']))
								foreach($join_params['TABLE_WHERE'] as $join_table_where)
									$join_line .= " AND `".$join_params['TABLE_NAME']."`.`".$join_table_where['NAME']."` ".$join_table_where['OPERATOR']." '".$join_table_where['VALUE']."'";
						}
					}
				}					
			}	
			$sql = "SELECT COUNT(*) FROM `".self::$table_name."`";	
			if($join_line!='')
				$sql .= $join_line;	
			if($whereStr!='')
				$sql .= " WHERE ".$whereStr;			
				

			$result = self::selectQuery($sql);
			$result = mysql_result($result, 0);
		}
		return $result;
	}
	
	static function pageNav($template = 'default')
	{
		CMain::GC('__page_nav',array('TOTAL_PAGE'=>self::$n_total_pages, 'PAGE'=>self::$n_page, 'ELEMENTS_ON_PAGE'=>self::$n_elements_on_page, 'ARR_ELEMENTS_ON_PAGE'=>self::$ar_elements_on_page), $template);
	}	

	static function setElementsOnPageForSelect($select = array())
	{
		self::$ar_elements_on_page = $select;
	}		
			
}
?>