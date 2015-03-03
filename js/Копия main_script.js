$(document).ready(function() {
	//localStorage.removeItem('hiddenComments');
	linkToHiddenCom();
	scrollPosition();
	hideCommentsFromLS();
	$('.lift_me_up').click(function(){
		$(document).scrollTop(0);
	});
	//pop_up_show();
	//slideDownPostContBtn();
	$('#new_post_vid_url').bind('input', function() { 
		setTimeout(function () {
		newPostLoadVideo();
		},250);
	}).keyup(function() {
		setTimeout(function () {
		newPostLoadVideo();
		},250);
	});
	
	$('.search_cont input').focusin(function(){
	//	$(this).animate({'width':'290px'},200);	
		if ($(this).val() == 'Search...') {
			$(this).val("");
			$(this).css({'color':'#276e87'});
		}
		
	}).focusout(function(){
		//$(this).animate({'width':'150px'},200);
		if ($(this).val() == '') {
			$(this).css('color','#a9a9a9');
			$(this).val("Search...");
		}		
	});
});

$(window).resize(function() {
	if($(window).width()<$('.body_cont').width())
	{
		$('.lift_me_up').removeClass('lift_me_up_max').addClass('lift_me_up_min');
	}
	else
	{
		$('.lift_me_up').removeClass('lift_me_up_min').addClass('lift_me_up_max');	
	}
});

function linkToHiddenCom(){
	var pathArray = document.URL.split( '#' );
	$('div .sub_comments_f').has('#'+pathArray[1]).map(function(){
		var sub = $(this).children('.sub_comment_close');
		longCommOpen(sub);
			
	});	
}

function jump(h){
    var url = location.href;               //Save down the URL without hash.
    location.href = "#"+h;                 //Go to the target element.
    //history.replaceState(null,null,url);   //Don't like hashes. Changing it back.
}

function newPostLoadVideo(){
	vidObj = $('.new_pos_video_cont input');
	vidObj.next().remove();
	newVidUrl = $('#new_post_vid_url').val();	
	if(parse_url = youtube_parser(newVidUrl))
	{
		vidUrl = 'https://www.youtube.com/v/'+parse_url+'?version=3';
		vidObjText = '<div class="posted_video_cont"><object width="640" height="360">'
		vidObjText += '<param name="movie" value="'+vidUrl+'"></param>'
		vidObjText += '<param name="allowFullScreen" value="true"></param>'
		vidObjText += '<param name="allowScriptAccess" value="always"></param>'
		vidObjText += ' <embed src="'+vidUrl+'" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="360"></embed>'
		vidObjText += '</object>'
		vidObjText += '<div class="new_post_clear_btn" onclick="clearNewVideoNP()">clear</div></div>'
	}
	else
	{
		vidObjText = "<div class='video_load_error_msg'>Can't load video perhaps the link is invalid</div>";
	}
	if(newVidUrl!='')
		vidObj.after(vidObjText);	
}
function clearNewVideoNP(){
	vidObj = $('.new_pos_video_cont input');
	vidObj.next().remove();	
	$('#new_post_vid_url').val('');
}
function youtube_parser(url){
    var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[1].length==11){
        return match[1];
    }else{
       return false;
    }
}
function showNewPost(e,text){
	$('.new_post_type_s div').removeClass('new_post_type_a');
	$(e).addClass('new_post_type_a');
	if(text=='image')
	{

		$('.new_pos_images_main').css('display','block');
		$('.new_pos_video_main').css('display','none');
		$('.new_pos_text_main').css('display','none');
	}
	else if	(text=='video')
	{
		$('.new_pos_images_main').css('display','none');
		$('.new_pos_video_main').css('display','block');
		$('.new_pos_text_main').css('display','none');
	}
	else
	{
		$('.new_pos_images_main').css('display','none');
		$('.new_pos_video_main').css('display','none');
		$('.new_pos_text_main').css('display','block');
	}
}
function slidePostCont(){
	var $lefty = $('.posts_main_cont');
	$lefty.animate({
		marginLeft: parseInt($lefty.css('margin-left'),10) == 10 ?
		-$lefty.outerWidth() :
		10
	},100);	
}
function slidePostContBtn(e){
	var $lefty = $('.posts_main_cont');
	if( parseInt($lefty.css('margin-left'),10) == 10 )
	{
		$lefty.animate({marginLeft:-$lefty.outerWidth()},100);
		$('.new_post_btn').css('background-color','#67a114');
	}
	else
	{
		$lefty.animate({marginLeft:10},100);
		$('.new_post_btn').css('background-color','#48aacd');
	}
}
function slideDownPostContBtn(e){
	var lefty = $('.new_post_main_cont');
	if( lefty.css('display') == 'none' )
	{
		lefty.fadeIn(200);
		$('.new_post_btn').css('background-color','#67a114');
	}
	else
	{
		lefty.fadeOut(200);
		$('.new_post_btn').css('background-color','#48aacd');
	}
}

