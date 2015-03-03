$(document).ready(function(){
	//$('.a_list_menu_element_menu')

})

$(document).mouseup(function (e)
{
    var container = $(".a_list_menu_element_menu");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.remove();
    }
});
/*
function elementGetMenu(e)
{
	var ePosition = $(e).offset();
	//alert(ePosition.top +'####'+ ePosition.left);
	$('.a_list_menu_element_menu').offset({top:ePosition.top-3, left:ePosition.left+25});
}*/