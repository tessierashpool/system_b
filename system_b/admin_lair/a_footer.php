				
				<?if(count(CAdminPage::getAdminButtons())>0):?>
				<div class="footer_buttons_cont">
					<?
						$arButtons = CAdminPage::getAdminButtons();
						foreach($arButtons as $arButton):
							$line = ''; 
							if($arButton['type']=='l')
							{
								if($arButton['class']!='')
									$line .= ' class="'.$arButton['class'].'"';
								else
									$line .= ' class="a_button_custom"';
									
								echo '<a style="line-height:21px" '.$line.' href="'.$arButton['link'].'">'.$arButton['value'].'</a>';
							}
							else
							{
								if($arButton['type']=='s')
									$line = 'type="submit"';
								else
									$line = 'type="button"';
									
								if($arButton['class']!='')
									$line .= ' class="'.$arButton['class'].'"';
								else
									$line .= ' class="a_button_custom"';

								if($arButton['name']!='')
									$line .= ' name="'.$arButton['name'].'"';
								else
									$line .= ' name="a_save_btn"';
									
								$line .= ' value="'.$arButton['value'].'"';
								
								?> <input <?=$line;?> /> <?
							}
						endforeach;				
					?>
				</div>
				</div>
				<?else:?>
					<style type="text/css">
						.admin_sub_main_place{
							padding-bottom:0px;
						}
					</style>			
				<?endif;?>
			</form>
		</td>	
	</tr>
</table>
</body>
</html>  
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/epilog.php');?>