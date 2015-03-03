<?
$filter_active = $arData['FILTER_ACTIVE'];
$filter_val = $arData['FILTER_VAL'];
$arFields = $arParams['FIELDS_ARRAY'];

?>
<div class="a_filter_cont">
	<script type="text/javascript">
		$(document).ready(function(){
			$( ".datepicker" ).datetimepicker();
			$( ".datepicker" ).datetimepicker("option", "dateFormat","dd-mm-yy");					
		})		
	</script>
	<div class="a_filter_cont_btn f_b_expanded" onclick="filterExpand()"></div>
	<p class="a_filter_title" onclick="filterExpand()" ><?=CMain::getM('T_FILTER')?> <?if($filter_active) echo CMain::getM('T_FILTER_ACTIVE');?></p>
	<div class="a_filter_cont_inner">
		<table class="a_table_filter" width="100%"  cellpadding="0" cellspacing="0" border="0">	
			<?foreach($arFields as $key=>$field):?>
				<?if(!$field['NOFILTER']):?>
					<?if($field['TYPE']=='date'):?>
						<tr>
							<td align="left"><p><?=$field['TITLE']?>:</p></td>
							<script type="text/javascript">
								$(document).ready(function(){
									//We put default values to the input[text] that way, because of jquery datepicker somehow clear the value attribute of input[text]
									$( ".<?=$key;?>_from" ).val('<?=$filter_val[$key]['from']?>');							
									$( ".<?=$key;?>_to" ).val('<?=$filter_val[$key]['to']?>');							
								})		
							</script>					
							<td align="left"><input class="datepicker <?=$key;?>_from" type="text" name="FILTER[<?=$key;?>][from]" placeholder = "<?=CMain::getM('T_DATE_FROM')?>" value="" /></td>
							<td align="left"><input class="datepicker <?=$key;?>_to"  type="text" name="FILTER[<?=$key;?>][to]" placeholder = "<?=CMain::getM('T_DATE_TO')?>" value="" /></td>
						</tr>					
					<?elseif($field['TYPE']=='list'):?>
						<tr>
							<td align="left"><p><?=$field['TITLE']?>:</p></td>
							<td align="left" colspan="2"><?CAdminPage::getDropList($arFields[$key]['LIST']['VALUE'],$arFields[$key]['LIST']['TITLE'],'FILTER['.$key.']',$filter_val[$key])?></td>
						</tr>					
					<?else:?>
						<tr>
							<td align="left"><p><?=$field['TITLE']?>:</p></td>
							<td align="left" colspan = "2"><input type="text" name="FILTER[<?=$key?>]" value="<?=$filter_val[$key]?>" /></td>
						</tr>	
					<?endif;?>				
				<?endif;?>				
			<?endforeach;?>
		</table>	
		<script type="text/javascript">
			$(document).ready(function(){					
				//Save the value of "expand/collapse" of filter inner cont
				if(!localStorage.getItem("<?=basename($_SERVER['PHP_SELF'])?>_filter"))	
				{
					$('.a_filter_cont_btn').removeClass('f_b_expanded');
					$('.a_filter_cont_inner').hide();					
				}
			})
			
			function filterExpand()
			{
				if($('.a_filter_cont_btn').hasClass('f_b_expanded'))
				{
					$('.a_filter_cont_btn').removeClass('f_b_expanded');
					$('.a_filter_cont_inner').hide();
					localStorage.removeItem("<?=basename($_SERVER['PHP_SELF'])?>_filter");
				}
				else
				{
					$('.a_filter_cont_btn').addClass('f_b_expanded');
					$('.a_filter_cont_inner').show();
					localStorage.setItem("<?=basename($_SERVER['PHP_SELF'])?>_filter", "1");
				}
			}			
		</script>
		<br />
		<input type="submit" <?if($filter_active) echo 'class="button_filter_go_active" value="'.CMain::getM('T_FILTER_GO_ACTIVE').'"'; else echo 'class="button_filter_go" value="'.CMain::getM('T_FILTER_GO').'"';?> name="filter_go"  />
		<input type="submit" name="filter_clear"  class="button_filter_clear" value="<?=CMain::getM('T_FILTER_CLEAR')?>" />
	</div>
</div>	
