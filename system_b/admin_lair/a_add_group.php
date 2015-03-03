<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('groups.CGroups');
CMain::includeClass('rights.CRights');
CMain::getLangFile();
global $USER;
if($USER->hr('new_group_add')):

$catResult = CRights::getCategoriesList();
$site_lang = CMain::getSiteLang();
while($arCat = mysql_fetch_array($catResult))
{
	$arRightCategories[$arCat['id']] = $arCat['name_'.$site_lang];
}
$rihgtsListResult = CRights::getList();
while($arRight = mysql_fetch_array($rihgtsListResult, MYSQL_ASSOC))
{
	$arRightsList[$arRight['category']][] = $arRight;
}
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
//Проверка данных и добавление нового пользователя
$data['s'] = CSettings::getSettings();
if(isset($_REQUEST['a_save_right_btn']))
{
	$code = safeStr($_REQUEST['code']);
	if($code!='')
	{
		if(!CGroups::isValidCode($code)) 
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_INVALID_CODE'));
		}		
		elseif(CGroups::codeExist($code))
		{
			CAdminPage::errorMsg(CMain::getM('ERROR_CODE_EXIST'));
		}
	}		
	else
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_CODE_EMPTY'));
	}
	
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
		$arFields['code'] = $code;
		$arFields['name_en'] = $name_en;
		$arFields['description_en'] = $_REQUEST['description_en'];
		$arFields['name_ru'] = $name_ru;
		$arFields['description_ru'] = $_REQUEST['description_ru'];
		$arFields['active'] = $_REQUEST['active'];
		$arFields['type'] = $_REQUEST['type'];
		$arFields['rang'] = $rang;
		if(CGroups::add($arFields))
		{
			$lastGroupId = CGroups::lastId();
			
			if($lastGroupId!=0)
			{
				if(isset($_REQUEST['rights']))
					foreach($_REQUEST['rights'] as $rightId)
					{	
						if(!CGroups::addRight($lastGroupId,$rightId))
							CAdminPage::errorMsg(CMain::getM('ERROR_RIGHT_ADD').$rightId);
					}
			}
			CAdminPage::successMsg(CMain::getM('GROUP_CREATED_1').$code." [".CGroups::lastId()."]".CMain::getM('GROUP_CREATED_2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('GROUP_CREATED_1').$code .CMain::getM('GROUP_CREATED_2'))));
		}
	}
}
$rang_def = 1;
if(isset($_REQUEST['rang']))
	$rang_def = $_REQUEST['rang'];
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
<?CAdminPage::setPageTitleIcon('group_add.gif');?>
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
				<td align="left"><input type="text" name="code" value="<?=$_REQUEST['code']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_EN')?></p></td>
				<td align="left"><input type="text" name="name_en" value="<?=$_REQUEST['name_en']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_EN')?></p></td>
				<td align="left"><input type="text" name="description_en" value="<?=$_REQUEST['description_en']?>" /></td>
			</tr>		
			<tr>
				<td align="left"><p><?=CMain::getM('T_NAME_RU')?></p></td>
				<td align="left"><input type="text" name="name_ru" value="<?=$_REQUEST['name_ru']?>" /></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_DESCRIPTION_RU')?></p></td>
				<td align="left"><input type="text" name="description_ru" value="<?=$_REQUEST['description_ru']?>" /></td>
			</tr>	
			<tr>
				<td align="left"><p><?=CMain::getM('T_RANG')?></p></td>
				<td align="left"><input type="text" name="rang" value="<?=$rang_def?>" /></td>
			</tr>				
			<tr>
				<td align="left"><p><?=CMain::getM('T_ACTIVEN')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value,$drop_list_title,'active',$_REQUEST['active'],'','','Y')?></td>
			</tr>
			<tr>
				<td align="left"><p><?=CMain::getM('T_TYPE')?></p></td>
				<td align="left"><?CAdminPage::getDropList($drop_list_value_type,$drop_list_title_type,'type',$_REQUEST['type'])?></td>
			</tr>				
		</table>		
<?CAdminPage::endTabCont()?>	
<?CAdminPage::startTabCont('rights')?>
	<?
	$arCheckedElements = $_REQUEST['rights'];
	?>
	<?foreach($arRightsList as $categoryId => $arRight):?>
		<div class="tab_two_col_div">
			<table width="100%"  cellpadding="0" cellspacing="3" border="0">
				<tr >
					<th colspan ="2" align="left" width="50%"><label for="h_checkbox_<?=$categoryId?>"><?=$arRightCategories[$categoryId]?></label><input onchange="checkAll(this,<?=$categoryId?>)" id="h_checkbox_<?=$categoryId?>" type="checkbox" /></th>
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
	<?endforeach;?>		
<?CAdminPage::endTabCont()?>				
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>