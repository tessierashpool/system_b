$(document).ready(function(){
	//$('.a_list_menu_element_menu')
	$('.a_pic_cont_main img').click(function(e){e.stopPropagation()});
	zoomContDimrns();
	$('.a_g_zoom_imags_cont').mouseup(function(e){
		var container = $(".a_g_zoom_imags_cont");
		if (container.has(e.target).length ===0)
			checkZoomImg();
	});
	$('.a_video_info_cont').click(function(){
		if($(this).children('.a_video_check').attr('checked')=='checked')
		{
			$(this).children('.a_video_check').removeAttr('checked');
			$(this).children('.a_video_check_marker').css('display','none');
		}	
		else	
		{
			$(this).children('.a_video_check').attr('checked','checked');
			$(this).children('.a_video_check_marker').css('display','block');
		}	
	})
})
var oldHandlerToKeep = document.oncontextmenu;
$(document).mouseup(function (e)
{
	
	var zoom_cont = $(".a_g_zoom_cont");
	var main_place = $(".admin_sub_main_place");
	var container = $(".a_list_menu_element_menu");
	if( e.button == 2 )
	{ 
		if ((main_place.is(e.target)||main_place.has(e.target).length > 0)&&(!zoom_cont.is(e.target)&&zoom_cont.has(e.target).length ===0))
		{
			document.oncontextmenu = function() {return false;};
			container.css('display','block');		
			container.offset({top:e.pageY, left:e.pageX});
			return false; 	
		}	
		else
		{
			document.oncontextmenu = oldHandlerToKeep;
		}
	}
	else
	{
		if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0) // ... nor a descendant of the container
		{
			container.css('display','none');
		}	
	}
});
function checkImg(id)
{
	if($('.a_img_checkbox_'+id).attr('checked')=='checked')
		$('.a_img_checkbox_'+id).removeAttr('checked');
	else	
		$('.a_img_checkbox_'+id).attr('checked','checked');

}
//======================================Просмотр изображений==============================================
function zoomContDimrns()
{
	var win_weight = $(window).width();
	$('.a_g_zoom_imags_cont').width(win_weight-2-$('.a_g_zoom_btns_cont').width());
}	
var curImgNum = 0;
function zoomImg(imgNum)
{
	curImgNum = imgNum;
	$('.a_g_zoom_cont').css('display','block');
	$('.a_zoom_num_cont span').text(curImgNum);
	var imgSrg = $('.img_'+imgNum).attr('src');
	$('.a_g_zoom_imags_cont').html('<img onclick="zoomImgNext();" src="'+imgSrg+'" alt="" />');
	$('.a_zoom_open_in_wind').attr('href',imgSrg);
	$('.zoom_tmp_check').removeAttr('checked');
	if($('.a_img_check_for_zoom_'+imgNum).attr('checked')=='checked')
		$('.zoom_tmp_check').attr('checked','checked');
}
function zoomImgNext()
{
	$('.a_g_zoom_imags_cont img').remove();
	imgNum = parseInt(curImgNum) +1 ;
	if(imgNum == a_img_count)
		imgNum = 1;
	curImgNum = imgNum;
	$('.a_zoom_num_cont span').text(curImgNum);
	var imgSrg = $('.img_'+imgNum).attr('src');
	$('.a_g_zoom_imags_cont').html('<img onclick="zoomImgNext();" src="'+imgSrg+'" alt="" />');
	$('.a_zoom_open_in_wind').attr('href',imgSrg);
	$('.zoom_tmp_check').removeAttr('checked');
	if($('.a_img_check_for_zoom_'+imgNum).attr('checked')=='checked')
		$('.zoom_tmp_check').attr('checked','checked');		
}
function zoomImgPrev()
{
	$('.a_g_zoom_imags_cont img').remove();
	imgNum = parseInt(curImgNum) - 1 ;
	if(imgNum < 1)
		imgNum = a_img_count-1;
	curImgNum = imgNum;
	$('.a_zoom_num_cont span').text(curImgNum);
	var imgSrg = $('.img_'+imgNum).attr('src');
	$('.a_g_zoom_imags_cont').html('<img onclick="zoomImgNext();" src="'+imgSrg+'" alt="" />');
	$('.a_zoom_open_in_wind').attr('href',imgSrg);
	$('.zoom_tmp_check').removeAttr('checked');
	if($('.a_img_check_for_zoom_'+imgNum).attr('checked')=='checked')
		$('.zoom_tmp_check').attr('checked','checked');	
}
function checkZoomImg()
{
	if($('.zoom_tmp_check').attr('checked')=='checked')
	{
		$('.zoom_tmp_check').removeAttr('checked');
		$('.a_img_check_for_zoom_'+curImgNum).removeAttr('checked');
	}
	else
	{
		$('.zoom_tmp_check').attr('checked','checked');	
		$('.a_img_check_for_zoom_'+curImgNum).attr('checked','checked');	
	}
}
function checkZoomImgCheckbox(e)
{
	if($(e).attr('checked')=='checked')
	{
		$('.a_img_check_for_zoom_'+curImgNum).attr('checked','checked');	
	}
	else
	{
		$('.a_img_check_for_zoom_'+curImgNum).removeAttr('checked');	
	}
}
function zoomImgClose()
{
	$('.a_g_zoom_imags_cont img').remove();
	$('.a_g_zoom_cont').css('display','none');
}
