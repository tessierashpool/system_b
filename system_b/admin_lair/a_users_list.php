<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('groups.CGroups');
CMain::getLangFile();
global $USER;
if($USER->hr('read_users_list')):

$site_lang = CMain::getSiteLang();
$resGroups = CGroups::getList();
$groupsVal = array();
$groupsTitle = array();
$groupsVal[] = '';
$groupsTitle[] = '';
while($fetchGroups = mysql_fetch_array($resGroups))
{
	$groupsVal[] = $fetchGroups['id'];
	$groupsTitle[] =$fetchGroups['name_'.$site_lang];
}

$arSettings['FIELDS'] = array(
	"id" => array(
		"TITLE" => CMain::getM('T_ID'),
		"USE_ORDER" => true,
		"TYPE" => "number"
	),
	"login" => array(
		"TITLE" => CMain::getM('T_LOGIN'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),	
	"email" => array(
		"TITLE" => CMain::getM('T_EMAIL'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),
	"date_create" => array(
		"TITLE" => CMain::getM('T_DATE_CREATE'),
		"USE_ORDER" => true,
		"TYPE" => "date"
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
	"login" => array(
		"TITLE" => CMain::getM('T_LOGIN'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),	
	"email" => array(
		"TITLE" => CMain::getM('T_EMAIL'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),
	"date_create" => array(
		"TITLE" => CMain::getM('T_DATE_CREATE'),
		"USE_ORDER" => true,
		"TYPE" => "date"
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
	"group_id" => array(
		"TITLE" => CMain::getM('T_GROUPS'),
		"JOIN" => array(
			"TABLE_NAME"=>CUsersMain::getUsersInGroupTableName(),
			"TABLE_COLUMN"=> "user_id"
		),
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>$groupsVal,
			"TITLE"=>$groupsTitle
		)
	)	
);
//=======================================================Настройка меню=============================================================
if($USER->hr('user_edit'))
	$arSettings['MENU_SETTINGS']['ELEMENT'][] = array(
			"TITLE" => CMain::getM('M_T_EDIT'),
			"ACTION" => "edit",
			"FUNCTION"=>"goToEditPage"
	);	
	
if($USER->hr('user_delete'))
	$arSettings['MENU_SETTINGS']['ELEMENT'][] = array(
		"TITLE" => CMain::getM('M_T_DELETE'),
		"ACTION" => "delete",
		"TYPE"=>"link",
		"REQUEST_CONFIRM" => true,
		"CONFIRM_TEXT" => CMain::getM('E_D_CONFIRMATION')
	);	

if($USER->hr('user_delete'))
	$arSettings['MENU_SETTINGS']['GLOBAL'] = array(
		"0"=>array(
			"TITLE" => CMain::getM('G_M_T_DELETE'),
			"ACTION" => "delete",
			"FUNCTION_ON_SELECT"=>"",
			"REQUEST_CONFIRM" => true,
			"CONFIRM_TEXT" => CMain::getM('G_D_CONFIRMATION')		
		)	
	);
	
if($USER->hr('user_edit'))	
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
	'value'=>CMain::getM('T_BTN_ADD_USER'),
	'link'=>'./a_add_user.php?back_url='.rawurlencode(currentPageWithParams(array(),array('success_msg','action','e_target','g_target'))),
	'class'=>'a_button_fancy'	
);
CAdminPage::adminButtons($arAdminButtons);
//Actions handler 
if(isset($_REQUEST['e_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&$_REQUEST['e_target']!='')
	{
		if(!$USER->hr('user_delete'))
		{
			CAdminPage::errorMsg(CMain::getM('NO_PERMITS_DELETE_USER'));
		}

		if(CUsersMain::isOmnipotent($_REQUEST['e_target']))
		{
			CAdminPage::errorMsg(CMain::getM('NO_PERMITS_DELETE_USER_O'));
		}
	
		if($USER->getId()==$_REQUEST['e_target'])
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_CANT_SELF_DELETE'));
		}
		
		if(!CAdminPage::haveErrors())
		{		
			CUsersMain::deleteUserById($_REQUEST['e_target']);
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}
}

if(isset($_REQUEST['g_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&count($_REQUEST['g_target'])>0)
	{
		if(!$USER->hr('user_delete'))
		{
			CAdminPage::errorMsg(CMain::getM('NO_PERMITS_DELETE_USER'));
		}
		
		if(!CAdminPage::haveErrors())
		{		
			foreach($_REQUEST['g_target'] as $key => $element)
				CUsersMain::deleteUserById($key);
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}
}
?>
<script type="text/javascript">
	function goToEditPage(e,target)
	{	
		window.location.href = './a_user_edit.php?id='+target+'&back_url=<?=rawurlencode(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));?>';
	}	
</script>
<?CAdminPage::setPageTitleIcon('users.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::showList($arSettings, CUsersMain::getTableName());?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>