<?
$page_gen_time_end = microtime(1);
echo "<div id='b_system_page_genreate_time' style='display:none'>".($page_gen_time_end  - $page_gen_time_start)."</div>";

CMain::deferredFunctions();
//CMain::getICList();
?>