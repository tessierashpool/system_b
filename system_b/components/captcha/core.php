<?
CMain::includeClass('captcha.CCaptcha');
$data['CAPTCHA_NAME'] = $arParams['CAPTCHA_NAME'];
$data['captcha']=CCaptcha::getCaptcha($data['CAPTCHA_NAME']);
require('/template/'.$TEMPLATE_NAME.'/index.php');

?>