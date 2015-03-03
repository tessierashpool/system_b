$(document).ready(function(){
	adminMWorkPlaceDim();
	//floatingSubContFooter();		
	$('.admin_sub_right_column').css('min-width',$('.admin_sub_right_column').width());	
    $('.footer_buttons_cont').css({
        'left': $('.admin_sub_menu_cont').width()+6 
    });
    $('body').css({
        'width': $(document).width()
    });	
	menuLenghtCorrect();
})
$(document).scroll(function(e) {
    $('.footer_buttons_cont').css({
        'left': $('.admin_sub_menu_cont').width()+6 - $(document).scrollLeft()
    });
});
$( window ).resize(function(){
	adminMWorkPlaceDim();
	//floatingSubContFooter();			
})
function globalMenuSelectLink(link,e)
{	
	$('.admin_sub_menu div').css('display','none');
	$('#'+link).css('display','block');
	$('.admin_global_btn').removeClass('active');
	$('#'+e).addClass('active');
}

function adminMWorkPlaceDim()
{
	var offset_w = $('.admin_sub_main_place').offset();
	var w_height =  $(window).height();
	var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
	if(is_chrome)
	{
		$('.admin_sub_main_place').css('min-height',w_height);
	}
	else
	{
		$('.admin_sub_main_place').css('min-height',w_height-offset_w.top-22-parseInt($('.admin_sub_main_place').css('padding-bottom')));
	}
}
/*
function floatingSubContFooter()
{
	var m_c_margint = parseInt($('.admin_sub_main_place').css('margin-left'));
	var footer_buttons_l_padding = parseInt($('.footer_buttons_cont').css('padding-left'));
	var footer_buttons_r_padding = parseInt($('.footer_buttons_cont').css('padding-right'));
	var m_c_height = $('.admin_sub_right_column').width();
	$('.footer_buttons_cont').width(m_c_height-m_c_margint-footer_buttons_l_padding-footer_buttons_r_padding-1);
}*/

function selectTab(tabId,e)
{
	$('.a_tab').css('display','none');
	$('.a_tab_id_'+tabId).show();
	$('.a_tab_btn').removeClass('a_tab_div_active');
	$(e).addClass('a_tab_div_active');
}

function showAjaxLoader()
{
	$('.a_loader').css('display','block');
}

function hideAjaxLoader()
{
	$('.a_loader').css('display','none');
}

//===========================Menu Length Correct====================================
function menuLenghtCorrect()
{
	$('.admin_sub_menu a').mouseover(function(){
		$( ".a_too_longMenuTitle_wraper a" ).remove();
		var spanOffset = $(this).children('span').offset();
		var spanWidth = $(this ).children('span').width();
		var adminMWidth = $('.admin_sub_menu_cont').width();
		//alert(spanOffset.left + spanHeight);
		if((spanOffset.left + spanWidth)>parseInt(adminMWidth))
		{			
			var parentAOffset = $(this).offset();
			$(this).clone().appendTo( ".a_too_longMenuTitle_wraper" );
			$( ".a_too_longMenuTitle_wraper" ).offset({ top: parentAOffset.top, left: parentAOffset.left });
		}	
	});

	$('.a_too_longMenuTitle_wraper').mouseleave(
		function(){
			$( ".a_too_longMenuTitle_wraper a" ).remove();
		}	
	);
}/*
function toLongTitle()
{
	$('.toLongTitle').mouseover(
		function(){
			var parentAOffset = $(this).offset();
			$(this ).clone().appendTo( ".a_too_longMenuTitle_wraper" );
			$( ".a_too_longMenuTitle_wraper" ).offset({ top: parentAOffset.top, left: parentAOffset.left });
		}
	);
	$('.a_too_longMenuTitle_wraper').mouseleave(
		function(){
			$( ".a_too_longMenuTitle_wraper a" ).remove();
		}	
	);	
}*/