function getScrollbarWidth() {
  var div, body, W = window.browserScrollbarWidth;
  if (W === undefined) {
    body = document.body, div = document.createElement('div');
    div.innerHTML = '<div style="width: 50px; height: 50px; position: absolute; left: -100px; top: -100px; overflow: auto;"><div style="width: 1px; height: 100px;"></div></div>';
    div = div.firstChild;
    body.appendChild(div);
    W = window.browserScrollbarWidth = div.offsetWidth - div.clientWidth;
    body.removeChild(div);
  }
  return W;
};

function pop_up_show(){
	$('.new_post_bg_wrap').css('display','block');
	if(document.documentElement.scrollHeight>document.body.clientHeight)
		$('body').css({'overflow':'hidden','padding-right':getScrollbarWidth()+'px'});
}

function pop_up_hide(){
	$('.new_post_bg_wrap').css('display','none');
	if(document.documentElement.scrollHeight>document.body.clientHeight)
		$('body').css({'overflow':'auto','padding-right':'0px'});
}
function scrollPosition(){
	if($(window).width()<$('.body_cont').width())
	{
		$('.lift_me_up').removeClass('lift_me_up_max').addClass('lift_me_up_min');
	}
	else
	{
		$('.lift_me_up').removeClass('lift_me_up_min').addClass('lift_me_up_max');	
	}	
		
	var top_h = $('.header').height()+$('.sainfo').height()+40+120;
	$(document).scroll(function(){
		//$('.logCont').text($(document).height()-$(document).scrollTop());
		$('.logCont').text($(window).height()-(top_h-$(document).scrollTop()));
		if(($(window).height()-(top_h-$(document).scrollTop()))>100&&$(document).scrollTop()>120)
			$('.lift_me_up').fadeIn(200);
		else
			$('.lift_me_up').css('display','none');		
	});
}
function clearAllPosts(){
	$('.post_cont').remove();
	$('.post_separ').remove();
}
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
}
var msgFlag = 1;
function msgBox(msg){
	/*if(msgFlag)
	{
		msgFlag = 0;
		var sec = 5;
		$('.msg_timer').html(sec + ' sec.');
		$('.msg_box').fadeIn();
		$('.msg_report').html(msg);
		var myVar = setInterval(function(){
			sec -=1;
			$('.msg_timer').html(sec + ' sec.');
			if(sec==0)
			{
				clearInterval(myVar);
				$('.msg_box').fadeOut();
				msgFlag = 1;
			}
		},1000);
	}*/
	return false;
}

