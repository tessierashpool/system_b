<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('groups.CGroups');
CMain::includeClass('rights.CRights');
CMain::includeClass('images.CGallery');
CMain::includeClass('videos.CVGallery');
CMain::getLangFile();
global $USER;
if($USER->hr('group_edit')):

if(CGroups::getRang($_REQUEST['id'])>=$USER->getRang())
	CAdminPage::errorMsg(CMain::getM('RANG_TOO_LOW'));
//Проверка данных и обновление группы
$data['s'] = CSettings::getSettings();
if(isset($_REQUEST['a_save_right_btn'])||isset($_REQUEST['a_save_apply_btn']))
{
	
	$name_en = safeStr($_REQUEST['name_en']);
	if($name_en=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_EN_EMPTY'));
	}	

	$name_ru = safeStr($_REQUEST['name_ru']);
	if($name_ru=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_RU_EMPTY'));
	}		

	$rang = intval($_REQUEST['rang']);
	if(1>$rang||999<$rang)
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_RANG_OUT_RANGE'));
	}	
	
	if($rang>=$USER->getRang())
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_RANG_TOO_LOW')." (".$USER->getRang().")");
	}
	
	if(!CAdminPage::haveErrors())
	{
		$groupId = $_REQUEST['id'];
		$arFields['name_en'] = $name_en;
		$arFields['description_en'] = $_REQUEST['description_en'];
		$arFields['name_ru'] = $name_ru;
		$arFields['description_ru'] = $_REQUEST['description_ru'];
		$arFields['active'] = $_REQUEST['active'];
		$arFields['rang'] = $rang;
		if(CGroups::update($arFields,$groupId))
		{
			
			if($groupId!=0)
			{	
				CGroups::deleteRight($groupId);
				if(isset($_REQUEST['rights']))
				{
					foreach($_REQUEST['rights'] as $rightId)
					{	
						if(!CGroups::addRight($groupId,$rightId))
							CAdminPage::errorMsg(CMain::getM('ERROR_RIGHT_ADD').$rightId);
					}
				}	
				
				CGallery::deleteGroupRight($groupId);								
				if(isset($_REQUEST['gallery_right'])&&(count($_REQUEST['gallery_right'])>0))
				{
					foreach($_REQUEST['gallery_right'] as $gallery_id)
					{	
						if(!CGallery::addGroupRight($groupId,$gallery_id))
							CAdminPage::errorMsg(CMain::getM('ERROR_GALLERY_RIGHT_ADD').$gallery_id);
					}		
				}	
				
				CVGallery::deleteGroupRight($groupId);								
				if(isset($_REQUEST['video_gallery_right'])&&(count($_REQUEST['video_gallery_right'])>0))
				{
					foreach($_REQUEST['video_gallery_right'] as $gallery_id)
					{	
						if(!CVGallery::addGroupRight($groupId,$gallery_id))
							CAdminPage::errorMsg(CMain::getM('ERROR_GALLERY_RIGHT_ADD').$gallery_id);
					}		
				}					
			}
			CAdminPage::successMsg(CMain::getM('GROUP_UPDATED_1').$_REQUEST['code']." [".$groupId."]".CMain::getM('GROUP_UPDATED_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!=''&&!isset($_REQUEST['a_save_apply_btn']))
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('GROUP_UPDATED_1').$_REQUEST['code']." [".$groupId."]".CMain::getM('GROUP_UPDATED_2'))));
		}
	}
}
//=======================================Получение данных====================================================
$resGroup = CGroups::getById($_REQUEST['id']);
$arGroupVal = mysql_fetch_array($resGroup);
//Список прав данной группы
$resRights = CGroups::getRightsList($_REQUEST['id']);
while($arRightsTemp = mysql_fetch_array($resRights))
{
	$arRights[$arRightsTemp['right_id']] = $arRightsTemp['right_id'];
}
//Список категорий прав
$catResult = CRights::getCategoriesList();
$site_lang = CMain::getSiteLang();
while($arCat = mysql_fetch_array($catResult))
{
	$arRightCategories[$arCat['id']]['name'] = $arCat['name_'.$site_lang];
	$arRightCategories[$arCat['id']]['code'] = $arCat['code'];
}
//Список прав в общем
$rihgtsListResult = CRights::getList();
while($arRight = mysql_fetch_array($rihgtsListResult, MYSQL_ASSOC))
{
	$arRightsList[$arRight['category']][] = $arRight;
}
//Список галерей в общем
$qGalleryList = CGallery::getList();
$arGallerys = array();
while($arGallery = mysql_fetch_array($qGalleryList))
	$arGallerys[]  = $arGallery;
//Спискок галерей к которым данная группа имеет доступ
$qGroupRights = CGallery::getGroupRights($_REQUEST['id']);
$arGroupRights = array();
while($arGroupRight = mysql_fetch_array($qGroupRights))
	$arGroupRights[]  = $arGroupRight['gallery_id'];
	
