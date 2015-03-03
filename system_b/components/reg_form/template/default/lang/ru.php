<?
global $S_SETTINGS;
$M['REG_ERROR_LOGIN_EMPTY'] = 'Login field  must be filled.';
$M['REG_ERROR_LOGIN_2_SHORT'] = 'Your username must be at least '.$S_SETTINGS['login_min_len'].' characters.';
$M['REG_ERROR_LOGIN_2_LONG'] = 'Your username must be less than '.$S_SETTINGS['login_max_len'].' characters.';
$M['REG_ERROR_EMAIL_NOT_VALID'] = 'Please enter a valid email address.';
$M['REG_ERROR_PASS_2_LONG'] = 'Your password must be less than '.$S_SETTINGS['password_max_len'].' characters.';
$M['REG_ERROR_PASS_2_SHORT'] = 'Your password must be at least '.$S_SETTINGS['password_min_len'].' characters.';
$M['REG_ERROR_PASS_CONFIRM'] = "Password doesn\'t match confirmation.";
$M['REG_ERROR_USER_EXIST'] = 'User with this username already exist.';
$M['REG_ERROR_EMAIL_EXIST'] = 'User with this email already exist.';
$M['REG_ERROR_LOGIN_INVALID_CHAR'] = 'Invalid characters in username field.';
$M['REG_ERROR_CAPTCHA_EMPTY'] = 'Please enter the CAPTCHA code.';
$M['REG_ERROR_CAPTCHA_INVALID'] = 'The CAPTCHA code you entered is invalid. Please try again.';
$M['REG_ERROR_LOGIN_CREATE_FAIL'] = 'Account creation failed: Something went wrong!';
$M['REG_ERROR_EMAIL_SEND_FAIL'] = 'Sending of message failed: Something went wrong!';
$M['SIGN_ERROR_LOGIN_EMPTY'] = 'Please enter your username.';
$M['SIGN_ERROR_PASS_EMPTY'] = 'Please enter your password.';
$M['REG_ERROR_WRONG_SIGNIN'] = 'Your username or password is incorrect.';

$M['REG_FORM_LOGIN_T'] = 'Username:';
$M['REG_FORM_EMAIL_T'] = 'E-mail:';
$M['REG_FORM_PASS_T'] = 'Password:';
$M['REG_FORM_PASS_C_T'] = 'Confirm password:';
$M['REG_FORM_REG_BTN'] = 'create account';
$M['REG_FORM_LOG_BTN'] = 'sign in';

$M['REG_SUCCES_REG'] = 'Your account has been successfully created.';
$M['REG_SUCCES_REG_MAIL'] = 'Thank you for registering. Please check your email and use the enclosed link to finish registration.';
$M['REG_SUCCES_ACC_ACTIVE'] = 'Your account is now active.';

$M['REG_EMAIL_TITLE'] = 'Registration Confirmation';
$M['REG_EMAIL_TEXT'] = '
<html>
    <head>
        <title>Registration Confirmation</title>
    </head>
    <body >
        <p>Hello {LOGIN}. 
		Thank you for registering with Random-box.</p>
		<p>To activate your account, please click on this link: 
		<a href="{LINK}">{LINK}</a>
		</p>
    </body>
</html>';
?>