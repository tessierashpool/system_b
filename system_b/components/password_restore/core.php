<?
$errors = array();
CMain::includeClass('users.CUsersMain');
global $S_SETTINGS;
$data['s'] = $S_SETTINGS;
if(isset($_REQUEST['p_r_btn_save']))
{	
	if(isset($_REQUEST['a'])&&isset($_REQUEST['b'])&&isset($_REQUEST['c'])&&isset($_REQUEST['d']))
	{
		if(isset($_REQUEST['pr_new_password']))
		{
			$user_id = intval($_REQUEST['a']);
			$hash = safeStr($_REQUEST['b']);
			$password = safeStr($_REQUEST['pr_new_password']);
			if (strlen($password) < intval($data['s']['password_min_len'])) 
			{
				$errors[]=$MESS['PR_ERROR_PASS_2_SHORT'];
			}	
			elseif ( strlen($password) > intval($data['s']['password_max_len'])) 
			{
				$errors[]=$MESS['PR_ERROR_PASS_2_LONG'];
			}	
			
			//"Password confirm" field check
			//Проверка поля "повтор пароля"
			if(isset($_REQUEST['pr_new_password_confirm']))	
			{
				$password_c = safeStr($_REQUEST['pr_new_password_confirm']);
				if ($password != $password_c) 
				{
					$errors[]=$MESS['PR_ERROR_PASS_CONFIRM'];
				}			
			}
			else
			{
				$errors[]=$MESS['PR_ERROR_PASS_CONFIRM'];
			}
			
			CMain::includeClass('captcha.CCaptcha');
			if(!CCaptcha::isRightCaptcha($_REQUEST['captcha_restore_pass'],'captcha_restore_pass'))
			{
				$errors[]=$MESS['PASS_RESORE_ERROR_CAPTCHA_INVALID'];
			}			
		}
		else
		{
			$errors[]=$MESS['PR_ERROR_PASS_2_SHORT'];
		}
	
	}	
	else
	{
		$errors[]=$MESS['PASS_RESORE_WRONG_LINK'];
	}
	
	if(count($errors)==0)
	{
		if(CUsersMain::resetPassword($user_id, $hash, $password))
		{
			$data["SUCCESS_RESET"] = 1;
		}
		else
		{
			$errors[]=$MESS['PR_ERROR_SGW'];
			errorLoader($errors);
		}
	}
	else
	{
		errorLoader($errors);
	}	
}
require('/template/'.$TEMPLATE_NAME.'/index.php');
?>