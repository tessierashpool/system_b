<?
$filter_active = $arData['FILTER_ACTIVE'];
$filter_val = $arData['FILTER_VAL'];
$arFields = $arParams['FIELDS_ARRAY'];
$arMenuSettings = $arParams['MENU_SETTINGS'];
$table_name = $arParams['TABLE_NAME'];
CMain::includeClass('db.CDateBase');
CMain::includeClass('videos.CVideos');
//====================Массив возможных значений для поля "Запись на странице"===============================
CDateBase::setElementsOnPageForSelect(array(10,20,50,100,200));
?>
<?
$sort = array();
if(isset($_REQUEST['order'])&&isset($_REQUEST['order_d']))
{
	$sort[$_REQUEST['order']]=$_REQUEST['order_d'];
}
$sort = array("date_insert"=>"DESC");
$cells_on_page = $_GET['elements_on_page'];
$page = $_GET['page'];
$limit = array($page,$cells_on_page);	

$video_list = CDateBase::getListFromTable($table_name,CAdminPage::getFilter(),$sort,$limit);?>
<?CDateBase::pageNav('admin_page_nav');?>
<script type="text/javascript">
<?//=========================Подтверждение запроса в глобальном меню=====================================?>
	function confirmAction(text)
	{
		var r = confirm(text);
		if (r == true) {
			return true;
		} else {
			return false;
		}	
		return false;	
	}
</script>
<br />
<div class="a_video_gallery_cont">
	<?while($video_list&&$video_array = mysql_fetch_array($video_list)):?>
		<div class="a_video_info_cont">
			<input class="a_video_check" name="g_target[<?=$video_array['id']?>]" type="checkbox" />
			<h1><?=$video_array['name']?></h1>
			<p> <?=date("d.m.Y H:i:s",$video_array['date_insert']);?></p>
			<?if($video_array['active']=='Y'):?>
				<p><span style="background-color:#7cc931">Активно</span></p>
			<?elseif($video_array['active']=='N'):?>
				<p><span style="background-color:#ff4343">Неактивно</span></p>
			<?else:?>	
				<p><span style="background-color:#eec022">Модерируется</span></p>
			<?endif;?>				
			<p><a href="<?=$video_array['url']?>"><?=$video_array['url']?></a></p>
			<br />
			<div ><?CVideos::getYoutubeVideo($video_array['url_video_id']);?></div>
			<br />
			<div class="a_video_check_marker"><span class="glyphicon glyphicon-ok"></span></div>
		</div>	
	<?endwhile;?>
</div>
<br />
<script type="text/javascript">
	function globalActionChange(e){
		switch(e.selectedIndex) {
			case 0:
				$('.a_list_global_action input').remove();
				break;
			<?
			$num = 1;
			foreach($arMenuSettings['GLOBAL'] as $global_action):			
			?>		
				case <?=$num?>:
					<?if($global_action['FUNCTION_ON_SELECT']!=''):?>
						<?=$global_action['FUNCTION_ON_SELECT']?>();
					<?endif;?>
					
					$('.a_list_global_action input').remove();
					<?if($global_action['REQUEST_CONFIRM']):?>
						var input_submit_btn = '<input onclick="return confirmAction(\'<?=$global_action['CONFIRM_TEXT']?>\')" class="g_menu_action_btn" type="submit"  value="Go" />';
					<?else:?>
						var input_submit_btn = '<input class="g_menu_action_btn" type="submit"  value="Go" />';
					<?endif;?>
					$('.a_list_global_action select').after(input_submit_btn);
				break;
			<?				
			$num++;
			endforeach;			
			?>	
		} 
	}
	function globalAction(action)
	{
		$('.a_list_global_action select').val(action);
		document.getElementById("admin_form").submit();
	}
</script>
<?if(count($arMenuSettings['GLOBAL'])):?>
	<div class="a_list_menu_element_menu" >
		<?foreach($arMenuSettings['GLOBAL'] as $global_action):?>
			<?if($global_action['REQUEST_CONFIRM']):?>
				<div class="a_list_menu_punkt" onclick="if(confirmAction('<?=$global_action['CONFIRM_TEXT']?>')) globalAction('<?=$global_action['ACTION']?>')" ><?=$global_action['TITLE']?></div>
			<?else:?>
				<div class="a_list_menu_punkt" onclick="globalAction('<?=$global_action['ACTION']?>')"><?=$global_action['TITLE']?></div>
			<?endif;?>
		<?endforeach;?>
	</div>
	<div class="a_list_global_action">
		<select name="action" onchange="globalActionChange(this)" id="">
			<option value="">-</option>
			<?foreach($arMenuSettings['GLOBAL'] as $global_action):?>
				<option  value="<?=$global_action['ACTION']?>"><?=$global_action['TITLE']?></option>
			<?endforeach;?>
		</select>
		<div style="clear:both"></div>
	</div><br />
<?endif;?>
<?CDateBase::pageNav('admin_page_nav');?>
