<div class="captcha_cont">
	<?echo $data['captcha'];?>
</div>			
<p class="captcha_title_input "><?=$MESS['REG_FORM_CAPTCHA'];?></p>
<p><input name="<?=$data['CAPTCHA_NAME'];?>" id="<?=$data['CAPTCHA_NAME'];?>" class="a_r_input captch_input" type="text" autocomplete="off" /></p>	
<div class="cpat_reload_btn" onclick="<?echo CCaptcha::getReloadLink($data['CAPTCHA_NAME']);?>" title="Not readable? Change text."></div>
<div style="clear:both"></div>			
			