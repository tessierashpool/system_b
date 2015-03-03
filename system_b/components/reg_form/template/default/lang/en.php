<?
global $S_SETTINGS;
$MESS['REG_ERROR_LOGIN_EMPTY'] = 'Login field  must be filled.';
$MESS['REG_ERROR_LOGIN_2_SHORT'] = 'Your username must be at least '.$S_SETTINGS['login_min_len'].' characters.';
$MESS['REG_ERROR_LOGIN_2_LONG'] = 'Your username must be less than '.$S_SETTINGS['login_max_len'].' characters.';
$MESS['REG_ERROR_EMAIL_NOT_VALID'] = 'Please enter a valid email address.';
$MESS['REG_ERROR_PASS_2_LONG'] = 'Your password must be less than '.$S_SETTINGS['password_max_len'].' characters.';
$MESS['REG_ERROR_PASS_2_SHORT'] = 'Your password must be at least '.$S_SETTINGS['password_min_len'].' characters.';
$MESS['REG_ERROR_PASS_CONFIRM'] = "Password doesn\'t match confirmation.";
$MESS['REG_ERROR_USER_EXIST'] = 'User with this username already exist.';
$MESS['REG_ERROR_EMAIL_EXIST'] = 'User with this email already exist.';
$MESS['REG_ERROR_LOGIN_INVALID_CHAR'] = 'Invalid characters in username field.';
$MESS['REG_ERROR_CAPTCHA_EMPTY'] = 'Please enter the CAPTCHA code.';
$MESS['REG_ERROR_CAPTCHA_INVALID'] = 'The CAPTCHA code you entered is invalid. Please try again.';
$MESS['REG_ERROR_LOGIN_CREATE_FAIL'] = 'Account creation failed: Something went wrong!';
$MESS['REG_ERROR_EMAIL_SEND_FAIL'] = 'Sending of message failed: Something went wrong!';
$MESS['SIGN_ERROR_LOGIN_EMPTY'] = 'Please enter your username.';
$MESS['SIGN_ERROR_PASS_EMPTY'] = 'Please enter your password.';
$MESS['REG_ERROR_WRONG_SIGNIN'] = 'Your username or password is incorrect.';

$MESS['REG_FORM_LOGIN_T'] = 'Username:';
$MESS['REG_FORM_EMAIL_T'] = 'E-mail:';
$MESS['REG_FORM_PASS_T'] = 'Password:';
$MESS['REG_FORM_PASS_C_T'] = 'Confirm password:';
$MESS['REG_FORM_REG_BTN'] = 'create account';
$MESS['REG_FORM_LOG_BTN'] = 'sign in';

$MESS['REG_SUCCES_REG'] = 'Your account has been successfully created.';
$MESS['REG_SUCCES_REG_MAIL'] = 'Thank you for registering. Please check your email and use the enclosed link to finish registration.';
$MESS['REG_SUCCES_ACC_ACTIVE'] = 'Your account is now active.';

$MESS['REG_EMAIL_TITLE'] = 'Registration Confirmation';
$MESS['REG_EMAIL_TEXT'] = '
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