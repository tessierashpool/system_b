<?
$errors = array();
global $S_SETTINGS;
$sql = "DELETE FROM `t_users` WHERE `active_confirmation`='0' AND `date_create`<".(time()-3600*intval($S_SETTINGS['activation_timer']))."";
$result = CDateBase::deleteQuery($sql);

if(isset($_REQUEST['code'])&&isset($_REQUEST['login']))
{
	$code = $_REQUEST['code'];
	$login = safeStr($_REQUEST['login']);
	
	$sql = "SELECT id FROM `t_users` WHERE `login`='$login' ";
	$result = CDateBase::selectQuery($sql);
	if($myrow = mysql_fetch_array($result))
	{
		$activation = md5($myrow['id']).md5($login);
		if($activation == $code)
		{
			$sql = "UPDATE `t_users` SET `active_confirmation`='1',`date_update`= ".time()." WHERE login='$login'";
			$result = CDateBase::updateQuery($sql);
			//reg_succes = 3, activation success
			setcookie ("reg_succes", 3,time()+300);	
			redirect('/new-comment.php');	
		}
		else
		{
			$errors[]=$MESS['EMAIL_ACTIV_ERROR_ACTIV'];	
		}
	}	
	else
		$errors[]=$MESS['EMAIL_ACTIV_ERROR_ACTIV'];	
}	
else
{
	$errors[]=$MESS['EMAIL_ACTIV_ERROR_ACTIV'];	
}	
if(count($errors)!=0)
{	
	errorLoader($errors);
}	
	
	
require('/template/'.$TEMPLATE_NAME.'/index.php');
?>