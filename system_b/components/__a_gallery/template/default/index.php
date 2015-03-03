<?
$filter_active = $arData['FILTER_ACTIVE'];
$filter_val = $arData['FILTER_VAL'];
$arFields = $arParams['FIELDS_ARRAY'];
$arMenuSettings = $arParams['MENU_SETTINGS'];
$table_name = $arParams['TABLE_NAME'];
CMain::includeClass('db.CDateBase');
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

$img_list = CDateBase::getListFromTable($table_name,CAdminPage::getFilter(),$sort,$limit);?>
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
<?//=========================Выделить все=================================================================?>	
	var allChecked = false;
	function gTargetCheck(e)
	{
		if(allChecked)
		{
			$('.g_target_checkbox').removeAttr('checked');
			allChecked = false;
		}	
		else	
		{
			$('.g_target_checkbox').attr('checked','checked');
			allChecked = true;
		}
	}	
	
	function oneElementTargetCheck()
	{
		$('.g_target_checkbox_prime').removeAttr('checked');
		allChecked = false;
	}
	
</script>
<br />
<div class="a_g_zoom_cont">
	<div class="a_g_zoom_btns_cont">
		<div class="a_zoom_num_cont"><span>4234</span></div>
		<a class="a_g_zoom_btn a_zoom_open_in_wind" target="_blank"><span class="glyphicon glyphicon-share-alt"></span></a>
		<div onclick="zoomImgPrev();" class="a_g_zoom_btn "><span class="glyphicon glyphicon-chevron-left"></span></div>
		<div onclick="zoomImgNext();" class="a_g_zoom_btn "><span class="glyphicon glyphicon-chevron-right"></span></div>
		<div onclick="zoomImgClose();" class="a_g_zoom_btn "><span class="glyphicon glyphicon-remove"></span></div>
		<br />
		<br />
		<input onchange="checkZoomImgCheckbox(this);" class="zoom_tmp_check" type="checkbox" />
		<div class="a_image_check_marker"><span class="glyphicon glyphicon-ok"></span></div>
	</div>
	<div class="a_g_zoom_separ"></div>
	<div class="a_g_zoom_imags_cont">
		
	</div>
</div>
<div class="a_gallery_cont">
	<?$imgNum=1;?>
	<?while($img_list&&$img_array = mysql_fetch_array($img_list)):?>
		<div onclick="checkImg(<?=$img_array['id']?>)" class="a_pic_cont">
			<div class="a_pic_cont_main">
				<img onclick="zoomImg('<?=$imgNum;?>')" class="img_<?=$imgNum;?>" src="<?=$img_array['url']?>" alt="" />
			</div>
			
			<p> id: <?=$img_array['id']?></p>
			<p> <?=$img_array['name']?></p>
			<p> <?=$img_array['width']?>x<?=$img_array['height']?> (<?=floor($img_array['size']/1024);?> КБ)</p>
			<p> <?=date("d.m.Y H:i:s",$img_array['date_insert']);?></p>
			<?if($img_array['active']=='Y'):?>
				<p><span style="background-color:#7cc931">Активно</span></p>
			<?elseif($img_array['active']=='N'):?>
				<p><span style="background-color:#ff4343">Неактивно</span></p>
			<?else:?>	
				<p><span style="background-color:#eec022">Модерируется</span></p>
			<?endif;?>						
			<?if($img_array['activated']=='N'):?>
				<div class="a_img_not_activated">
					<div title="Не активирован" class="glyphicon glyphicon-ban-circle"></div>
				</div>
			<?endif;?>
			<div class="a_img_checkbox_cont"><input name="g_target[<?=$img_array['id']?>]" value="<?=$img_array['id']?>" class="a_img_checkbox a_img_checkbox_<?=$img_array['id']?> a_img_check_for_zoom_<?=$imgNum;?>" type="checkbox" /></div>
			<div class="a_img_menu_btn_cont"><div><span class="glyphicon glyphicon-align-justify"></span></div></div>
		</div>
		<?$imgNum++;?>
	<?endwhile;?>
	<script type="text/javascript">
		var a_img_count = <?=$imgNum;?>;
	</script>

</div>
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
