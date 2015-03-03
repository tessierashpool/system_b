<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('videos.CVideos');
CMain::getLangFile();
global $USER;
if($USER->hr('read_deleted_video_gallery')):
//=========================Поля===========================
$arSettings['FILTER'] = array(
	"id" => array(
		"TITLE" => CMain::getM('T_ID'),
		"USE_ORDER" => true,
		"TYPE" => "number"
	),
	"name" => array(
		"TITLE" => CMain::getM('T_NAME'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	)
);
//=============================Фильтр==================================
$arSettings['FILTER'] = array(
	"id" => array(
		"TITLE" => CMain::getM('T_ID'),
		"USE_ORDER" => true,
		"TYPE" => "number"
	),
	"name" => array(
		"TITLE" => CMain::getM('T_NAME'),
		"USE_ORDER" => true,
		"TYPE" => "text"
	),	
	"type".$site_lang => array(
		"TITLE" => CMain::getM('T_TYPE'),
		"USE_ORDER" => true,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","jpg","gif","png"),
			"TITLE"=>array("","jpg","gif","png")
		)
	),
	"animated".$site_lang => array(
		"TITLE" => CMain::getM('T_ANIMATED'),
		"USE_ORDER" => true,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","Y","N"),
			"TITLE"=>array("",CMain::getM('T_LIST_YES'),CMain::getM('T_LIST_NO'))
		)
	),
	"activated" => array(
		"TITLE" => CMain::getM('T_ACTIVATED'),
		"USE_ORDER" => false,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","Y","N"),
			"TITLE"=>array("",CMain::getM('T_LIST_YES'),CMain::getM('T_LIST_NO'))
		)
	),
	"active" => array(
		"TITLE" => CMain::getM('T_ACTIVE'),
		"USE_ORDER" => true,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","Y","N"),
			"TITLE"=>array("",CMain::getM('T_LIST_YES'),CMain::getM('T_LIST_NO'))
		)
	),
	"deleted" => array(
		"TITLE" => CMain::getM('T_DELETED'),
		"USE_ORDER" => true,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","Y","N"),
			"TITLE"=>array("",CMain::getM('T_LIST_YES'),CMain::getM('T_LIST_NO'))
		)
	),		
	"date_insert" => array(
		"TITLE" => CMain::getM('T_DATE_INSERT'),
		"USE_ORDER" => true,
		"TYPE" => "date"
	),
	"DEFAULT" => array(
		0=>array(
			"NAME" => "deleted",
			"OPERATOR" => "=",
			"VALUE" => "Y"
		)
	)		
);

//==========Глобадьное меню=================
if($USER->hr('delete_video'))
	$arSettings['MENU_SETTINGS']['GLOBAL'][] = array(
			"TITLE" => CMain::getM('G_DELETE'),
			"ACTION" => "delete",
			"FUNCTION_ON_SELECT"=>"",
			"REQUEST_CONFIRM" => true,
			"CONFIRM_TEXT" => CMain::getM('G_D_CONFIRMATION')		
	);
if($USER->hr('restore_video_wink'))
	$arSettings['MENU_SETTINGS']['GLOBAL'][] = array(
			"TITLE" => CMain::getM('G_RESTORE'),
			"ACTION" => "restore",
			"FUNCTION_ON_SELECT"=>"",
			"REQUEST_CONFIRM" => true,
			"CONFIRM_TEXT" => CMain::getM('G_D_RESTORE')		
	);
//==========Получение данных галереи========
$resGal = CGallery::getById($_REQUEST['id']);
$arGallery = mysql_fetch_array($resGal);
//==========Обработка запросов==============
if(isset($_REQUEST['g_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&count($_REQUEST['g_target'])>0)
	{
		if(!$USER->hr('delete_video'))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_DELETE_VIDEO_RIGHTS'));
		}		
		if(!CAdminPage::haveErrors())
		{		
			foreach($_REQUEST['g_target'] as $key => $element)
			{
				CVideos::delete($key);
			}	
			//redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}	
	elseif($_REQUEST['action']=='restore'&&count($_REQUEST['g_target'])>0)
	{
		if(!$USER->hr('restore_video_wink'))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_RESTORE_VIDEO_RIGHTS'));
		}		
		if(!CAdminPage::haveErrors())
		{		
			foreach($_REQUEST['g_target'] as $key => $element)
			{
				CVideos::restoreWinkWink($key);
			}	
			//redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}		
}
//================================================================Визуальная часть страницы=========================================================
?>
<?CAdminPage::setPageTitleIcon('clear_video_gallery.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::showVideoGallery($arSettings, CVideos::getTableName());?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>