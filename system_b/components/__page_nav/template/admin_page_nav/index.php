<?
$page = $arParams['PAGE'];
$elements_on_page = $arParams['ELEMENTS_ON_PAGE'];
$arElementsOnPage=$arParams['ARR_ELEMENTS_ON_PAGE'];
$totalElements = CDateBase::totalCount();	

$first_page = 1;
$last_page = $arParams['TOTAL_PAGE'];

if(($first_page+2)>=$page)
{
	$PAGES=array(2,3,4);
}
elseif(($last_page-2)<=$page)	
{
	$PAGES=array($last_page-3,$last_page-2,$last_page-1);
}	
else
{
	$PAGES=array($page-1,$page,$page+1);
}

$left_dotes = FALSE;
$right_dotes = FALSE;

if(($PAGES[0]-$first_page)>1)
	$left_dotes = TRUE;
if(($last_page - $PAGES[2])>1)
	$right_dotes = TRUE;

foreach($PAGES as $key=>$page_s)
{
	if(($page_s>=$last_page)||($page_s<=$first_page))
		unset($PAGES[$key]);
}	

$from_element = ($page-1)*$elements_on_page + 1;
$to_element = $page*$elements_on_page;
if($to_element>$totalElements) 
	$to_element = $totalElements;
?>
<script type="text/javascript">
	function elentsOnPageChange(e)
	{
		switch(e.value) {
			<?foreach($arElementsOnPage as $elOnPage):?>
			case '<?=$elOnPage?>':
				window.location.href = '<?=currentPageWithParams(array('elements_on_page'=>$elOnPage),array('elements_on_page','success_msg','action','e_target','g_target'));?>';
				break;
			<?endforeach;?>
			default:
				window.location.href = '<?=currentPageWithParams(array('elements_on_page'=>$arElementsOnPage[0]),array('elements_on_page','success_msg','action','e_target','g_target'));?>';
		} 		
	}
</script>

<div class="a_page_nav_cont">
	<?if($arParams['TOTAL_PAGE']>1):?>
		<p><?=CMain::getM('T_PAGE')?></p>
		<a <?if($page==$first_page)echo"class='active'";?> href="<?=currentPageWithParams(array('page'=>$first_page),array('page','success_msg','action','e_target','g_target')); ?>"><?=$first_page?></a>
		<?
			if($left_dotes)
				echo "<span>...</span> ";
		
			foreach($PAGES as $page_s)
			{
				if($page==$page_s)
					echo '<a class="active" href="'.currentPageWithParams(array('page'=>$page_s),array('page','success_msg','action','e_target','g_target')).'">'.$page_s.'</a> ';
				else	
					echo '<a href="'.currentPageWithParams(array('page'=>$page_s),array('page','success_msg','action','e_target','g_target')).'">'.$page_s.'</a> ';
			}

			if($right_dotes)
				echo "<span>...</span>";
		?>
		
		<a <?if($page==$last_page)echo"class='active'";?> href="<?=currentPageWithParams(array('page'=>$last_page),array('page','success_msg','action','e_target','g_target')); ?>"><?=$last_page?></a>
	<?else:?>	
		<p><?=CMain::getM('T_PAGE')?></p>
		<a <?if($page==$first_page)echo"class='active'";?> href="<?=currentPageWithParams(array('page'=>$first_page),array('page','success_msg','action','e_target','g_target')); ?>"><?=$first_page?></a>
	<?endif;?>	
	<div class="a_page_nav_order"><p><?=CMain::getM('T_ELEMENTS_ON_PAGE')?></p><?CAdminPage::getDropList($arElementsOnPage,$arElementsOnPage,'elements_on_page',$elements_on_page,'','elentsOnPageChange')?></div>
	<?if($totalElements!=0):?>
		<div class="a_from_to_elements_on_page"> <?=$from_element?>-<?=$to_element?> (<?=CMain::getM('T_TOTAL')?> <?=$totalElements?>)</div>
	<?else:?>
		<div class="a_from_to_elements_on_page"><?=CMain::getM('T_NO_ELEMENTS')?></div>
	<?endif;?>
	</div>

