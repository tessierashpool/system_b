<?
global $USER;
if($USER->isAuthorized()&&$USER->hr('admin_page_access'))
{
	redirect('/system_b/admin_lair/');
	exit();
}
	
?>
<div class="a_r_cont">
	<p class="a_r_line"><a onclick="srShow(this)" class="siClass">Sign in</a></p>
	<div class="sign_in_cont" >
		<form action="" method="post">
			<p class="a_r_title"><?=CMain::getM('REG_FORM_LOGIN_T');?></p>
			<p><input class="a_r_input" name="login" id="s_login" value="<?=$arData['s_login']?>"  type="text" /></p>
			<p class="a_r_title"><?=CMain::getM('REG_FORM_PASS_T');?></p>
			<p><input class="a_r_input" name="password" id="s_password" type="password" /></p>	
			<div class="checkbox_emul_cnt">
				<div class="checkbox_emul <?if($arData['stay_sigin']==1){echo 'checkbox_emul_checket';}?>" onclick="if($(this).next('input').val()=='0'){$(this).addClass('checkbox_emul_checket');$(this).next('input').val('1');}else{$(this).removeClass('checkbox_emul_checket');$(this).next('input').val('0');}"></div>
				<input type="hidden" name="stay_sigin" value="<?if($arData['stay_sigin']==1){echo '1';}else{echo '0';}?>" />
				<div class="checkbox_emul_t" >Stay signed in </div>
				<div style="clear:both"></div>		
			</div>
			<?
				if(CUsersMain::isBadLoginer())
					CMain::GC('captcha',array('CAPTCHA_NAME'=>'captcha_signin'));
			?>				
			<div class="s_i_btn_cont">
				<input type="submit" name="sign_in" class="c_a_btn" value="<?=CMain::getM('REG_FORM_LOG_BTN');?>" onClick="return signInFldVerif();" />
			</div>	
		</form>				
	</div>

</div>