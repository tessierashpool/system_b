<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('videos.CVideos');
CMain::getLangFile();
global $USER;
if($USER->hr('video_add_to_gallery')&&$USER->hvgr(intval($_REQUEST['id']))):

//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'s',
	'value'=>CMain::getM('BTN_SAVE'),
	'name'=>'a_save_video_btn',
	'class'=>'a_button_fancy'	
);
if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
{
	$arAdminButtons[] = array(
		'type'=>'l',
		'value'=>CMain::getM('BTN_BACK'),
		'link'=>$_REQUEST['back_url'],
		'class'=>'a_button_custom'	
	);
}

CAdminPage::adminButtons($arAdminButtons);
//==============================================Обработка запроса========================================================
if(isset($_REQUEST['a_save_video_btn']))
{
	$url = safeStr($_REQUEST['url']);
	if($url=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_URL_EMPTY'));
	}	
	
	$url_video_id = safeStr($_REQUEST['url_video_id']);
	if($url_video_id=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_URL_VIDEO_ID_EMPTY'));
	}
	
	$name = safeStr($_REQUEST['name']);
	if($name=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_NAME_EMPTY'));
	}		

	$source = safeStr($_REQUEST['source']);
	if($source=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_SOURCE_EMPTY'));
	}		

	$gallery_id = intval($_REQUEST['id']);
	if($gallery_id=='')
	{
		CAdminPage::errorMsg(CMain::getM('ERROR_GROUP_ID_EMPTY'));
	}	
	
	if(!CAdminPage::haveErrors())
	{
		global $USER;
		$arFields['url'] = $url;
		$arFields['url_video_id'] = $url_video_id;
		$arFields['name'] = $name;
		$arFields['active'] = 'Y';
		$arFields['source'] = $source;
		$arFields['gallery_id'] = $gallery_id;
		$arFields['moderator_id'] = $USER->getId();
		if(CVideos::add($arFields))
		{
			CAdminPage::successMsg(CMain::getM('VIDEO_ADDED1').$name.CMain::getM('VIDEO_ADDED2'));
			if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
				redirect(transformUrlWithParams($_REQUEST['back_url'],array('success_msg'=>CMain::getM('VIDEO_ADDED1').$name.CMain::getM('VIDEO_ADDED2'))));
		}
	}
}
//================================================================Визуальная часть страницы=========================================================
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#new_post_vid_url').bind('input', function() { 
		setTimeout(function () {
		newPostLoadVideo();
		},250);
	}).keyup(function() {
		setTimeout(function () {
		newPostLoadVideo();
		},250);
	});
});	

function newPostLoadVideo(){
	vidObj = $('#new_post_vid_url');
	vidObj.next().remove();
	newVidUrl = $('#new_post_vid_url').val().trim();	
	if(parse_url = youtube_parser(newVidUrl))
	{
		$('#video_source').val('youtube');
		$('#url_video_id').val(parse_url);
		vidUrl = 'https://www.youtube.com/v/'+parse_url+'?version=3';
		vidObjText = '<div class="a_video_cont"><object width="600" height="337">'
		vidObjText += '<param name="movie" value="'+vidUrl+'"></param>'
		vidObjText += '<param name="allowFullScreen" value="true"></param>'
		vidObjText += '<param name="allowScriptAccess" value="always"></param>'
		vidObjText += ' <embed src="'+vidUrl+'" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="600" height="337"></embed>'
		vidObjText += '</object>'
		vidObjText += '<div class="a_video_clear_btn" onclick="clearNewVideoNP()">clear</div></div>'
	}
	else
	{
		vidObjText = "<div class='video_load_error_msg'>Can't load video perhaps the link is invalid</div>";
	}
	if(newVidUrl!='')
		vidObj.after(vidObjText);	
}

function clearNewVideoNP(){
	$('#video_source').val('');
	vidObj = $('#new_post_vid_url');
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
</script>
<?CAdminPage::setPageTitleIcon('add_video_to_gallery.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>
<?CAdminPage::startPage()?>
<?//http://www.youtube.com/watch?v=FhT14ZSJuw4 ?>
	<div class="a_add_video_cont new_pos_video_cont">
		<p><?=CMain::getM('T_VIDEO_NAME')?></p>
		<input name="name" type="text" value="<?=$_REQUEST['name']?>" />
		<input type="hidden" id="video_source" value="<?=$_REQUEST['source']?>" name="source" />
		<input type="hidden" id="url_video_id" value="<?=$_REQUEST['url_video_id']?>" name="url_video_id" /><br />
		<p><?=CMain::getM('T_VIDEO_ADRESS')?></p>
		<input id="new_post_vid_url" name="url"type="text" value="<?=$_REQUEST['url']?>" />
	</div>
<?CAdminPage::endPage()?>
<?else:?>
	<?if(!$USER->hr('video_add_to_gallery')):?>
		<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
	<?else:?>
		<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT_TO_GALLERY'))?>
	<?endif;?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>