//Список видео галерей в общем
$qVGalleryList = CVGallery::getList();
$arVideoGallerys = array();
while($arVGallery = mysql_fetch_array($qVGalleryList))
	$arVideoGallerys[]  = $arVGallery;
//Спискок видео галерей к которым данная группа имеет доступ
$qVGroupRights = CVGallery::getGroupRights($_REQUEST['id']);
$arVGroupRights = array();
while($arVGroupRight = mysql_fetch_array($qVGroupRights))
	$arVGroupRights[]  = $arVGroupRight['gallery_id'];
		
//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'s',
	'value'=>CMain::getM('BTN_SAVE'),
	'name'=>'a_save_right_btn',
	'class'=>'a_button_fancy'	
);
if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
{
	$arAdminButtons[] = array(
		'type'=>'s',
		'value'=>CMain::getM('BTN_APPLY'),
		'link'=>$_REQUEST['back_url'],
		'name'=>'a_save_apply_btn',
		'class'=>'a_button_custom'	
	);
	$arAdminButtons[] = array(
		'type'=>'l',
		'value'=>CMain::getM('BTN_CANCEL'),
		'link'=>$_REQUEST['back_url'],
		'class'=>'a_button_custom'	
	);
}
CAdminPage::adminButtons($arAdminButtons);
//Начало формирования вкладок
$arTabs = array(
	'main'=>CMain::getM('TABS_MAIN'),
	'rights'=>CMain::getM('TABS_RIGHTS'),
);	
CAdminPage::setTabs($arTabs);

//Подготовка данных
if(isset($_REQUEST['rights']))
	$arCheckedElements = $_REQUEST['rights'];
else
	$arCheckedElements = $arRights;

$arFieldsDefaultVal = $arGroupVal;	
if($_REQUEST['name_en']!='')
	$arFieldsDefaultVal['name_en'] = $_REQUEST['name_en'];
if($_REQUEST['description_en']!='')
	$arFieldsDefaultVal['description_en'] = $_REQUEST['description_en'];
if($_REQUEST['name_ru']!='')
	$arFieldsDefaultVal['name_ru'] = $_REQUEST['name_ru'];
if($_REQUEST['description_ru']!='')
	$arFieldsDefaultVal['description_ru'] = $_REQUEST['description_ru'];	
if($_REQUEST['active']!='')
	$arFieldsDefaultVal['active'] = $_REQUEST['active'];	
if($_REQUEST['rang']!='')
	$arFieldsDefaultVal['rang'] = $_REQUEST['rang'];		

//================================================================Визуальная часть страницы=========================================================
?>
<script type="text/javascript">
	function checkRight(e){
		var input = $(e).children('div').children('input');
		if(!input.is(':hover'))
		{
			if(input.attr('checked')=='checked')
				input.removeAttr('checked');
			else
				input.attr('checked','checked');
		}
	}
	
	function checkAll(e,categoryId)
	{		
		if($(e).attr('checked')!='checked')
			$('.checkBox_'+categoryId).removeAttr('checked');
		else
			$('.checkBox_'+categoryId).attr('checked','checked');		
	}
</script>
<?CAdminPage::setPageTitleIcon('group_edit.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?
	$drop_list_value[0] = "N";
	$drop_list_value[1] = "Y";
	$drop_list_title[0] = CMain::getM('LIST_NO');
	$drop_list_title[1] = CMain::getM('LIST_YES');	
	
	$drop_list_value_type[0] = "c";
	$drop_list_value_type[1] = "s";
	$drop_list_title_type[0] = CMain::getM('LIST_CUSTOM');
	$drop_list_title_type[1] = CMain::getM('LIST_SYSTEM');		
?>
<?CAdminPage::startPage()?>
<?CAdminPage::getTabsMenu()?>
<?CAdminPage::startTabCont('main',true)?>
		<table  width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<tr>
				<td align="left"><p><?=CMain::getM('T_CODE')?></p></td>
				<td align="left"><input disabled="disabled"  type="text" value="<?=$arFieldsDefaultVal['code']?>" /><input  name="code" type="hidden" value="<?=$arFieldsDefaultVal['code']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_EN')?></p></td>
				<td align="left"><input type="text" name="name_en" value="<?=$arFieldsDefaultVal['name_en']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_EN')?></p></td>
				<td align="left"><input type="text" name="description_en" value="<?=$arFieldsDefaultVal['description_en']?>" /></td>
			</tr>		
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_RU')?></p></td>
				<td align="left"><input type="text" name="name_ru" value="<?=$arFieldsDefaultVal['name_ru']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_RU')?></p></td>
				<td align="left"><input type="text" name="description_ru" value="<?=$arFieldsDefaultVal['description_ru']?>" /></td>
			</tr>	
			<tr>
				<td align="left"><p><?=CMain::getM('T_RANG')?></p></td>
				<td align="left"><input type="text" name="rang" value="<?=$arFieldsDefaultVal['rang']?>" /></td>
			</tr>				
			<tr>
				<td align="left"><p><?=CMain::getM('T_ACTIVEN')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'active',$arFieldsDefaultVal['active'],'','','Y')?></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_TYPE')?></p></td>
				<td align="left"><input disabled=”disabled” type="text" value="<?=$drop_list_title_type[array_search($arFieldsDefaultVal['type'], $drop_list_value_type)]?>" /></td>
			</tr>				
		</table>		
