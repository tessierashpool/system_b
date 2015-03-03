function fPFormShow(){
	if($('.fp_form_cont_wraper').css('display')=='none')
	{
		$('.bg_black').css('display','block');
		scrollShowHide('h');
		$('.fp_form_cont_wraper').prependTo('body');
		$('.fp_form_cont_wraper').css('display','block');
		$('#fp_email_input').focus();		
	}
	else
	{
		$('.bg_black').css('display','none');
		scrollShowHide('s');
		$('.fp_form_cont_wraper').css('display','none');	
	}

}

