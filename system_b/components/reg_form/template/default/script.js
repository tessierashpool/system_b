function srShow(e){
	if($(e).hasClass('a_r_line_active'))
	{
		$('.a_r_line a').removeClass('a_r_line_active');		
		if($(e).hasClass('siClass'))
		{
			$('.sign_in_cont').fadeOut(200);
		}
		else if($(e).hasClass('rClass'))
		{
			$('.regist_in_cont').fadeOut(200);	
		}		
	}
	else
	{
		$('.a_r_line a').removeClass('a_r_line_active');
		$(e).addClass('a_r_line_active');
		if($(e).hasClass('siClass'))
		{
			$('.regist_in_cont').css('display','none');
			$('.sign_in_cont').fadeIn(200);
		}
		else if($(e).hasClass('rClass'))
		{
		$('.sign_in_cont').css('display','none');
		$('.regist_in_cont').fadeIn(200);		
		}
	}
	leftColumnHeight();
}