function longCommOpen(e){
	$(e).css('display','none');
	$(e).next('.sub_comment_open').css('display','block');
}
function longCommCloseD(e){
	$(e).parent().parent('.sub_comment_open').css('display','none');
	$(e).parent().parent('.sub_comment_open').prev('.sub_comment_close').css('display','block');
}
function longCommCloseU(e){
	$(e).parent('.sub_comment_open').css('display','none');
	$(e).parent('.sub_comment_open').prev('.sub_comment_close').css('display','block');
}
function trigerNewCommentCont(){
	if($('.new_comment_cont').css('display')=='none')
	{
		showNewCommentCont();
	}
	else
	{
		hideNewCommentCont();
	}
}
function showNewCommentCont(){
	$('.new_comment_cont').css('display','block');
	$('.submit_new_com_cont').css('font-weight','bold');
	$('.reply_comment_cont').remove();
	$('.report_comment_cont').remove();
	$('.link_comment_cont').remove();
	$('.active_opt_comm').removeClass('active_opt_comm');
}
function hideNewCommentCont(){
	$('.new_comment_cont').css('display','none');
	$('.submit_new_com_cont').css('font-weight','100');
}
var l1h = "622px";
var l2h = "591px";
var l3h = "560px";
var l4h = "529px";
var l5h = "498px";
var l6h = "467px";
var l7h = "436px";
var l8h = "405px";
var l9h = "374px";
/*Reply comment*/
function replyComment(comm_id,comm_lvl,e){
	var c_html = '';
	var tf_width = '';
	switch(comm_lvl)
	{
	case 1:
		tf_width = l1h;
		break;
	case 2:
		tf_width = l2h;
		break;
	case 3:
		tf_width = l3h;
		break;
	case 4:
		tf_width = l4h;
		break;
	case 5:
		tf_width = l5h;
		break;
	case 6:
		tf_width = l6h;
		break;
	case 7:
		tf_width = l7h;
		break;	
	case 8:
		tf_width = l8h;
		break;
	case 9:
		tf_width = l9h;
		break;		
	}
	if($(e).hasClass('active_opt_comm'))
	{
		$('.reply_comment_cont').remove();
		$(e).removeClass('active_opt_comm');
	}
	else
	{
		$('.active_opt_comm').removeClass('active_opt_comm');
		$(e).addClass('active_opt_comm');
		hideNewCommentCont();
		$('.report_comment_cont').remove();
		$('.link_comment_cont').remove();
		$('.reply_comment_cont').remove();
		c_html='<div class="reply_comment_cont">';
		c_html+='<textarea  style="width:'+tf_width+';max-width:'+tf_width+';min-width:'+tf_width+';" ></textarea> ';
		c_html+='<p><span>add image...</span></p>';
		c_html+='<div class="new_com_submit_btn_cont">';
		c_html+='<div class="new_com_submit_btn">Save</div>';
		c_html+='</div>';
		c_html+='</div>';
		$('#'+comm_id).children('.comment').after(c_html);
	}
	return false;
}
/*Link comment*/
function linkComment(comm_id,comm_lvl,e){
	var c_html = '';
	var tf_width = '';
	switch(comm_lvl)
	{
	case 1:
		tf_width = l1h;
		break;
	case 2:
		tf_width = l2h;
		break;
	case 3:
		tf_width = l3h;
		break;
	case 4:
		tf_width = l4h;
		break;
	case 5:
		tf_width = l5h;
		break;
	case 6:
		tf_width = l6h;
		break;
	case 7:
		tf_width = l7h;
		break;	
	case 8:
		tf_width = l8h;
		break;
	case 9:
		tf_width = l9h;
		break;		
	}
	var c_link =window.location.host+window.location.pathname+'#'+comm_id;
	if($(e).hasClass('active_opt_comm'))
	{
		$('.link_comment_cont').remove();
		$(e).removeClass('active_opt_comm');
	}
	else
	{
		$('.active_opt_comm').removeClass('active_opt_comm');
		$(e).addClass('active_opt_comm');
		hideNewCommentCont();
		$('.report_comment_cont').remove();
		$('.reply_comment_cont').remove();
		$('.link_comment_cont').remove();
		c_html='<div class="link_comment_cont">';
		c_html+='<input type="text"  style="width:'+tf_width+';max-width:'+tf_width+';min-width:'+tf_width+';" value='+c_link+' />';
		c_html+='</div>';
		$('#'+comm_id).children('.comment').after(c_html);
	}
	return false;
}
/*Report comment*/
function reportComment(comm_id,comm_lvl,e){
	var c_html = '';
	var tf_width = '';
	switch(comm_lvl)
	{
	case 1:
		tf_width = l1h;
		break;
	case 2:
		tf_width = l2h;
		break;
	case 3:
		tf_width = l3h;
		break;
	case 4:
		tf_width = l4h;
		break;
	case 5:
		tf_width = l5h;
		break;
	case 6:
		tf_width = l6h;
		break;
	case 7:
		tf_width = l7h;
		break;	
	case 8:
		tf_width = l8h;
		break;
	case 9:
		tf_width = l9h;
		break;		
	}
	if($(e).hasClass('active_opt_comm'))
	{
		$('.report_comment_cont').remove();
		$(e).removeClass('active_opt_comm');
	}
	else
	{
		$('.active_opt_comm').removeClass('active_opt_comm');
		$(e).addClass('active_opt_comm');
		hideNewCommentCont();
		$('.link_comment_cont').remove();
		$('.reply_comment_cont').remove();
		$('.report_comment_cont').remove();
		c_html='<div class="report_comment_cont">';
		c_html+='<textarea  style="width:'+tf_width+';max-width:'+tf_width+';min-width:'+tf_width+';" ></textarea> ';
		c_html+='<p><span>add image...</span></p>';
		c_html+='<div class="new_com_submit_btn_cont">';
		c_html+='<div class="new_com_submit_btn">Save</div>';
		c_html+='</div>';
		c_html+='</div>';
		$('#'+comm_id).children('.comment').after(c_html);
	}
	return false;
}
function hideComment(comm_id){
	$('.active_opt_comm').removeClass('active_opt_comm');
	$('.link_comment_cont').remove();
	$('.reply_comment_cont').remove();
	$('.report_comment_cont').remove();
	c_html='<div class="comm_hidden">';
	c_html+='<p>Comment is hidden | <span onclick="hiddenCommShow(\''+comm_id+'\')">show</span></p>';
	c_html+='</div>';
	$('#'+comm_id).children('.comment').css('display','none').before(c_html);
	
	try 
	{
		if (localStorage.hiddenComments)
		{
			var commArray = localStorage.hiddenComments.split(":");
			var index = commArray.indexOf(comm_id);
			if (index == -1)
			{
				if(commArray.length > 100)
				{
					commArray.shift();
					commArray.push(comm_id);
					localStorage.hiddenComments = commArray.join(':');
				}
				else
				{
					localStorage.hiddenComments=localStorage.hiddenComments+':'+comm_id;
				}
			}			
			
		}
		else
		{
			localStorage.hiddenComments=comm_id;
		}	
	} 
	catch (e) 
	{
		if (e == QUOTA_EXCEEDED_ERR) {
			localStorage.removeItem('hiddenComments');
		}
	}	
}
function hiddenCommShow(comm_id){
	$('#'+comm_id).children('.comm_hidden').remove();
	$('#'+comm_id).children('.comment').css('display','block');
	var commArray = localStorage.hiddenComments.split(":");
	var index = commArray.indexOf(comm_id);
	if (index > -1)
	{
		commArray.splice(index, 1);
		localStorage.hiddenComments = commArray.join(':')
	}
}
function hideCommentsFromLS(){
	var comm_id;
	if (localStorage.hiddenComments)
	{
		commArray = localStorage.hiddenComments.split(":");
		$.each(commArray, function( index, value ) {
			comm_id = value;		
			c_html='<div class="comm_hidden">';
			c_html+='<p>Comment is hidden | <span onclick="hiddenCommShow(\''+comm_id+'\')">show</span></p>';
			c_html+='</div>';
			$('#'+comm_id).children('.comment').css('display','none').before(c_html);
		});			
	}
}
function getGifForCom(e){
	if($(e).parent().children('.img_src_data').data("imgSrc")!='')
	{	
		var src = $(e).parent().children('.img_src_data').data("imgSrc");
		$(e).parent().css('display','none');
		if ($(e).parent().parent().children('.c_img_wrap_r').length){
			$(e).parent().parent().children('.c_img_wrap_r').css('display','block');
		}
		else
		{			
			$(e).parent().parent().prepend('<div style="margin-top:10px;height:'+$(e).parent().height()+'px;width:'+$(e).parent().width()+'px;background-color:#F7F7F7;" class="c_img_wrap_r"><img onclick="hideGifForCom(this)" src="'+src+'" alt="" /></div>');
		}
	}
}
function hideGifForCom(e){
	$(e).parent().css('display','none');
	$(e).parent().parent().children('.c_img_wrap').css('display','block');
}
function selectSectionAll(e){
	$('.section_btn_cont').removeClass("section_btn_activ");
	$(e).parent().addClass("section_btn_activ");
	$('.sub_section_cont').children().remove();
}
function selectSectionFast(e){
	$('.section_btn_cont').removeClass("section_btn_activ");
	$(e).parent().addClass("section_btn_activ");
	$('.sub_section_cont').children().remove();
}
function selectSectionTop(e){
	$('.section_btn_cont').removeClass("section_btn_activ");
	$(e).parent().addClass("section_btn_activ");
	$('.sub_section_cont').children().remove();
	var str;
	str ='<p>';	
	str+='<span  onclick="topSubMenuShow(this)">24 hour </span>';
	str+='<div class="arrow_s" onclick="topSubMenuShow(this)"></div></p>';
	str+='<div class="top_per_select_c pop_up">';
	str+='<div onclick="topSubMenuHide(\'d\',this)"><p>24 hour</p></div>';
	str+='<div onclick="topSubMenuHide(\'w\',this)"><p>Week</p></div>';
	str+='<div onclick="topSubMenuHide(\'m\',this)"><p>Month</p></div>';
	str+='<div onclick="topSubMenuHide(\'a\',this)"><p>All time</p></div>';
	str+='</div>';				
	$('.sub_section_cont').html(str);
}
function topSubMenuShow(e){
	$('.top_per_select_c').fadeIn(1,function(){
		$(document).bind( 'click', function(){
			$('.pop_up').fadeOut(1);
			$(document).unbind( 'click');
		})	
	});	
}
function topSubMenuHide(p,e){
	if(p=='d')
	{
		$('.sub_section_cont p span').text('24 hour');
	}	
	if(p=='w')
	{
		$('.sub_section_cont p span').text('Week');
	}	
	if(p=='m')
	{
		$('.sub_section_cont p span').text('Month');
	}	
	if(p=='a')
	{
		$('.sub_section_cont p span').text('All time');		
	}	
}
function sebSectTopSelect(time,e){
	$('.sub_active').removeClass('sub_active');
	$(e).addClass('sub_active');
}