<?foreach($ADMIN_MENU as $menu_punkt):?>	
	<div id="<?=$menu_punkt['LINK']?>">
		<?foreach($menu_punkt['SUB_MENU'] as $sub_menu_punkt):?>
			<?if($sub_menu_punkt['NAME']=='sub_menu_separator'):?>
				<div class="sub_menu_separator"></div>
			<?elseif($sub_menu_punkt['NAME']=='list_title'):?>
				<div class="sub_menu_list_title"><?=$sub_menu_punkt['TITLE']?><span  class="glyphicon glyphicon-chevron-down"></span></div>
			<?elseif(isset($sub_menu_punkt['LIST'])):?>
				<a style="padding-left:40px;" <?if((basename($_SERVER['PHP_SELF'])==$sub_menu_punkt['PAGE'])&&($_REQUEST['id']==$sub_menu_punkt['ID'])) echo 'class="admin_sub_menu_cur"';?> href='<?=$sub_menu_punkt['LINK']?>'><span><?=$sub_menu_punkt['NAME']?></span></a>
				<span style="height:5px;width:10px;display:block;<?if($sub_menu_punkt['HIDDEN']):?>display:none;<?endif;?>"></span>
				<?if(basename($_SERVER['PHP_SELF'])==$sub_menu_punkt['LINK']):?>
					<script type="text/javascript"> globalMenuSelectLink('<?=$menu_punkt['LINK'];?>','menu_link_to_<?=$menu_punkt["LINK"];?>');</script>
				<?endif;?>				
			<?else:?>
				<a style="<?if($sub_menu_punkt['HIDDEN']):?>display:none;<?endif;?> <?if($sub_menu_punkt['ICON']!=''):?>background-image:url('./images/menu_icons/<?=$sub_menu_punkt['ICON']?>');padding-left:46px;<?endif;?> <?if(isset($sub_menu_punkt['LIST'])):?>padding-left:40px;<?endif;?>" <?if(basename($_SERVER['PHP_SELF'])==$sub_menu_punkt['LINK']) echo 'class="admin_sub_menu_cur"';?> href='<?=$sub_menu_punkt['LINK']?>'><span><?=$sub_menu_punkt['NAME']?></span></a>
				<span style="height:5px;width:10px;display:block;<?if($sub_menu_punkt['HIDDEN']):?>display:none;<?endif;?>"></span>
				<?if(basename($_SERVER['PHP_SELF'])==$sub_menu_punkt['LINK']):?>
					<script type="text/javascript"> globalMenuSelectLink('<?=$menu_punkt['LINK'];?>','menu_link_to_<?=$menu_punkt["LINK"];?>');</script>
				<?endif;?>		
			<?endif;?>	
		<?endforeach;?>
	</div>
<?endforeach;?>