<?
CMain::includeClass('users.CUsersMain');
if(isset($_REQUEST['fp_ajax'])&&$_REQUEST['fp_ajax']=='1'&&isset($_REQUEST['email_adress'])&&$_REQUEST['email_adress']!='')
{
	$email_title = $MESS['REG_FORM_R_P_EMAIL_TITLE'];
	$email_text = $MESS['REG_FORM_R_P_EMAIL_BODY'];
	CUsersMain::fpEmailSend($_REQUEST['email_adress'],$email_title,$email_text);
}
if(CCache::startCache())
{
require('/template/'.$TEMPLATE_NAME.'/index.php');
}
?>