<div class="profile_cont">
<?global $USER;
$arUser = $USER->getInfo()
?>
	<p class="user_n_nameinfo"><?=$arUser['login']?></p>				
	<div class="user_a_cont">
		<div style="border:1px solid #fff;	">
			<img src="./images/avatar.png" alt="" />
		</div>
	</div>

	<div class="user_a_info">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td>Raiting:</td>
				<td>+2342</td>
			</tr>
			<tr valign="top">
				<td>With us:</td>
				<td>3 days</td>
			</tr>
			<tr valign="top">
				<td>Liked:</td>
				<td>
					<div class="r_liked">
						<p >345</p><div><div></div></div>
						<div style="clear:both;"></div>	
					</div>
				</td>
			</tr>
			<tr valign="top">
				<td>Disliked:</td>
				<td>
					<div class="r_disliked">
						<p >4321</p><div><div></div></div>
						<div style="clear:both;"></div>	
					</div>								
				</td>
			</tr>	
			<tr valign="top">
				<td>Posted:</td>
				<td>250</td>
			</tr>							
		</table>
	</div>
	<br />
	<br />

	<?/*<a href="/profile-settings.php" class="p_sett_line">Profile settings</a>*/?>
	<a href="?logout=1" class="logout_btn" title="Logout"></a>
</div>	