<?
$errors = array();
CMain::includeClass('users.CUsersMain');
if(isset($_COOKIE['reg_succes']))
{
	//reg_succes = 1, registration success
	//reg_succes = 2, reg. success, email send
	//reg_succes = 3, activation success
	$reg_succes = $_COOKIE['reg_succes'];
	setcookie ("reg_succes",  "", time() - 3600);
	if($reg_succes==1)
		msgLoader(CMain::getM('REG_SUCCES_REG'),'g');
	elseif($reg_succes==2)	
		msgLoader(CMain::getM('REG_SUCCES_REG_MAIL'),'g');
	elseif($reg_succes==3)	
		msgLoader(CMain::getM('REG_SUCCES_ACC_ACTIVE'),'g');		
}
if(isset($_REQUEST['ajax'])&&intval($_REQUEST['ajax'])==1&&isset($_REQUEST['login']))
{
	$login = safeStr($_REQUEST['login']);
	ob_end_clean();
	echo intval(CUsersMain::checkNewLogin($login));
	exit;
}
global $S_SETTINGS;
$data['s'] = $S_SETTINGS;
if(isset($_REQUEST['sign_in']))
{
	//Login field check
	//Проверка поля "логин"
	if (isset($_REQUEST['login']))
	{ 
		$login = safeStr($_REQUEST['login']);

		if ($login=='') 
		{
			$errors[]=CMain::getM('SIGN_ERROR_LOGIN_EMPTY');
		}	
	}
	else
	{
		$errors[]=CMain::getM('SIGN_ERROR_LOGIN_EMPTY');
	}

	//Password field check
	//Проверка поля "пароль"	
	if (isset($_REQUEST['password']))
	{ 
		$password = safeStr($_REQUEST['password']);
		if ($password=='') 
		{
			$errors[]=CMain::getM('SIGN_ERROR_PASS_EMPTY');
		}
		
		if(CUsersMain::isBadLoginer())
		{
			CMain::includeClass('captcha.CCaptcha');
			if(!CCaptcha::isRightCaptcha($_REQUEST['captcha_signin'],'captcha_signin'))
			{
				$errors[]=CMain::getM('REG_ERROR_CAPTCHA_INVALID');
			}	
		}		
	}
	else
	{
		$errors[]=CMain::getM('SIGN_ERROR_PASS_EMPTY');
	}	
	
	if(count($errors)==0)
	{
		if(intval($_REQUEST['stay_sigin'])==1)
		{
			if(CUsersMain::signInUser($login, $password, 1))
			{
				redirect(currentPage());
			}
			else
			{
				$errors[]=CMain::getM('REG_ERROR_WRONG_SIGNIN');
				errorLoader($errors);	
				$arData['s_login']=$login;
				$arData['stay_sigin']=intval($_REQUEST['stay_sigin']);				
			}			
		}
		else
		{
			if(CUsersMain::signInUser($login, $password))
			{
				redirect(currentPage());
			}
			else
			{
				$errors[]=CMain::getM('REG_ERROR_WRONG_SIGNIN');
				errorLoader($errors);	
				$arData['s_login']=$login;
				$arData['stay_sigin']=intval($_REQUEST['stay_sigin']);				
			}
		}
	}
	else
	{
		errorLoader($errors);
		$arData['s_login']=$login;
		$arData['stay_sigin']=intval($_REQUEST['stay_sigin']);
	}	
}
if(isset($_REQUEST['create_account']))
{
	//Login field check
	//Проверка поля "логин"
	if (isset($_REQUEST['login']))
	{ 
		$login = safeStr($_REQUEST['login']);

		if (strlen($login) < intval($data['s']['login_min_len'])) 
		{
			$errors[]=CMain::getM('REG_ERROR_LOGIN_2_SHORT');
		}	
		elseif ( strlen($login) > intval($data['s']['login_max_len'])) 
		{
			$errors[]=CMain::getM('REG_ERROR_LOGIN_2_LONG');
		}
		elseif(!CUsersMain::isValidLogin($login)) 
		{
			$errors[]=CMain::getM('REG_ERROR_LOGIN_INVALID_CHAR');
		}		
		elseif(CUsersMain::checkNewLogin($login))
		{
			$errors[]=CMain::getM('REG_ERROR_USER_EXIST');
		}	
	}
	else
	{
		$errors[]=CMain::getM('REG_ERROR_LOGIN_EMPTY');
	}
	//Email field check
	//Проверка поля "email"
	if($data['s']['use_email']==1)
	{
		
		if (isset($_REQUEST['email']))
		{ 
		
			$email = safeStr($_REQUEST['email']);
			if (!CUsersMain::isValidEmail($email)) 
			{
				$errors[]=CMain::getM('REG_ERROR_EMAIL_NOT_VALID');
			}
			elseif(CUsersMain::checkNewEmail($email))
			{
				$errors[]=CMain::getM('REG_ERROR_EMAIL_EXIST');
			}
		}
		else
		{

			$errors[]=CMain::getM('REG_ERROR_EMAIL_NOT_VALID');
		}	
	}
	//Password field check
	//Проверка поля "пароль"	
	if (isset($_REQUEST['password']))
	{ 
		$password = safeStr($_REQUEST['password']);
		if (strlen($password) < intval($data['s']['password_min_len'])) 
		{
			$errors[]=CMain::getM('REG_ERROR_PASS_2_SHORT');
		}	
		elseif ( strlen($password) > intval($data['s']['password_max_len'])) 
		{
			$errors[]=CMain::getM('REG_ERROR_PASS_2_LONG');
		}	
		
		//"Password confirm" field check
		//Проверка поля "повтор пароля"
		if(isset($_REQUEST['password_c']))	
		{
			$password_c = safeStr($_REQUEST['password_c']);
			if ($password != $password_c) 
			{
				$errors[]=CMain::getM('REG_ERROR_PASS_CONFIRM');
			}			
		}
		else
		{
			$errors[]=CMain::getM('REG_ERROR_PASS_CONFIRM');
		}
		
		if($data['s']['use_captcha']=='1')
		{
			CMain::includeClass('captcha.CCaptcha');
			if(!CCaptcha::isRightCaptcha($_REQUEST['captcha_auth'],'captcha_auth'))
			{
				$errors[]=CMain::getM('REG_ERROR_CAPTCHA_INVALID');
			}	
		}
	}	
	else
	{
		$errors[]=CMain::getM('REG_ERROR_PASS_2_SHORT');
	}
	
	if(count($errors)==0)
	{
		if($data['s']['use_email']==0)
		{
		//Registration without email
			if(CUsersMain::registNewUser($login,$password))
			{
				setcookie ("reg_succes", 1,time()+300);
				redirect(currentPage());
			}
			else
			{
				$errors[]=CMain::getM('REG_ERROR_LOGIN_CREATE_FAIL');
				errorLoader($errors);			
			}
		}
		else
		{
		//Registration with email
			$result = CUsersMain::registNewUser($login,$password,$email,0);
			if($result)
			{
				$email_title = CMain::getM('REG_EMAIL_TITLE');
				$email_text = CMain::getM('REG_EMAIL_TEXT');
				
				if(!CUsersMain::emailAccountActivation($login, $email_title, $email_text))
				{
					$errors[]=CMain::getM('REG_ERROR_EMAIL_SEND_FAIL');
					errorLoader($errors);
				}	
				else
				{
					setcookie ("reg_succes", 2,time()+300);
					redirect(currentPage());
				}
			}
			else
			{
				$errors[]=CMain::getM('REG_ERROR_LOGIN_CREATE_FAIL');
				errorLoader($errors);			
			}
		}
	}
	else
	{
		errorLoader($errors);
		$arData['password']=$password;
		$arData['password_c']=$password_c;
		$arData['login']=$login;
		if($data['s']['use_email']==1)
			$arData['email']=$email;
	}	
}
/*
if(CCache::startCache())
{
	while($i<5000000)
	{
		$i++;
	}*/
require('/template/'.$TEMPLATE_NAME.'/index.php');
//}

?>