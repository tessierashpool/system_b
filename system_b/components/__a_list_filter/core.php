<?
CMain::includeClass('admin.CAdminPage');
$arFields = $arParams['FIELDS_ARRAY'];
//Filter check and create Session with filter values
if(isset($_REQUEST['filter_go'])&&(count($_REQUEST['FILTER'])>0))
{
	foreach($_REQUEST['FILTER'] as $key=>$f_value)
	{

		if($arFields[$key]['TYPE']=='date')
		{
			$_SESSION['filter'][currentPage()][$key]['from'] = $f_value['from'];
			$_SESSION['filter'][currentPage()][$key]['to'] = $f_value['to'];
		}
		else
			$_SESSION['filter'][currentPage()][$key] = $f_value;			
	}	
}
//Clear filter(unset Session)
if(isset($_REQUEST['filter_clear']))
{
	unset($_SESSION['filter'][currentPage()]);
		
}
//If Filter exist, put values to the array to filter the list
$filter_val = array();
$filter = array();
$filter_active = false;
if(isset($_SESSION['filter'][currentPage()]))
{
	$arData['FILTER_VAL'] = $filter_val = $_SESSION['filter'][currentPage()];

	foreach($filter_val as $name=>$value)
	{
		$name = safeStr($name);
		if(!isset($arFields[$name]['JOIN']))
		{
			if($arFields[$name]['TYPE']=='date')
			{
				if($value['from']!='')
				{
					$filter[]=array(
					"NAME"=>$name,
					"OPERATOR"=>'>=',
					"VALUE"=>strtotime($value['from'])
					);	
				}
				if($value['to']!='')
				{
					$filter[]=array(
					"NAME"=>$name,
					"OPERATOR"=>'<=',
					"VALUE"=>strtotime($value['to'])
					);
				}
			}
			elseif($arFields[$name]['TYPE']=='number')
			{
				if($value!='')
				{		
					$filter[]=array(
					"NAME"=>$name,
					"OPERATOR"=>'=',
					"VALUE"=>intval($value)
					);
				}
			}		
			else
			{
				if($value!='')
				{	
					$filter[]=array(
					"NAME"=>$name,
					"OPERATOR"=>'LIKE',
					"VALUE"=>'%'.safeStr($value).'%'
					);
				}
			}
		}
		else
		{
			if($arFields[$name]['TYPE']=='number')
			{
				if($value!='')
				{		
					$filter['JOIN'][$name]=array(
						"TABLE_NAME" => $arFields[$name]['JOIN']['TABLE_NAME'],
						"TABLE_COLUMN" => $arFields[$name]['JOIN']['TABLE_COLUMN'],
						"TABLE_WHERE" => array(
							0=>array(
								"NAME"=>$name,
								"OPERATOR"=>'=',
								"VALUE"=>intval($value)							
							)
						)
					);
				}
			}		
			else
			{
				if($value!='')
				{	
					$filter['JOIN'][$name]=array(
						"TABLE_NAME" => $arFields[$name]['JOIN']['TABLE_NAME'],
						"TABLE_COLUMN" => $arFields[$name]['JOIN']['TABLE_COLUMN'],
						"TABLE_WHERE" => array(
							0=>array(
								"NAME"=>$name,
								"OPERATOR"=>'LIKE',
								"VALUE"=>'%'.safeStr($value).'%'					
							)
						)
					);				
				}
			}		
		}
	}
	$filter_active = true;
}
if(isset($filter,$arParams['FIELDS_ARRAY']['DEFAULT']))
	$filter = array_merge($filter,$arParams['FIELDS_ARRAY']['DEFAULT']);
$arData['FILTER_ACTIVE'] = $filter_active;
CAdminPage::setFilter($filter);
unset($arParams['FIELDS_ARRAY']['DEFAULT']);
require('/template/'.$TEMPLATE_NAME.'/index.php');
?>