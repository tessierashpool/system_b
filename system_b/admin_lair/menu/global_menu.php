<?require ('menu_generator.php')?>
<div class="admin_global_menu">	
	<div class="admin_button_list">
		<?foreach($ADMIN_MENU as $menu_punkt):?>
			<div id="menu_link_to_<?=$menu_punkt["LINK"];?>" onclick = "globalMenuSelectLink('<?=$menu_punkt["LINK"];?>', 'menu_link_to_<?=$menu_punkt["LINK"];?>')" class= "admin_global_btn" >
				<?=$menu_punkt['NAME']?>
			</div>			
		<?endforeach;?>
	</div>
</div>