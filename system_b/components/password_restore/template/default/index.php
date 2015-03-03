<?if(isset($data["SUCCESS_RESET"])):?>
	<p class="pr_succes_reset"><?=$MESS['PR_SUCCESS_RESET']?></p>
<?else:?>
<form action="" method="post">
	<div class="pass_r_m_cont">
		<p class="prtitle"><?=$MESS['PASS_RESORE_TITLE'];?></p>
		<p class="rpinput_title"><?=$MESS['PASS_RESORE_NEW_P_TITLE'];?></p>
		<p><input name = "pr_new_password" class="pr_input" type="password" /></p>
		<p class="rpinput_title"><?=$MESS['PASS_RESORE_C_NEW_P_TITLE'];?></p>
		<p><input name = "pr_new_password_confirm" class="pr_input" type="password" /></p>	
		<?CMain::GC('captcha',array('CAPTCHA_NAME'=>'captcha_restore_pass'));?>
		<p><input type="submit" class="p_r_btn" name="p_r_btn_save" value="<?=$MESS['PASS_RESORE_BTN_TITLE'];?>" /></p>
	</div>
</form>
<?endif;?>