<?CAdminPage::endTabCont()?>	
<?CAdminPage::startTabCont('rights')?>
	<?foreach($arRightsList as $categoryId => $arRight):?>
		<div class="tab_two_col_div">
			<table width="100%"  cellpadding="0" cellspacing="3" border="0">
				<tr >
					<th colspan ="2" align="left" width="50%"><label for="h_checkbox_<?=$categoryId?>"><?=$arRightCategories[$categoryId]['name']?></label><input onchange="checkAll(this,<?=$categoryId?>)" id="h_checkbox_<?=$categoryId?>" type="checkbox" /></th>
				</tr>	
				<?$counter = 1;?>
				<?foreach($arRight as $key => $right):?>
					<?if(($counter+1)%2==0):?>
						<tr>
					<?endif;?>
						<td onclick="checkRight(this);$('#h_checkbox_<?=$categoryId?>').removeAttr('checked');" <?if(!isset($arRight[intval($key)+1])) echo "colspan='2'"?>  align="left" width="50%">
							<div><input <?if(isset($arCheckedElements[$right['id']]))echo "checked='checkecd'"?> name="rights[<?=$right['id']?>]" value="<?=$right['id']?>"  class="checkBox_<?=$categoryId?>" id="h_checkbox_users1" type="checkbox" /></div>
							<div>
								<p><?=$right['name_'.$site_lang]." [".$right['code']."]"?></p>
								<p><?=$right['description_'.$site_lang]?></p>					
							</div>
						</td>
					<?if($counter%2==0):?>
						</tr>	
					<?endif;?>
					<?$counter++;?>
				<?endforeach;?>		
			</table>
		</div>	
		<?if($arRightCategories[$categoryId]['code']=='images'):?>
			<div class="tab_two_col_div">
				<table width="100%"  cellpadding="0" cellspacing="3" border="0">
					<tr >
						<th colspan ="2" align="left" width="50%"><label for="h_checkbox_gallery_rights">Доступ к галереям</label><input onchange="checkAll(this,'gallery_rights')" id="h_checkbox_gallery_rights" type="checkbox" /></th>
					</tr>	
					<?$counter = 1;?>
					<?foreach($arGallerys as $key => $gallery):?>
						<?if(($counter+1)%2==0):?>
							<tr>
						<?endif;?>
							<td onclick="checkRight(this);$('#h_checkbox_gallery_rights').removeAttr('checked');" <?if(!isset($arGallerys[intval($key)+1])) echo "colspan='2'"?>  align="left" width="50%">
								<div><input <?if(in_array($gallery['id'],$arGroupRights)) echo "checked='checkecd'"; ?>  name="gallery_right[<?=$gallery['id']?>]" value="<?=$gallery['id']?>"  class="checkBox_gallery_rights" type="checkbox" /></div>
								<div>
									<p><?=$gallery['name']." [".$gallery['code']."]"?></p>	
									<p></p>
								</div>
							</td>
						<?if($counter%2==0):?>
							</tr>	
						<?endif;?>
						<?$counter++;?>
					<?endforeach;?>		
				</table>
			</div>			
		<?endif;?>
		<?if($arRightCategories[$categoryId]['code']=='videos'):?>
			<div class="tab_two_col_div">
				<table width="100%"  cellpadding="0" cellspacing="3" border="0">
					<tr >
						<th colspan ="2" align="left" width="50%"><label for="h_checkbox_video_rights">Доступ к галереям</label><input onchange="checkAll(this,'video_rights')" id="h_checkbox_video_rights" type="checkbox" /></th>
					</tr>	
					<?$counter = 1;?>
					<?foreach($arVideoGallerys as $key => $vgallery):?>
						<?if(($counter+1)%2==0):?>
							<tr>
						<?endif;?>
							<td onclick="checkRight(this);$('#h_checkbox_video_rights').removeAttr('checked');" <?if(!isset($arGallerys[intval($key)+1])) echo "colspan='2'"?>  align="left" width="50%">
								<div><input <?if(in_array($vgallery['id'],$arVGroupRights)) echo "checked='checkecd'"; ?>  name="video_gallery_right[<?=$vgallery['id']?>]" value="<?=$vgallery['id']?>"  class="checkBox_video_rights" type="checkbox" /></div>
								<div>
									<p><?=$vgallery['name']." [".$vgallery['code']."]"?></p>	
									<p></p>
								</div>
							</td>
						<?if($counter%2==0):?>
							</tr>	
						<?endif;?>
						<?$counter++;?>
					<?endforeach;?>		
				</table>
			</div>			
		<?endif;?>		
	<?endforeach;?>		
	
<?CAdminPage::endTabCont()?>				
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>