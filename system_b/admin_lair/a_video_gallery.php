<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('videos.CVideos');
CMain::includeClass('videos.CVGallery');
CMain::getLangFile();
global $USER;
if($USER->hr('read_video_gallery')&&$USER->hvgr(intval($_REQUEST['id']))):
if(isset($_REQUEST['success_msg'])&&$_REQUEST['success_msg']!='')
{
	CAdminPage::successMsg($_REQUEST['success_msg']);
}
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
if(isset($_REQUEST['id']))
	$gallery_id=intval($_REQUEST['id']);
else
	$gallery_id=0;	
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
	"active" => array(
		"TITLE" => CMain::getM('T_ACTIVE'),
		"USE_ORDER" => true,
		"TYPE" =>"list",
		"LIST"=>array(
			"VALUE"=>array("","Y","N","M"),
			"TITLE"=>array("",CMain::getM('T_LIST_YES'),CMain::getM('T_LIST_NO'),CMain::getM('T_LIST_MODER'))
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
			"VALUE" => "N"
		),
		1=>array(
			"NAME" => "gallery_id",
			"OPERATOR" => "=",
			"VALUE" => $gallery_id
		)		
	)		
);
//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'l',
	'value'=>CMain::getM('BTN_SAVE'),
	'link'=>"a_add_video_to_gallery.php?id=".$_REQUEST['id']."&back_url=".rawurlencode(currentPageWithParams(array(),array('success_msg','action','e_target','g_target'))),
	'class'=>'a_button_fancy'	
);
CAdminPage::adminButtons($arAdminButtons);
//==========Глобадьное меню=================
if($USER->hr('activate_video'))
	$arSettings['MENU_SETTINGS']['GLOBAL'][] = array(
			"TITLE" => CMain::getM('G_ACTIVE'),
			"ACTION" => "active",
			"FUNCTION_ON_SELECT"=>""	
	);
if($USER->hr('deactivate_video'))
	$arSettings['MENU_SETTINGS']['GLOBAL'][] = array(
			"TITLE" => CMain::getM('G_DEACTIVE'),
			"ACTION" => "deactive",
			"FUNCTION_ON_SELECT"=>""	
	);
if($USER->hr('delete_wink_video'))
	$arSettings['MENU_SETTINGS']['GLOBAL'][] = array(
			"TITLE" => CMain::getM('G_DELETE'),
			"ACTION" => "delete",
			"FUNCTION_ON_SELECT"=>"",
			"REQUEST_CONFIRM" => true,
			"CONFIRM_TEXT" => CMain::getM('G_D_CONFIRMATION')		
	);
//==========Получение данных галереи========
$resGal = CVGallery::getById($_REQUEST['id']);
$arGallery = mysql_fetch_array($resGal);
//==========Обработка запросов==============
if(isset($_REQUEST['g_target'])&&isset($_REQUEST['action']))
{
	if($_REQUEST['action']=='delete'&&count($_REQUEST['g_target'])>0)
	{
		if(!$USER->hr('delete_wink_video'))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_DELETE_WINK_VIDEO'));
		}		
		if(!CAdminPage::haveErrors())
		{
			foreach($_REQUEST['g_target'] as $key => $element)
			{
				CVideos::deleteWinkWink($key);
			}	
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}
	if($_REQUEST['action']=='active'&&count($_REQUEST['g_target'])>0)
	{
		if(!$USER->hr('activate_video'))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_ACTIVE_VIDEO'));
		}		
		if(!CAdminPage::haveErrors())
		{	
			foreach($_REQUEST['g_target'] as $key => $element)
			{
				CVideos::activate($key);
			}	
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}
	if($_REQUEST['action']=='deactive'&&count($_REQUEST['g_target'])>0)
	{
		if(!$USER->hr('deactivate_video'))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_DEACTIVE_VIDEO'));
		}		
		if(!CAdminPage::haveErrors())
		{	
			foreach($_REQUEST['g_target'] as $key => $element)
			{
				CVideos::activate($key,'N');
			}	
			redirect(currentPageWithParams(array(),array('success_msg','action','e_target','g_target')));
		}
	}		
}
//================================================================Визуальная часть страницы=========================================================
?>
<?CAdminPage::setPageTitleIcon('video_gallery.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE')."\"".$arGallery['name']."\"",CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::showVideoGallery($arSettings, CVideos::getTableName());?>
<?else:?>
	<?if(!$USER->hr('read_video_gallery')):?>
		<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
	<?else:?>
		<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT_TO_GALLERY'))?>
	<?endif;?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>