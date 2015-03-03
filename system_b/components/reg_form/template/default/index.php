<?
	//msgLoader('Hello!!!!!','g');
?>
<script type="text/javascript">
	function regFldVerif()
	{
		msgBoxCloseAll();
		if($('#r_login').val().trim().length>parseInt(<?=$data['s']['login_max_len'];?>))
		{
			msgBox('<?=CMain::getM('REG_ERROR_LOGIN_2_LONG');?>','r');
			$('#r_login').focus();
			return false;
		}	
		if($('#r_login').val().trim().length<parseInt(<?=$data['s']['login_min_len'];?>))
		{
			msgBox('<?=CMain::getM('REG_ERROR_LOGIN_2_SHORT');?>','r');
			$('#r_login').focus();
			return false;
		}			
		if(!$('#r_login').val().match(/^[A-z0-9_]+$/i))
		{
			msgBox('<?=CMain::getM('REG_ERROR_LOGIN_INVALID_CHAR');?>','r');
			$('#r_login').focus();
			return false;			
		}

		<?if($data['s']['use_email']==1):?>
			if(!$('#r_email').val().match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/))
			{
				msgBox('<?=CMain::getM('REG_ERROR_EMAIL_NOT_VALID');?>','r');
				$('#r_email').focus();
				return false;
			}		
		<?endif;?>		
		if($('#r_password').val().trim().length>parseInt(<?=$data['s']['password_max_len'];?>))
		{
			msgBox('<?=CMain::getM('REG_ERROR_PASS_2_LONG');?>','r');
			$('#r_password').focus();
			return false;
		}	
		if($('#r_password').val().trim().length<parseInt(<?=$data['s']['password_min_len'];?>))
		{
			msgBox('<?=CMain::getM('REG_ERROR_PASS_2_SHORT');?>','r');
			$('#r_password').focus();
			return false;
		}		
		if($('#r_c_password').val()!=$('#r_password').val())
		{
			msgBox('<?=CMain::getM('REG_ERROR_PASS_CONFIRM');?>','r');
			$('#r_c_password').focus();
			return false;
		}	
		<?if($data['s']['use_captcha']=='1'):?>
			if($('.regist_in_cont .captch_input').val().trim()=='')
			{
				msgBox('<?=CMain::getM('REG_ERROR_CAPTCHA_EMPTY');?>','r');
				$('.regist_in_cont .captch_input').focus();
				return false;
			}		
		<?endif;?>
			
		var login = $('#r_login').val();
		login  = login .trim();
		$('#login_ajax_loader').removeClass('login_exist_ajax').addClass('login_ajax_loader').attr('title','');
		$.post( "", { ajax: '1', login: login }, function(data) 
		{
			return loginExistAjax(data);
		});			
			
		return true;
	}
	
	$(document).ready(function(){
		var lgn_tmp = '';
		$( ".a_r_input_login" ).on('change keyup',function() {
			var login = $(this).val().trim();
			if(lgn_tmp!=login)
			{
				$('#login_ajax_loader').removeClass('login_exist_ajax').removeClass('login_ajax_loader');
				lgn_tmp = login;
				if(login.length>parseInt(<?=$data['s']['login_min_len'];?>)-1)
				{
					$('#login_ajax_loader').removeClass('login_exist_ajax').addClass('login_ajax_loader').attr('title','');
					$.post( "", { ajax: '1', login: login }, function(data) 
					{
						loginExistAjax(data);
					});	
				}
			}	
		});
	})	
	
	//var login_exist = false;
	function loginExistAjax(e)
	{
		e = parseInt(e);
		if(e==1)
		{
			$('#login_ajax_loader').removeClass('login_ajax_loader').addClass('login_exist_ajax').css({'background-position':'-17px 0'}).attr('title','<?=CMain::getM('REG_ERROR_USER_EXIST');?>');
			//login_exist = true;
		}
		else
		{
			$('#login_ajax_loader').removeClass('login_ajax_loader').addClass('login_exist_ajax').css({'background-position':'0 0'}).attr('title','');
			//login_exist = false;
		}
	}	

	function signInFldVerif()
	{
		msgBoxCloseAll();
		if($('#s_login').val().trim()=='')
		{
			msgBox('<?=CMain::getM('SIGN_ERROR_LOGIN_EMPTY');?>','r');
			$('#s_login').focus();
			return false;
		}
		if($('#s_password').val().trim()=='')
		{
			msgBox('<?=CMain::getM('SIGN_ERROR_PASS_EMPTY');?>','r');
			$('#s_password').focus();
			return false;
		}	
		<?if(CUsersMain::isBadLoginer()):?>
			if($('.sign_in_cont .captch_input').val().trim()=='')
			{
				msgBox('<?=CMain::getM('REG_ERROR_CAPTCHA_EMPTY');?>','r');
				$('.sign_in_cont .captch_input').focus();
				return false;
			}		
		<?endif;?>		
			
		return true;
	}	
