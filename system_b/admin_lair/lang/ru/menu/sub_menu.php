<?foreach($ADMIN_MENU as $menu_punkt):?>	
	<div id="<?=$menu_punkt['LINK']?>">
		<?foreach($menu_punkt['SUB_MENU'] as $sub_menu_punkt):?>
			<?if($sub_menu_punkt['NAME']=='sub_menu_separator'):?>
				<div class="sub_menu_separator"></div>
			<?else:?>
				<a style="<?if($sub_menu_punkt['HIDDEN']):?>display:none;<?endif;?> <?if($sub_menu_punkt['ICON']!=''):?>background-image:url('./images/menu_icons/<?=$sub_menu_punkt['ICON']?>');padding-left:46px;<?endif;?>" <?if(basename($_SERVER['PHP_SELF'])==$sub_menu_punkt['LINK']) echo 'class="admin_sub_menu_cur"';?> href='<?=$sub_menu_punkt['LINK']?>'><?=$sub_menu_punkt['NAME']?></a>
				<?if(basename($_SERVER['PHP_SELF'])==$sub_menu_punkt['LINK']):?>
					<script type="text/javascript"> globalMenuSelectLink('<?=$menu_punkt['LINK'];?>','menu_link_to_<?=$menu_punkt["LINK"];?>');</script>
				<?endif;?>		
			<?endif;?>	
		<?endforeach;?>
	</div>
<?endforeach;?>