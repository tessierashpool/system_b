<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('rights.CRights');
CMain::getLangFile();
global $USER;
if($USER->hr('read_rights_list')):

$catResult = CRights::getCategoriesList();
$site_lang = CMain::getSiteLang();
$dl_r_categoies_value = array();
$dl_r_categoies_title = array();
$dl_r_categoies_value[] = '';
$dl_r_categoies_title[] = '';
while($arCat = mysql_fetch_array($catResult))
{
	$dl_r_categoies_value[] = $arCat['id'];
	$dl_r_categoies_title[] = $arCat['name_'.$site_lang];
}
$arSettings['FIELDS'] = array(
	"id" => array(
		"TITLE" => CMain::getM('T_ID'),
		"USE_ORDER" => true,
		"TYPE" => "number",
		"NOFILTER"=>true
	),
	"code" => array(
		"TITLE" => CMain::getM('T_CODE'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),	
	"name_".$site_lang => array(
		"TITLE" => CMain::getM('T_NAME'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),
	"description_".$site_lang => array(
		"TITLE" => CMain::getM('T_DESCRIPTION'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),
	"category" => array(
		"TITLE" => CMain::getM('T_CATEGORY'),
		"USE_ORDER" => true,
		"TYPE" => "list",
		"LIST"=>array(
			"VALUE"=>$dl_r_categoies_value,
			"TITLE"=>$dl_r_categoies_title
		)		
	),	
	"type" => array(
		"TITLE" => CMain::getM('T_TYPE'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	)	
);
$arSettings['FILTER'] = array(
	"id" => array(
		"TITLE" => CMain::getM('T_ID'),
		"USE_ORDER" => true,
		"TYPE" => "number",
		"NOFILTER"=>true
	),
	"code" => array(
		"TITLE" => CMain::getM('T_CODE'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),	
	"name_".$site_lang => array(
		"TITLE" => CMain::getM('T_NAME'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),
	"description_".$site_lang => array(
		"TITLE" => CMain::getM('T_DESCRIPTION'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),
	"category" => array(
		"TITLE" => CMain::getM('T_CATEGORY'),
		"USE_ORDER" => true,
		"TYPE" => "list",
		"LIST"=>array(
			"VALUE"=>$dl_r_categoies_value,
			"TITLE"=>$dl_r_categoies_title
		)		
	),	
	"type" => array(
		"TITLE" => CMain::getM('T_TYPE'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	)	
);
//====================================================Настройка меню элементов================================================================
if($USER->hr('right_edit'))
	$arSettings['MENU_SETTINGS']['ELEMENT'][] = array(
			"TITLE" => CMain::getM('M_T_EDIT'),
			"ACTION" => "edit",
			"FUNCTION"=>"goToEditPage"
	);
	
if($USER->hr('right_delete'))
	$arSettings['MENU_SETTINGS']['ELEMENT'][] = array(
			"TITLE" => CMain::getM('M_T_DELETE'),
			"ACTION" => "delete",
			"TYPE"=>"link",
			"REQUEST_CONFIRM" => true,
			"CONFIRM_TEXT" => CMain::getM('E_D_CONFIRMATION'),
			"ONLY_IF"=> array("FIELD"=>"type","OPERATOR"=>"!=", "VALUE"=>"s")
	);
if($USER->hr('right_delete'))	
	$arSettings['MENU_SETTINGS']['GLOBAL'] = array(
		"0"=>array(
			"TITLE" => CMain::getM('G_M_T_DELETE'),
			"ACTION" => "delete",
			"FUNCTION_ON_SELECT"=>"",
			"REQUEST_CONFIRM" => true,
			"CONFIRM_TEXT" => CMain::getM('G_D_CONFIRMATION')		
		)	
	);
if($USER->hr('right_edit'))
	$arSettings['MENU_SETTINGS']['DBL_CLICK'] = 'goToEditPage';
CAdminPage::setElementsOnPageForSelect(array(10,20,50,100,200));
//Check msg from UserAdd page
if(isset($_REQUEST['success_msg'])&&$_REQUEST['success_msg']!='')
{
	CAdminPage::successMsg($_REQUEST['success_msg']);
}
//Buttons 
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'l',
	'value'=>CMain::getM('T_BTN_ADD_RIGHT'),
	'link'=>'./a_add_new_right.php?back_url='.rawurlencode(currentPageWithParams(array(),array('success_msg','action','e_target','g_target'))),
	'class'=>'a_button_fancy'	
);
CAdminPage::adminButtons($arAdminButtons);
//Actions handler 
if(isset($_REQUEST['e_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&$_REQUEST['e_target']!='')
	{
		if(CRights::isSystem($_REQUEST['e_target']))
		{	
			CAdminPage::errorMsg(CMain::getM('ERROR_SYSTEM_DELETE'));
		}
		
		if(!$USER->hr('right_delete'))
		{	
			CAdminPage::errorMsg(CMain::getM('ERROR_DR_TO_DELETE'));
		}
		
		if(!CAdminPage::haveErrors())
		{	
			CRights::delete($_REQUEST['e_target']);
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));		
		}		
	}

}

if(isset($_REQUEST['g_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&count($_REQUEST['g_target'])>0)
	{
	
		if(!$USER->hr('right_delete'))
		{	
			CAdminPage::errorMsg(CMain::getM('ERROR_DR_TO_DELETE'));
		}
		
		if(!CAdminPage::haveErrors())
		{		
			foreach($_REQUEST['g_target'] as $key => $element)
			{
				if(!CRights::isSystem($key))
					CRights::delete($key);
			}	
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}
}
?>
<script type="text/javascript">
	function goToEditPage(e,target)
	{	
		window.location.href = './a_right_edit.php?id='+target+'&back_url=<?=rawurlencode(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));?>';
	}	
	function menuGlobalDelete(e,target)
	{	
		alert('222222s');
	}
</script>
<?CAdminPage::setPageTitleIcon('rights.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::showList($arSettings, CRights::getTableName());?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>