<?
$ADMIN_MENU = array();
CMain::getLangFile($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/lang/'.CMain::getSiteLang().'/menu/menu_generator.php');
$array[0]=array(
	"NAME" => CMain::getM('MAIN'),
	"LINK" => 'index',
	"SUB_MENU" => array(
		"13" => array(
			"NAME"=>"sub_menu_separator"
		),	
		"0" => array(
			"NAME"=>CMain::getM('MAIN_PAGE'),
			"LINK"=>"index.php"
		),
		"1" => array(
			"NAME"=>"Авито",
			"LINK"=>"avito.php"
		),		
		"23" => array(
			"NAME"=>"sub_menu_separator"
		)		
	)
);

if($USER->hr('global_menu_user'))
	$array[2]=array(
		"NAME" => CMain::getM('USERS'),
		"LINK" => 'users',
		"SUB_MENU" => array(
			"13" => array(
				"NAME"=>"sub_menu_separator"
			),		
			"0" => array(
				"NAME"=>CMain::getM('USERS_LIST'),
				"LINK"=>"a_users_list.php",
				"ICON"=>"users_list.png"
			),	
			"1" => array(
				"NAME"=>CMain::getM('ADD_USER'),
				"LINK"=>"a_add_user.php",
				"ICON"=>"add_user_m.png"
			),
			/*"2" => array(
				"NAME"=>CMain::getM('ADD_USER_ADDIT_FIELD'),
				"LINK"=>"a_add_new_user_field.php",
				"ICON"=>"rights_list_add.png"
			),*/
			"3" => array(
				"NAME"=>"sub_menu_separator"
			),
			"4" => array(
				"NAME"=>CMain::getM('GROUPS_LIST'),
				"LINK"=>"a_groups_list.php",
				"ICON"=>"users_menu_2.png"
			),	
			"5" => array(
				"NAME"=>CMain::getM('ADD_GROUP'),
				"LINK"=>"a_add_group.php",
				"ICON"=>"add_group_2.png"
			),
			"6" => array(
				"HIDDEN" => true,
				"LINK"=>"a_user_edit.php"
			),
			"7" => array(
				"NAME"=>"sub_menu_separator"
			),
			"8" => array(
				"NAME"=>CMain::getM('RIGHTS_LIST'),
				"LINK"=>"a_rights_list.php",
				"ICON"=>"rights_list.png"
			),	
			"9" => array(
				"NAME"=>CMain::getM('ADD_RIGHT'),
				"LINK"=>"a_add_new_right.php",
				"ICON"=>"rights_list_add.png"
			),
			"10" => array(
				"HIDDEN" => true,
				"LINK"=>"a_group_edit.php"
			),
			"11" => array(
				"HIDDEN" => true,
				"LINK"=>"a_right_edit.php"
			),
			"17" => array(
				"NAME"=>"sub_menu_separator"
			)
		)
	);

if($USER->hr('global_menu_images'))
{
	$array[3]=array(
		"NAME" => CMain::getM('IMAGES'),
		"LINK" => 'images',
		"SUB_MENU" => array(
			"17" => array(
				"NAME"=>"sub_menu_separator"
			),		
			"0" => array(
				"NAME"=>CMain::getM('GALLERY_LIST'),
				"LINK"=>"a_gallery_list.php",
				"ICON"=>"gallery_list.png"
			)	,
			"1" => array(
				"NAME"=>CMain::getM('GALLERY_ADD'),
				"LINK"=>"a_add_gallery.php",
				"ICON"=>"add_gallery_03.png"
			),
			"2" => array(
				"HIDDEN" => true,
				"LINK"=>"a_edit_gallery.php"
			),
			"3" => array(
				"NAME"=>"sub_menu_separator"
			),
			"4" => array(
				"NAME"=>"list_title",
				"TITLE"=>CMain::getM('T_GALLERIES')
			),
			"5" => array(
				"HIDDEN" => true,
				"LINK"=>"a_gallery.php"
			)	,
			"6" => array(
				"HIDDEN" => true,
				"LINK"=>"a_add_images_to_gallery.php"
			),
			"7" => array(
				"HIDDEN" => true,
				"LINK"=>"a_gallery_clear.php"
			)				
		)
	);
	CMain::includeClass('images.CGallery');
	$resGallery = CGallery::getList();
	while($gFetch = mysql_fetch_array($resGallery))
	{
		$array[3]["SUB_MENU"][]=array(
			"NAME"=>$gFetch['name'],
			"LIST"=>"Y",
			"ID"=>$gFetch['id'],
			"PAGE"=>"a_gallery.php",
			"LINK"=>"a_gallery.php?id=".$gFetch['id']
		);
	}
	$array[3]["SUB_MENU"][]=array(
			"NAME"=>"sub_menu_separator"
		);
	$array[3]["SUB_MENU"][]=array(
			"NAME"=>CMain::getM('GALLERY_DELETED'),
			"LINK"=>"a_gallery_deleted.php"
		);
	$array[3]["SUB_MENU"][]=array(
			"NAME"=>"sub_menu_separator"
		);		
}
	
if($USER->hr('global_menu_videos'))	
{
	$array[4]=array(
		"NAME" => CMain::getM('VIDEO'),
		"LINK" => 'video',
		"SUB_MENU" => array(
			"17" => array(
				"NAME"=>"sub_menu_separator"
			),		
			"0" => array(
				"NAME"=>CMain::getM('VIDEO_GALLERY_LIST'),
				"LINK"=>"a_video_gallery_list.php",
				"ICON"=>"menu_v_gallery.png"
			),
			"1" => array(
				"NAME"=>CMain::getM('VIDEO_GALLERY_ADD'),
				"LINK"=>"a_add_video_gallery.php",
				"ICON"=>"menu_add_v_gallery.png"
			),
			"4" => array(
				"NAME"=>"sub_menu_separator"
			),
			"3" => array(
				"NAME"=>"list_title",
				"TITLE"=>CMain::getM('T_V_GALLERIES')
			),
			"5" => array(
				"HIDDEN" => true,
				"LINK"=>"a_video_gallery.php"
			),
			"6" => array(
				"HIDDEN" => true,
				"LINK"=>"a_add_video_to_gallery.php"
			),
			"7" => array(
				"HIDDEN" => true,
				"LINK"=>"a_edit_video_gallery.php"
			),
			"8" => array(
				"HIDDEN" => true,
				"LINK"=>"a_video_gallery_clear.php"
			)			
		)
	);	
	CMain::includeClass('videos.CVGallery');
	$resGallery = CVGallery::getList();
	while($gFetch = mysql_fetch_array($resGallery))
	{
		$array[4]["SUB_MENU"][]=array(
			"NAME"=>$gFetch['name'],
			"LIST"=>"Y",
			"ID"=>$gFetch['id'],
			"PAGE"=>"a_video_gallery.php",
			"LINK"=>"a_video_gallery.php?id=".$gFetch['id']
		);
	}
	$array[4]["SUB_MENU"][]=array(
			"NAME"=>"sub_menu_separator"
		);
	$array[4]["SUB_MENU"][]=array(
			"NAME"=>CMain::getM('VIDEO_GALLERY_DELETED'),
			"LINK"=>"a_video_gallery_deleted.php"
		);	
	$array[4]["SUB_MENU"][]=array(
			"NAME"=>"sub_menu_separator"
		);		
}	
if($USER->hr('global_menu_settings'))		
	$array[5]=array(
		"NAME" => CMain::getM('SETTINGS'),
		"LINK" => 'settings',
		"SUB_MENU" => array(
			"17" => array(
				"NAME"=>"sub_menu_separator"
			),		
			"0" => array(
				"NAME"=>CMain::getM('MAIN_SETTINGS'),
				"LINK"=>"a_main_settings.php",
				"ICON"=>"settings_m.png"
			),
			"1" => array(
				"NAME"=>CMain::getM('EMAIL_SETTINGS'),
				"LINK"=>"a_email_settings.php",
				"ICON"=>"email_settings.png"
			),	
			"27" => array(
				"NAME"=>"sub_menu_separator"
			)			
		)
	);



$ADMIN_MENU = $array;
?>