<?
class CAdminPage{
	public static $arAdminButtons = array();
	private static $errorMsgs = array();
	private static $successMsgs = array();
	private static $filter_values = array();
	private static $filterActive = false;
	private static $pageTitleIcon = '';
	private static $arTabs = false;
	static function adminButtons($arButtons = array())
	{
		self::$arAdminButtons = $arButtons;
	}
	static function getAdminButtons($arButtons = array())
	{
		return self::$arAdminButtons;
	}	
	
	static function getDropList($values=array(),$titles=array(),$name,$cur_val='',$class='', $function='',$default='')
	{
		if($cur_val=='')
			$cur_val = $default;	
		foreach($values as $key=>$value)
		{		
			if($cur_val == $value )
				$option_line .= '<option value="'.$value.'" selected="selected">'.$titles[$key].'</option>';
			else
				$option_line .= '<option value="'.$value.'">'.$titles[$key].'</option>';				
		}
		$function_ln = '';
		if($function!='')
		{
			$function_ln = 'onchange="'.$function.'(this);"';
		}
		
		if($class!='')
			$select = '<select name="'.$name.'" class="'.$class.'" '.$function_ln.'>'.$option_line.'</select>';
		else
			$select = '<select name="'.$name.'" '.$function_ln.'>'.$option_line.'</select>';
			
		echo $select;	
	}
	
	static function errorMsg($errorMsg)
	{
		self::$errorMsgs[] = $errorMsg;
	}
	
	static function successMsg($successMsg)
	{
		self::$successMsgs[] = $successMsg;
	}
	
	static function haveErrors()
	{
		return (count(self::$errorMsgs)>0);
	}	
	
	static function haveSuccess()
	{
		return (count(self::$successMsgs)>0);
	}	
	
	static function getMsgs()
	{
		if(self::haveSuccess())
		{
			echo "<br />";
			foreach(self::$successMsgs as $success)
			{	
				echo '<p class="a_success_msg">'.stripcslashes($success).'</p>';
			}							
		}
		if(self::haveErrors())
		{
			echo "<br />";
			foreach(self::$errorMsgs as $error)
			{	
				echo '<p class="a_error_msg">'.$error.'</p>';
			}	
		}		
	}	

	static function setFilter($filter_val=array())
	{
		self::$filter_values = $filter_val;
	}	

	static function getFilter()
	{
		return self::$filter_values;
	}	
	
	static function setFilterActive()
	{
		self::$filterActive = true;
	}		
	
	static function showList($arSettings = array(), $table_name, $use_filter=true)
	{
		echo '<div class="a_table_section_list">';
		if($use_filter)
			CMain::GC('__a_list_filter',array('FIELDS_ARRAY'=>$arSettings['FILTER']));
	
		CMain::GC('__a_list',array('FIELDS_ARRAY'=>$arSettings['FIELDS'],"TABLE_NAME"=>$table_name, 'MENU_SETTINGS'=>$arSettings['MENU_SETTINGS']));
		echo '</div>';
	}
	
	static function showGallery($arSettings = array(), $table_name, $use_filter=true)
	{
		echo '<div class="a_table_section_list">';
		if($use_filter)
			CMain::GC('__a_list_filter',array('FIELDS_ARRAY'=>$arSettings['FILTER']));
		$id=0;
		if(isset($_REQUEST['id']))
			$id=intval($_REQUEST['id']);
		CMain::GC('__a_gallery',array('GALLERY_ID'=>$id,"TABLE_NAME"=>$table_name, 'MENU_SETTINGS'=>$arSettings['MENU_SETTINGS']));
		echo '</div>';
	}	
	
	static function showVideoGallery($arSettings = array(), $table_name, $use_filter=true)
	{
		echo '<div class="a_table_section_list">';
		if($use_filter)
			CMain::GC('__a_list_filter',array('FIELDS_ARRAY'=>$arSettings['FILTER']));
		$id=0;
		if(isset($_REQUEST['id']))
			$id=intval($_REQUEST['id']);
		CMain::GC('__a_video_gallery',array('GALLERY_ID'=>$id,"TABLE_NAME"=>$table_name, 'MENU_SETTINGS'=>$arSettings['MENU_SETTINGS']));
		echo '</div>';
	}	
	
	static function setElementsOnPageForSelect($select = array())
	{
		CDateBase::setElementsOnPageForSelect($select);
	}

	static function setPageTitleIcon($icon)
	{
		self::$pageTitleIcon = $icon;
	}	
	static function pageTitle($title,$description)
	{
		echo '<div class="page_title">';
			if(self::$pageTitleIcon!='')
				echo '<div class="page_title_icon" style="background-image:url(\'./images/title_icons/'.self::$pageTitleIcon.'\');"></div>';
			echo '<div class="page_title_title">';
			echo '<h2>'.$title.'</h2>';
			echo '<h3>'.$description.'</h3>';
			echo '</div>';
			echo '<div style="clear:both"></div>';
				self::getMsgs();
		echo '</div>';	
		echo '<div class="a_sect_separator"></div>';
	}
	
	static function startPage()
	{
		echo '<div class="a_table_section_page">';
	}

	static function endPage()
	{
		echo '</div>';
	}	
	static function setTabs($arTabs)
	{
		self::$arTabs = $arTabs;
	}	

	static function getTabsMenu()
	{
		echo '<div class="a_tabs_cont">';
			echo '<div class="a_tabs_cont_inner a_tabs_cont_inner_1">';
				$count = 1;
				foreach(self::$arTabs as $key => $tabName)
				{
					if($count==1)
					{
						echo '<div onclick="selectTab(\''.$key.'\',this);" class="a_tab_btn a_tab_div_active">';
							echo $tabName;
						echo '</div>';							
					}
					else
					{
						echo '<div onclick="selectTab(\''.$key.'\',this);" class="a_tab_btn">';
							echo  $tabName;
						echo '</div>';						
					}

					$count++;
				}
				echo '<div style="clear:both"></div>';
			echo '</div>';
		echo '</div>';
	}

	static function startTabCont($tabId,$first=false)
	{	
		if($first)
			echo '<div class="a_tab a_tab_id_main">';
		else
			echo '<div style="display:none" class="a_tab a_tab_id_'.$tabId.'">';
			
			echo '<div class="a_tab_title_cont">';
				echo '<p  class="a_tab_title">'.self::$arTabs[$tabId].'</p>';	
			echo '</div>';		
	}
	
	static function endTabCont()
	{	
		echo '</div>';		
	}	
	
	static function youDontHaveRight($msg)
	{	
		echo "<div class='dont_have_right_cont'>$msg</div>";		
	}		
}
?>