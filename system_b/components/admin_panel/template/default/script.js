function adminPanelHide(e)
{
	$(e).parent('.admin_panel_p').remove();
}
$(document).ready(function(){
	$('.a_panel_page_g_time').text($('#b_system_page_genreate_time').text());
})