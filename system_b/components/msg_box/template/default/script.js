var msgFlag = 1;
function msgBox(msg,color){
	if(color=='r')
		var  str = '<div class="msg_red msg_cont" onclick="msgBoxClose(this)"><p><span class="msg_report">'+msg+'</span></p><div class="close_msg_cont"></div></div>';
	else
		var  str = '<div class="msg_green msg_cont" onclick="msgBoxClose(this)"><p><span class="msg_report">'+msg+'</span></p><div class="close_msg_cont"></div></div>';
		
	if(parseInt($('.admin_panel_p').height())>0)
		$('.msg_box').css('top',parseInt($('.admin_panel_p').height())+'px');
		
	$(".msg_box").fadeIn(1);
	$('.msg_box').append(str);
	$(".msg_box div").fadeIn(100);

	return false;
}
function msgBoxClose(e){
	var count = $(".msg_box div.msg_cont").length;
	if(count>1)
		$(e).remove();
	else	
	{
		$(e).remove();
		$(".msg_box").css('display','none');
	}	
	return false;
}
function msgBoxCloseAll(){
	var count = $(".msg_box div").remove();
	$(".msg_box").css('display','none');
	return false;
}
