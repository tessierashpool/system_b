<div class="first_cont">
<?
if($arData['isAuthorized'])
	CMain::GC('user_profile');
else
	CMain::GC('reg_form');
?>
</div>