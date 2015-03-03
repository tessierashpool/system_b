<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_header.php');?>
<?
CMain::includeClass('users.CUsersMain');
CMain::includeClass('images.CGallery');
CMain::includeClass('images.CImages');
CMain::getLangFile();
global $USER;
if($USER->hr('clear_gallery')):

//Создание кнопок для страницы
$arAdminButtons = array();
$arAdminButtons[] = array(
	'type'=>'c',
	'value'=>CMain::getM('BTN_CLEAR'),
	'name'=>'a_clear_gallery',
	'class'=>'a_button_fancy a_clear_gallery'	
);
if(isset($_REQUEST['back_url'])&&$_REQUEST['back_url']!='')
{
	$arAdminButtons[] = array(
		'type'=>'l',
		'value'=>CMain::getM('BTN_CANCEL'),
		'link'=>$_REQUEST['back_url'],
		'class'=>'a_button_custom'	
	);
}
CAdminPage::adminButtons($arAdminButtons);

//===============================Обработка Ajax запроса=================================
$all_count = CGallery::size($_REQUEST['id']);
if(isset($_REQUEST['clear_ajax'])&&isset($_REQUEST['curent_id'])&&isset($_REQUEST['gallery_id'])&&isset($_REQUEST['curent_count']))
{
	$curent_id = intval($_REQUEST['curent_id']);
	$gallery_id = intval($_REQUEST['gallery_id']);
	$curent_count = intval($_REQUEST['curent_count']);
	$filter[] = array(
		"NAME"=>"id",
		"OPERATOR"=>">",
		"VALUE"=>$curent_id
	);		
	$filter[] = array(
		"NAME"=>"gallery_id",
		"OPERATOR"=>"=",
		"VALUE"=>$gallery_id
	);
	$imagesListResult = CImages::getList($filter);
	$start_time = time();
	$step = 1;
	
	while($arImage = mysql_fetch_array($imagesListResult))
	{

		CImages::delete($arImage['id']);
		$curent_count++;
		sleep(1);	
		if((time() - $start_time)>$step)
		{
			ob_get_clean();
				echo json_encode(array('curent_id' => $arImage['id'],'status' => 0, 'curent_count' => $curent_count));
			exit();
			
		}	
	}	
		
	ob_get_clean();
		echo json_encode(array('status' => 1));
	exit();
}
//================================================================Визуальная часть страницы=========================================================
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.a_clear_gallery').click(function(){
			showAjaxLoader();
			clearGallery();
		})
	})
	
	function clearGallery(c_id, c_count)
	{
		c_id = c_id || 0;
		c_count = c_count || 0;
		curent_id = parseInt(c_id);
		curent_count = parseInt(c_count);
		$.post( "", { clear_ajax: "1",curent_id: curent_id,gallery_id:<?=$_REQUEST['id']?>,curent_count:curent_count}, function( data ) {
			clearGalleryResult(data);			
		} );	
	}
	
	function clearGalleryResult(data)
	{
		hideAjaxLoader();
		var result = JSON.parse(data);
		if(result.status==1)
		{
			$('.a_gallery_clear_cont progress').val(100);
			$('#progress_perc').text(100);
			$('#deleted_count').text(<?=$all_count?>);			
		}	
		else	
		{
			showAjaxLoader();
			curent_id = parseInt(result.curent_id);
			curent_count = parseInt(result.curent_count);
			percent_complite = curent_count*100/parseInt(<?=$all_count?>);
			$('.a_gallery_clear_cont progress').val(percent_complite);
			$('#progress_perc').text(percent_complite);
			$('#deleted_count').text(curent_count);
			clearGallery(curent_id, curent_count);
		}	
	}	
</script>
<?CAdminPage::setPageTitleIcon('gallery_clear.gif');?>
<?CAdminPage::pageTitle(CMain::getM('PAGE_TITLE'),CMain::getM('PAGE_DESCRIPTION'))?>

<?CAdminPage::startPage()?>
	<div class="a_gallery_clear_cont">
		<p>Всего изображений: <span id="all_count"><?=$all_count?></span></p>
		<p>Удалено: <span id="deleted_count"></span></p>
		<p>Прогресс <span id="progress_perc"></span>%</p><br />
		<progress max="100" value="0"></progress>
	</div>
			
<?CAdminPage::endPage()?>
<?else:?>
<?CAdminPage::youDontHaveRight(CMain::getM('DONT_HAVE_RIGHT'))?>
<?endif;?>
<?require_once($_SERVER["DOCUMENT_ROOT"].'/system_b/admin_lair/a_footer.php');?>