<?
$filter_active = $arData['FILTER_ACTIVE'];
$filter_val = $arData['FILTER_VAL'];
$arFields = $arParams['FIELDS_ARRAY'];
$arMenuSettings = $arParams['MENU_SETTINGS'];
$table_name = $arParams['TABLE_NAME'];
?>
<?
$sort = array();
if(isset($_REQUEST['order'])&&isset($_REQUEST['order_d']))
{
	$sort[$_REQUEST['order']]=$_REQUEST['order_d'];
}

$cells_on_page = $_GET['elements_on_page'];
$page = $_GET['page'];
$limit = array($page,$cells_on_page);	
$users_list = CDateBase::getListFromTable($table_name,CAdminPage::getFilter(),$sort,$limit);?>
<?CDateBase::pageNav('admin_page_nav');?>
<script type="text/javascript">
	function elementGetMenu(e,target)
	{
	
		var ePosition = $(e).offset();
		var menu_cont = '<div class="a_list_menu_element_menu" >';
		menu_cont += $('#menu_'+target).html();	
		menu_cont += '</div>';		

		$('.a_table_list').before(menu_cont);
		$('.a_list_menu_element_menu').offset({top:ePosition.top-3, left:ePosition.left+25});
	}

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

<table class="a_table_list"  border="0"  cellpadding="0" cellspacing="0">
	<tr >
		<th class="checkbox_column" style="text-align:center"><input class="g_target_checkbox g_target_checkbox_prime" onchange="gTargetCheck(this)" type="checkbox" /></th>
		<th class="fn_column" style="text-align:center"></th>
		<?
			foreach($arFields as $key => $field)
			{
				$order_line = '';
				$order_link = '';
				if(isset($_REQUEST['order']))
				{
					if($_REQUEST['order']==$key)
					{
						if(isset($_REQUEST['order_d']))
						{
							if($_REQUEST['order_d']=='DESC')
							{
								$order_line = "<span class='a_list_order_btn'> &#8595;</span>";
								$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'ASC'),array('success_msg','order','order_d','action','e_target','g_target'));
							}	
							elseif($_REQUEST['order_d']=='ASC')
							{
								$order_line = "<span class='a_list_order_btn'> &#8593;</span>";		
								$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'DESC'),array('success_msg','order','order_d','action','e_target','g_target'));
							}							
						}
					}
					else
					{
						$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'ASC'),array('success_msg','order','order_d','action','e_target','g_target'));
					}
				}
				else
				{
					if($key=='id')
					{
						$order_line = "<span class='a_list_order_btn'> &#8593;</span>";	
						$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'DESC'),array('success_msg','order','order_d','action','e_target','g_target'));
					}
					else
					{
						$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'ASC'),array('success_msg','order','order_d','action','e_target','g_target'));
					}
						
				}
				echo "<th ><a href='".$order_link."'>".$field['TITLE'].$order_line."</a></th>";
			}
		?>
	</tr>
	<?while($users_list&&$user_array = mysql_fetch_array($users_list)):?>
		<tr <?if($arMenuSettings['DBL_CLICK']!=''):?> ondblclick = "<?=$arMenuSettings['DBL_CLICK']?>(this,<?=$user_array['id']?>)" <?endif;?>>
			<th class="checkbox_column" style="text-align:center"><input onchange="oneElementTargetCheck()" class="g_target_checkbox" name="g_target[<?=$user_array['id']?>]" type="checkbox" /></th>
			<th class="fn_column" style="text-align:center"> <div onclick="elementGetMenu(this,'<?=$user_array['id']?>')" class="a_list_menu_btn"></div></th>			
			<?//======================Menu for element======================?>
			<div style="display:none" id="menu_<?=$user_array['id']?>">
			<?if(count($arMenuSettings['ELEMENT'])>0):?>
				<?foreach($arMenuSettings['ELEMENT'] as $elementMenu):?>
					<?if(isset($elementMenu['ONLY_IF'])):?>
						<?if($elementMenu['ONLY_IF']['OPERATOR']=='!='):?>
							<?if($user_array[$elementMenu['ONLY_IF']['FIELD']]!=$elementMenu['ONLY_IF']['VALUE']):?>
								<?if($elementMenu['TYPE']=='link'):?>
									<?if($elementMenu['REQUEST_CONFIRM']):?>
										<a onclick="return confirmAction('<?=$elementMenu['CONFIRM_TEXT']?>')" href = "<?=currentPageWithParams(array('action'=>$elementMenu['ACTION']),array('success_msg','action','e_target'))?>&e_target=<?=$user_array['id']?>" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></a>
									<?else:?>					
										<a  href = "<?=currentPageWithParams(array('action'=>$elementMenu['ACTION']),array('success_msg','action','e_target'))?>&e_target=<?=$user_array['id']?>" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></a>
									<?endif;?>
								<?else:?>
									<div onclick="<?=$elementMenu['FUNCTION'];?>(this,'<?=$user_array['id']?>')" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></div>
								<?endif;?>
								<div class="a_menu_lelements_separ"></div>			
							<?endif;?>						
						<?else:?>
							<?if($user_array[$elementMenu['ONLY_IF']['FIELD']]==$elementMenu['ONLY_IF']['VALUE']):?>
								<?if($elementMenu['TYPE']=='link'):?>
									<?if($elementMenu['REQUEST_CONFIRM']):?>
										<a onclick="return confirmAction('<?=$elementMenu['CONFIRM_TEXT']?>')" href = "<?=currentPageWithParams(array('action'=>$elementMenu['ACTION']),array('success_msg','action','e_target'))?>&e_target=<?=$user_array['id']?>" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></a>
									<?else:?>					
										<a  href = "<?=currentPageWithParams(array('action'=>$elementMenu['ACTION']),array('success_msg','action','e_target'))?>&e_target=<?=$user_array['id']?>" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></a>
									<?endif;?>
								<?else:?>
									<div onclick="<?=$elementMenu['FUNCTION'];?>(this,'<?=$user_array['id']?>')" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></div>
								<?endif;?>
								<div class="a_menu_lelements_separ"></div>			
							<?endif;?>	
						<?endif;?>		
					<?else:?>
						<?if($elementMenu['TYPE']=='link'):?>
							<?if($elementMenu['REQUEST_CONFIRM']):?>
								<a onclick="return confirmAction('<?=$elementMenu['CONFIRM_TEXT']?>')" href = "<?=currentPageWithParams(array('action'=>$elementMenu['ACTION']),array('success_msg','action','e_target'))?>&e_target=<?=$user_array['id']?>" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></a>
							<?else:?>					
								<a  href = "<?=currentPageWithParams(array('action'=>$elementMenu['ACTION']),array('success_msg','action','e_target'))?>&e_target=<?=$user_array['id']?>" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></a>
							<?endif;?>
						<?else:?>
							<div onclick="<?=$elementMenu['FUNCTION'];?>(this,'<?=$user_array['id']?>')" class="a_list_menu_punkt"><?=$elementMenu['TITLE']?></div>
						<?endif;?>
						<div class="a_menu_lelements_separ"></div>	
					<?endif;?>
				<?endforeach;?>	
			<?endif;?>	
			</div>	
			<?//================================================================?>	
			<?
				foreach($arFields as $key => $field)
				{
					if($field['TYPE']=='date')
						echo "<td >".date("d-m-Y H:i:s",$user_array[$key])."</td>";
					elseif($field['TYPE']=='list')
						echo "<td >".$field['LIST']['TITLE'][array_search($user_array[$key], $field['LIST']['VALUE'])]."</td>";
					else
						echo "<td >".$user_array[$key]."</td>";
				}
			?>				
		</tr>
	<?endwhile;?>
	<tr >
		<th class="checkbox_column" style="text-align:center"><input class="g_target_checkbox g_target_checkbox_prime" onchange="gTargetCheck(this)"  type="checkbox" /></th>
		<th class="fn_column" style="text-align:center"></th>
		<?
			foreach($arFields as $key => $field)
			{
				$order_line = '';
				$order_link = '';
				if(isset($_REQUEST['order']))
				{
					if($_REQUEST['order']==$key)
					{
						if(isset($_REQUEST['order_d']))
						{
							if($_REQUEST['order_d']=='DESC')
							{
								$order_line = "<span class='a_list_order_btn'> &#8595;</span>";
								$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'ASC'),array('success_msg','order','order_d','action','e_target','g_target'));
							}	
							elseif($_REQUEST['order_d']=='ASC')
							{
								$order_line = "<span class='a_list_order_btn'> &#8593;</span>";		
								$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'DESC'),array('success_msg','order','order_d','action','e_target','g_target'));
							}							
						}
					}
					else
					{
						$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'ASC'),array('success_msg','order','order_d','action','e_target','g_target'));
					}
				}
				else
				{
					if($key=='id')
					{
						$order_line = "<span class='a_list_order_btn'> &#8593;</span>";	
						$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'DESC'),array('success_msg','order','order_d','action','e_target','g_target'));
					}
					else
					{
						$order_link = currentPageWithParams(array('order'=>$key, 'order_d'=>'ASC'),array('success_msg','order','order_d','action','e_target','g_target'));
					}
						
				}
				echo "<th ><a href='".$order_link."'>".$field['TITLE'].$order_line."</a></th>";
			}
		?>
	</tr>		
</table>
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
</script>
<?if(count($arMenuSettings['GLOBAL'])):?>
	<div class="a_list_global_action">
		<select name="action" onchange="globalActionChange(this)" id="">
			<option value="">-</option>
			<?foreach($arMenuSettings['GLOBAL'] as $global_action):?>
				<option  value="<?=$global_action['ACTION']?>"><?=$global_action['TITLE']?></option>
			<?endforeach;?>
		</select>
		<div style="clear:both"></div>
	</div>
<?endif;?>
<?CDateBase::pageNav('admin_page_nav');?>
