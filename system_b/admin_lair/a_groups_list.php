<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('groups.CGroups');
CMain::getLangFile();
global $USER;
if($USER->hr('read_groups_list')):

$site_lang = CMain::getSiteLang();
$arSettings['FIELDS'] = array(
	"id" => array(
		"TITLE" => CMain::getM('T_ID'),
		"USE_ORDER" => true,
		"TYPE" => "number"
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
	"active" => array(
		"TITLE" => CMain::getM('T_ACTIVE'),
		"USE_ORDER" => false,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","Y","N"),
			"TITLE"=>array("",CMain::getM('T_LIST_YES'),CMain::getM('T_LIST_NO'))
		)
	),
	"type" => array(
		"TITLE" => CMain::getM('T_TYPE'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),	
	"rang" => array(
		"TITLE" => CMain::getM('T_RANG'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	)		
);
$arSettings['FILTER'] = array(
	"id" => array(
		"TITLE" => CMain::getM('T_ID'),
		"USE_ORDER" => true,
		"TYPE" => "number"
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
	"active" => array(
		"TITLE" => CMain::getM('T_ACTIVE'),
		"USE_ORDER" => false,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","Y","N"),
			"TITLE"=>array("",CMain::getM('T_LIST_YES'),CMain::getM('T_LIST_NO'))
		)
	),
	"type" => array(
		"TITLE" => CMain::getM('T_TYPE'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),	
);
//===================================Настройка меню элементов=================================================
if($USER->hr('group_edit'))
	$arSettings['MENU_SETTINGS']['ELEMENT'][] = array(
			"TITLE" => CMain::getM('M_T_EDIT'),
			"ACTION" => "edit",
			"FUNCTION"=>"goToEditPage"
	);
	
if($USER->hr('group_delete'))
	$arSettings['MENU_SETTINGS']['ELEMENT'][] = array(
		"TITLE" => CMain::getM('M_T_DELETE'),
		"ACTION" => "delete",
		"TYPE"=>"link",
		"REQUEST_CONFIRM" => true,
		"CONFIRM_TEXT" => CMain::getM('E_D_CONFIRMATION'),
		"ONLY_IF"=> array("FIELD"=>"type","OPERATOR"=>"!=", "VALUE"=>"s")
	);	
if($USER->hr('group_delete'))
	$arSettings['MENU_SETTINGS']['GLOBAL'] = array(
		"0"=>array(
			"TITLE" => CMain::getM('G_M_T_DELETE'),
			"ACTION" => "delete",
			"FUNCTION_ON_SELECT"=>"",
			"REQUEST_CONFIRM" => true,
			"CONFIRM_TEXT" => CMain::getM('G_D_CONFIRMATION')		
		)	
	);
if($USER->hr('group_edit'))
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
	'value'=>CMain::getM('T_BTN_ADD_GROUP'),
	'link'=>'./a_add_group.php?back_url='.rawurlencode(currentPageWithParams(array(),array('success_msg','action','e_target','g_target'))),
	'class'=>'a_button_fancy'	
);
CAdminPage::adminButtons($arAdminButtons);
//Actions handler 
if(isset($_REQUEST['e_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&$_REQUEST['e_target']!='')
	{
		if(!$USER->hr('group_delete'))
		{
			CAdminPage::errorMsg(CMain::getM('NO_PERMITS_DELETE_GROUP'));
		}
		
		if(CGroups::isSystem($_REQUEST['e_target']))
		{	
			CAdminPage::errorMsg(CMain::getM('ERROR_SYSTEM_DELETE'));
		}	

		if(CGroups::getRang($_REQUEST['e_target'])>=$USER->getRang())
		{	
			CAdminPage::errorMsg(CMain::getM('TOO_LOW_RANG'));
		}	
		
		if(!CAdminPage::haveErrors())
		{
			CGroups::delete($_REQUEST['e_target']);
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));		
		}
	}

}

if(isset($_REQUEST['g_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&count($_REQUEST['g_target'])>0)
	{
		if(!$USER->hr('group_delete'))
		{
			CAdminPage::errorMsg(CMain::getM('NO_PERMITS_DELETE_GROUP'));
		}
		
		if(!CAdminPage::haveErrors())
		{
			foreach($_REQUEST['g_target'] as $key => $element)
			{
				if(!CGroups::isSystem($key))
					CGroups::delete($key);
			}	
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}
}
?>
<script type="text/javascript">
	function goToEditPage(e,target)
	{	
		window.location.href = './a_group_edit.php?id='+target+'&back_url=<?=rawurlencode(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));?>';
	}	
	function menuGlobalDelete(e,target)
	{	
		$('.g_menu_action_btn').remove();
	}
</script>
<?CAdminPage::setPageTitleIcon('groups.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'));?>
<?CAdminPage::showList($arSettings, CGroups::getTableName());?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>