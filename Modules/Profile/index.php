<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if(isset($_GET['user_id']) || is_numeric($_GET['user_id'])){
	if(($profile = $MySQL->Fetch("SELECT username, user_avatar, user_level, user_viewemail, user_email, user_regdate, user_posts, user_yim, user_aim, user_msnm, user_icq, user_from, user_website, user_occ, user_interests, user_sig, user_group FROM ".$pre."users WHERE user_id = '".$_GET['user_id']."'")) !== false && $_GET['user_id'] >= 2 ){
		if(!empty($profile['user_avatar'])){
			$find_avatar = @fopen($profile['user_avatar'], "r");
			if($find_avatar == true){
				$size = @GetImageSize($profile['user_avatar']);
				$aspectRat = (float)($size[1] / $size[0]);
	
				if(($size[0] <= 50) && ($size[1] <= 50)){
					$avatar_img =  "<img src=\"".$profile['user_avatar']."\" alt=\"avatar\" width=\"".$size[0]."\" height=\"".$size[1]."\"/>";
				}else{
					$width_ratio = $size[0] / 50;
					$height_ratio = $size[1] / 50;
	
					if($width_ratio >= $height_ratio){
					  $newY = 50 * $aspectRat;
					  $newX = 50;
					}else{
					  $newY = 50;
					  $newX = 50 * (1/$aspectRat);
					}
					$avatar_img = "<img src=\"".$profile['user_avatar']."\" alt=\"avatar\" width=\"".$newX."\" height=\"".$newY."\"/>";
				}
			}else{
				$avatar_img = "[ X ]";
			}
		}else{
			$avatar_img = "";
		}	
		
		if(($group = $MySQL->Fetch("SELECT name FROM ".$pre."user_groups WHERE group_id = '".$profile['user_group']."'")) !== false){
			$user_group = $group['name']; 
		}else{
			switch($profile['user_level'])
			{
				case 99:
					$user_group =  _ADMIN;
				break;
		
				case 3:
					$user_group =  _MOD;
				break;
		
				case 2:
					$user_group =  _AP;
				break;
		
				default:
					$user_group =  _MEMBER;
				break;
			}
		}	
		if ($profile['user_viewemail'] == 1){
			$user_email = "<a href=\"mailto:".$profile['user_email']."\">".$profile['user_email']."</a>";
		}else{
			$user_email = _DONT_DISPLAY_EMAIL;
		}
		echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"40%\" valign=\"top\">
			<div style=\"margin-right: 5px;\">";
			$Table->Open("<strong>"._GENERAL_INFO."</strong>");
		echo "<table width=\"100%\"  border=\"0\" cellspacing=\"4\" cellpadding=\"0\">
		<tr><td><div style=\"text-align:left\">".$profile['username']."</div>
		</td></tr>
		<tr><td><div style=\"text-align:left\">".$avatar_img."</div>
		</td></tr>
		<tr><td><div style=\"text-align:left\">"._JOINED.": ".$profile['user_regdate']."</div>
		</td></tr>
		<tr><td><div style=\"text-align:left\">"._GROUP.": ".$user_group."</div>
		</td></tr>
		<tr><td><div style=\"text-align:left\">"._POST.": ".$profile['user_posts']."</div>
		</td></tr>
		</table>";	
			$profile['user_aim'] = trim($profile['user_aim']);
			if(!empty($profile['user_aim'])){
				$user_aim = $profile['user_aim'];
			}else{
				$user_aim = _NO_INFO;
			}
			$profile['user_yim'] = trim($profile['user_yim']);
			if(!empty($profile['user_yim'])){
				$user_yim = $profile['user_yim'];
			}else{
				$user_yim = _NO_INFO;
			}
			$profile['user_msnm'] = trim($profile['user_msnm']);
			if(!empty($profile['user_msnm'])){
				$user_msn = $profile['user_msnm'];
			}else{
				$user_msn = _NO_INFO;
			}
			$profile['user_icq'] = trim($profile['user_icq']);
			if(!empty($profile['user_icq'])){
				$user_icq = $profile['user_icq'];
			}else{
				$user_icq = _NO_INFO;
			}
			$Table->Close();
			echo "</div>
			</td>
			<td width=\"60%\" valign=\"top\">
			<div style=\"margin-left: 5px;\">";
			$Table->Open("<strong>"._CONTACT_INFO."</strong>");
			echo "<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
		  <tr>
			<td width=\"10%\" valign=\"top\" align=\"left\"><img src=\"".$base_url."/images/profile_aim.gif\" width=\"16\" height=\"14\"></td>
			<td width=\"90%\"><div style=\"text-align: left;\">".$user_aim."<div></td>
		  </tr>
		  <tr>
			<td align=\"left\" valign=\"top\"><img src=\"".$base_url."/images/profile_yahoo.gif\" width=\"16\" height=\"16\"></td>
			<td><div style=\"text-align: left;\">".$user_yim."<div></td>
		  </tr>
		  <tr>
			<td align=\"left\" valign=\"top\"><img src=\"".$base_url."/images/profile_msn.gif\" width=\"16\" height=\"15\"></td>
			<td><div style=\"text-align: left;\">".$user_msn."<div></td>
		  </tr>
		  <tr>
			<td align=\"left\" valign=\"top\"><img src=\"".$base_url."/images/profile_icq.gif\" width=\"16\" height=\"14\"></td>
			<td><div style=\"text-align: left;\">".$user_icq."</div></td>
		  </tr>
		  <tr>
			<td align=\"left\" valign=\"top\"><img src=\"".$base_url."/images/email.png\" width=\"16\" height=\"16\"></td>
			<td><div style=\"text-align: left;\">".$user_email."</div></td>
		  </tr>
		  <tr>
			<td align=\"left\" valign=\"top\"><img src=\"".$base_url."/images/email.png\" width=\"16\" height=\"16\"></td>
			<td><div style=\"text-align: left;\"><a href=\"index.php?find=Private%20Messages&amp;file=Send_Msgs&amp;To=".$profile['username']."\">"._SEND_A_PM."</a></div></td>
		  </tr>
		</table>";
			$Table->Close();
			echo "</div>
			</td>
		  </tr>
		</table><br>
		
		<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td>";
			$profile['user_from'] = trim($profile['user_from']);
			if(!empty($profile['user_from'])){
				$user_from = $profile['user_from'];
			}else{
				$user_from = _NO_INFO;
			}
			$profile['user_website'] = trim($profile['user_website']);
			if(!empty($profile['user_website'])){
				$user_homepage = "<a href=\"".$profile['user_website']."\">".$profile['user_website']."</a>";
			}else{
				$user_homepage = _NO_INFO;
			}
			$profile['user_occ'] = trim($profile['user_occ']);
			if(!empty($profile['user_occ'])){
				$user_occ = $profile['user_occ'];
			}else{
				$user_occ = _NO_INFO;
			}
			$profile['user_interests'] = trim($profile['user_interests']);
			if(!empty($profile['user_interests'])){
				$user_int = $profile['user_interests'];
			}else{
				$user_int = _NO_INFO;
			}
			$Table->Open("<strong>"._OTHER_INFO."</strong>");
			echo "<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
		  <tr>
			<td valign=\"top\"><div style=\"text-align:left\">"._LOCATION.": ".$user_from."</div></td>
		  </tr valign=\"top\">
		  <tr>
			<td valign=\"top\"><div style=\"text-align:left\">"._WEBSITE.": ".$user_homepage."</div></td>
		  </tr>
			<tr>
			<td valign=\"top\"><div style=\"text-align:left\">"._OCC.": ".$user_occ."</div></td>
		  </tr>
		  <tr>
			<td valign=top><div style=\"text-align:left\">"._INTERESTS.": ".$user_int."</div></td>
		  </tr>
		</table>";
		
			$Table->Close();
			echo "</td>
		  </tr>
		</table>
		<br>
		
		<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td valign=top>";
			$Table->Open("<strong>"._SIGNATURE."</strong>");
				if($Censor_Words == 1){
					$profile['user_sig'] = censor($profile['user_sig']);
				}
				if($Emoticon_On == 1){
					$profile['user_sig'] = emoticon($profile['user_sig']);
				}
				if($BBcode_On == 1){
					$profile['user_sig'] = bbcode($profile['user_sig']);
				}
			echo "<div style=\"text-align: left;\">".nl2br($profile['user_sig'])."</div>";
			$Table->Close();
			echo "</td>
		  </tr>
		</table>";
	}else{
		$Table->Open(_U_ERROR);
			echo _NO_USER_ID;
		$Table->Close();
	}
}else{
	$Table->Open("<strong>"._SELECT_A_USER."</strong>");
	echo "<form method=\"get\" action=\"index.php\">
	<input type=\"hidden\" name=\"find\" value=\"Profile\">
	<table width=\"100%\" border=\"0\">
	<tr>
	<td align=\"center\">
	<select name=\"user_id\">";
	$user_info = $MySQL->Query("SELECT user_id, username FROM ".$pre."users ORDER BY username ASC");
	while($usr_info = mysql_fetch_array($user_info)){
		if($usr_info['user_id'] == 1){
			echo "";
		}else{
			echo "<option value=\"".$usr_info['user_id']."\">".$usr_info['username']."</option>";
		}
	}
	echo "</select></td>
	</tr>
	<tr>
	<td align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>
	</tr>
	</table>
	</form>";
	$Table->Close();
}

?>
