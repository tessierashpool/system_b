function slideDownPostContBtn(e){

	var lefty = $('.new_post_main_cont');
	if( lefty.css('display') == 'none' )
	{
		scrollShowHide('h');
		
		$('.new_post_main_wraper').css('display','block');
		$('.bg_black').css('display','block');
		lefty.css('display','block');
	}
	else
	{
		scrollShowHide('s');
		
		$('.new_post_main_wraper').css('display','none');
		$('.bg_black').css('display','none');
		lefty.css('display','none');
	}
	newPostAddFormPosition();
}



function newPostAddFormPosition()
{	
	//offset = $('.posts_main_cont').offset();
	//$('.new_post_main_cont').css({"margin-left": offset.left+'px',"margin-top":'100px'});
}