</script>

<div class="a_r_cont">
	<p class="a_r_line"><a onclick="srShow(this)" class="siClass">Sign in</a> | <a onclick="srShow(this)" class="rClass">Registration</a></p>
	<div class="sign_in_cont" >
		<form action="" method="post">
			<p class="a_r_title"><?=CMain::getM('REG_FORM_LOGIN_T');?></p>
			<p><input class="a_r_input" name="login" id="s_login" value="<?=$arData['s_login']?>"  type="text" /></p>
			<p class="a_r_title"><?=CMain::getM('REG_FORM_PASS_T');?></p>
			<p><input class="a_r_input" name="password" id="s_password" type="password" /></p>	
			<div class="checkbox_emul_cnt">
				<div class="checkbox_emul <?if($arData['stay_sigin']==1){echo 'checkbox_emul_checket';}?>" onclick="if($(this).next('input').val()=='0'){$(this).addClass('checkbox_emul_checket');$(this).next('input').val('1');}else{$(this).removeClass('checkbox_emul_checket');$(this).next('input').val('0');}"></div>
				<input type="hidden" name="stay_sigin" value="<?if($arData['stay_sigin']==1){echo '1';}else{echo '0';}?>" />
				<div class="checkbox_emul_t" >Stay signed in </div>
				<div style="clear:both"></div>		
			</div>
			<?
				if(CUsersMain::isBadLoginer())
					CMain::GC('captcha',array('CAPTCHA_NAME'=>'captcha_signin'));
			?>				
			<div class="s_i_btn_cont">
				<input type="submit" name="sign_in" class="c_a_btn" value="<?=CMain::getM('REG_FORM_LOG_BTN');?>" onClick="return signInFldVerif();" />
			</div>	
			<?if($data['s']['use_email']==1){CMain::GC('reg_form.password_restore');}?>
		</form>				
	</div>
	<div class="regist_in_cont">
		<form action="" method="post">	
			<p class="a_r_title"><?=CMain::getM('REG_FORM_LOGIN_T');?></p>
			<p><input  name="login" class="a_r_input a_r_input_login" id="r_login" type="text" value="<?=$arData['login']?>" /><div id="login_ajax_loader"></div><div  style="clear:both"></div></p>
			<?if($data['s']['use_email']==1):?>
				<p class="a_r_title"><?=CMain::getM('REG_FORM_EMAIL_T');?></p>
				<p><input name="email" class="a_r_input" id="r_email" type="text" value="<?=$arData['email']?>" /></p>		
			<?endif;?>
			<p class="a_r_title"><?=CMain::getM('REG_FORM_PASS_T');?></p>
			<p><input name="password" class="a_r_input" id="r_password" type="password" value="<?=$arData['password']?>" /></p>	
			<p class="a_r_title"><?=CMain::getM('REG_FORM_PASS_C_T');?></p>
			<p><input name="password_c" class="a_r_input" id="r_c_password" type="password" value="<?=$arData['password_c']?>" /></p>	
			<?
				if($data['s']['use_captcha']=='1')
					CMain::GC('captcha',array('CAPTCHA_NAME'=>'captcha_auth'));
			?>				
			<div class="s_i_btn_cont">
				<input type="submit" name="create_account" class="c_a_btn" value="<?=CMain::getM('REG_FORM_REG_BTN');?>" onClick="return regFldVerif();" />
			</div>		
		</form>				
	</div>	
</div>
<?if(isset($_REQUEST['create_account'])):?>
	<script type="text/javascript">
	srShow($('.rClass'));
	</script>
<?endif;?>
<?if(isset($_REQUEST['sign_in'])):?>
	<script type="text/javascript">
	srShow($('.siClass'));
	</script>
<?endif;?>