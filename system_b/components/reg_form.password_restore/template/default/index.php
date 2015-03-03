<div class="fp_form_cont_wraper">
	<div class="fp_form_cont">
		<p class="fp_form_title"><?=$MESS['REG_FORM_R_P_F_TITLE']?></p>
		<p class="fp_form_input_title"><?=$MESS['REG_FORM_R_P_EMAIL_TITLE']?></p>
		<p><input type="text" id="fp_email_input" /></p>
		<div class="fp_form_submit_cont">
			<div class="fp_form_btns" onclick="return fpVerifInput(); ">
				<?=$MESS['REG_FORM_R_P_SBM_BTN']?>
			</div>		
			<div class="fp_form_btns" style="margin-left:15px" onclick=" fPFormShow(); ">
				<?=$MESS['REG_FORM_R_P_CANC_BTN']?>
			</div>							
		</div>	
	</div>
</div>
<p class="fp_title" onclick="fPFormShow();">Forgot your password?</p>
<script type="text/javascript">
	function fpSendEmailAjax()
	{
		var email_adress = $('#fp_email_input').val();
		fPFormShow();
		msgBox("<?=$MESS['REG_FORM_R_P_EMAIL_SEND_MSG']?>","g");
		$.post( "", { fp_ajax: '1',email_adress:email_adress});
		return false;
	}
	
	function fpVerifInput()
	{
		msgBoxCloseAll();

		if($('#fp_email_input').val().trim()=='')
		{
			msgBox('<?=$MESS['REG_FORM_ERROR_EMPTY_EMAIL'];?>','r');
			$('#fp_email_input').focus();
			return false;			
		}
		fpSendEmailAjax();
		return true;
	}
</script>