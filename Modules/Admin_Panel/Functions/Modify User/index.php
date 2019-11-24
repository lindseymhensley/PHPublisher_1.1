<?php
/*******************************************************************
 **
 ** Admin File: Modify User/index.php
 ** Description: Modify just about everything about a user here
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}

if(isset($_GET['delete'])){
	if(isset($_GET['confirm'])){
		if(isset($_GET['user_id']) && is_numeric($_GET['user_id'])){
			$ui_i = $MySQL->Fetch("SELECT user_id, root_admin, username FROM ".$pre."users WHERE user_id = '".$_GET['user_id']."'");
			if($ui_i === FALSE){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User");
				die();
			}
			if($ui_i['root_admin'] != 1 && $ui_i['user_id'] != 1){
				$MySQL->Query("DELETE FROM ".$pre."users WHERE user_id = '".$ui_i['user_id']."' LIMIT 1");
				header("location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User&success=5&username=".$ui_i['username']);
				die();
			}else{
				header("location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User&success=4");
				die();
			}
		}else{
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User");
			die();
		}
	}else{
		if(isset($_GET['user_id']) && is_numeric($_GET['user_id'])){
			$ui_i = $MySQL->Fetch("SELECT user_id, root_admin FROM ".$pre."users WHERE user_id = '".$_GET['user_id']."'");
			if($ui_i === FALSE){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User");
				die();
			}
			if($ui_i['root_admin'] != 1 && $ui_i['user_id'] != 1){
				echo _DELETE_USER_SURE."<br /><a href=\"index.php?find=Admin_Panel&amp;func=Modify User&amp;delete=1&amp;confirm=1&amp;user_id=".$ui_i['user_id']."\">"._YES."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Modify User\">"._NO."</a><br /><br />";
			}else{
				header("location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User&success=4");
				die();
			}
		}else{
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User");
			die();
		}
	}
}

if($_POST['Update'] == 1){
	if($_POST['user'] == 1){
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User");
		die();
	}
	if(!empty($_POST['email'])){
		if(strpos($_POST['email'], '@')){
			function str_ills($string, $type){
				if($type == 1){
					return htmlspecialchars($string);
				}elseif($type == 2){
					return addslashes($string);
				}elseif($type == 3){
					return htmlspecialchars(addslashes($string));
				}
			}
			$ui_i = $MySQL->Fetch("SELECT user_id, root_admin FROM ".$pre."users WHERE user_id = '".$_POST['user']."'");
			if($ui_i === FALSE){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User");
				die();
			}
			if($ui_i['root_admin']){
				$MySQL->Query("UPDATE ".$pre."users SET user_email = '".$_POST['email']."', user_website = '".str_ills($_POST['website'], 3)."', user_avatar = '".str_ills($_POST['avatar'], 3)."', user_icq = '".str_ills($_POST['icq'], 3)."', user_occ = '".str_ills($_POST['occ'], 3)."', user_from = '".str_ills($_POST['from'], 3)."', user_interests = '".str_ills($_POST['interests'], 3)."', user_sig = '".str_ills($_POST['sig'], 3)."', user_aim = '".str_ills($_POST['aim'], 3)."', user_yim = '".str_ills($_POST['yim'], 3)."', user_msnm = '".str_ills($_POST['msnm'], 3)."', user_group='".$_POST['group']."' WHERE user_id = '".$_POST['user']."'");
			}else{
				$MySQL->Query("UPDATE ".$pre."users SET user_email = '".$_POST['email']."', user_website = '".str_ills($_POST['website'], 3)."', user_avatar = '".str_ills($_POST['avatar'], 3)."', user_icq = '".str_ills($_POST['icq'], 3)."', user_occ = '".str_ills($_POST['occ'], 3)."', user_from = '".str_ills($_POST['from'], 3)."', user_interests = '".str_ills($_POST['interests'], 3)."', user_sig = '".str_ills($_POST['sig'], 3)."', user_aim = '".str_ills($_POST['aim'], 3)."', user_yim = '".str_ills($_POST['yim'], 3)."', user_msnm = '".str_ills($_POST['msnm'], 3)."', user_group='".$_POST['group']."', user_level='".$_POST['lvl']."' WHERE user_id = '".$_POST['user']."'");
			}
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User&success=1&user=".$_POST['name']);
			die();
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User&success=3&user=".$_POST['name']);
			die();
		}
	}else{
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User&success=2&user=".$_POST['name']);
		die();
	}
}

if(isset($_POST['Submit'])){
	if($_POST['user'] == 1){
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modify User");
		die();
	}
	$ui = $MySQL->Fetch("SELECT username, user_email, user_website, user_avatar, user_occ, user_from, user_interests, user_sig, user_icq, user_aim, user_yim, user_msnm, root_admin, user_id, user_level, last_ip, user_group FROM ".$pre."users WHERE user_id = '".$_POST['user']."'");
	echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Modify User\">"._RETURN_TO_UA_PAGE."</a></div>";
	$Table->Open("<strong>".$ui['username']._USER_PROFILE_INFO."</strong>");
	echo " <br />
	<form method=\"post\" action=\"index.php?find=Admin_Panel&amp;func=Modify User\">
	<input type=\"hidden\" name=\"Update\" value=\"1\">
	<input type=\"hidden\" name=\"user\" value=\"".$_POST['user']."\">
	<input type=\"hidden\" name=\"name\" value=\"".$ui['username']."\">
	<Table width=100% cellspacing=0 border=0 align=center>
	<tr>
		<td width=\"45%\" align=right>"._EMAIL ." ("._REQUIRED ."):</td>
		<td width=\"55%\" align=left><input type=\"text\" value=\"". $ui['user_email'] ."\" name=\"email\"></td>
	</tr>
	<tr>
		<td  align=right>"._HOMEPAGE .":</td>
		<td align=left><input type=\"text\" value=\"". $ui['user_website'] ."\" name=\"website\"></td>
	</tr>
	<tr>
		<td  align=right>"._AVATAR .": ("._WEBL.")</td>
		<td align=left><input type=\"text\" value= \"". $ui['user_avatar'] ."\" name=\"avatar\"> "._MAX_SIZE."</td>
	</tr>
	
	<tr>
		<td  align=right>"._OCCUPATION .":</td>
		<td align=left><input type=\"text\" value= \"". $ui['user_occ'] ."\" name=\"occ\"></td>
	</tr>
	<tr>
		<td  align=right>"._FROM .":</td>
		<td align=left><input type=\"text\" value= \"". $ui['user_from'] ."\" name=\"from\"></td>
	</tr>
	<tr>
		<td  align=right valign=top>"._INTERESTS.":</td>
		<td align=left><textarea name=\"interests\" cols=\"50\" rows=\"5\">". $ui['user_interests'] ." </textarea></td>
	</tr>
	<tr>
		<td  align=right valign=top>"._SIGNATURE.":</td>
		<td align=left><textarea name=\"sig\"  cols=\"50\" rows=\"5\">". $ui['user_sig'] ." </textarea></td>
	</tr>
	<tr>
		<td  align=right>"._ICQ .":</td>
		<td align=left><input type=\"text\" value= \"". $ui['user_icq'] ."\" name=\"icq\"></td>
	</tr>
	<tr>
		<td  align=right>"._AIM .":</td>
		<td align=left><input type=\"text\" value= \"". $ui['user_aim'] ."\" name=\"aim\"></td>
	</tr>
	<tr>
		<td  align=right>"._YIM .":</td>
		<td align=left><input type=\"text\" value= \"". $ui['user_yim'] ."\" name=\"yim\"></td>
	</tr>
	<tr>
		<td  align=right>"._MSN .":</td>
		<td align=left><input type=\"text\" value= \"". $ui['user_msnm'] ."\" name=\"msnm\"></td>
	</tr>
	</table>";
	$Table->Close();
	
	if(($ui['root_admin'] == 0) || ($ui['user_id'] == $user->id())){
		echo "<br />";
		if($user->lvl() == 99){
			$Table->Open("<strong>"._ACC_LVLS."</strong>");
			echo "<Table width=100% cellspacing=0 border=0 align=center>
			<tr>
				<td align=right width=\"45%\">"._ACC_LVL.": </td>
			<td width=\"55%\" align=left>
			<select name=\"lvl\">
				<option value=\"1\" "; if($ui['user_level'] == 1) echo "selected=\"selected\""; echo">1 ("._REGULAR_MEMBERS.")</option>
				<option value=\"2\" "; if($ui['user_level'] == 2) echo "selected=\"selected\""; echo">2 ("._ARTICLE_PUBLISHER.")</option>
				<option value=\"3\" "; if($ui['user_level'] == 3) echo "selected=\"selected\""; echo">3 ("._MODERATOR.")</option>
				<option value=\"99\" "; if($ui['user_level'] == 99) echo "selected=\"selected\""; echo">99 ("._ADMIN.")</option>
			</select></td>
			</tr>
			<tr>
			<td align=right>"._GROUP.":</td>
			<td align=left><select name=\"group\">
			<option value=\"0\" "; if($ui['user_group'] == 0) echo "selected=\"selected\""; echo">"._NO_GROUP."</option>";
			$group_sql = $MySQL->Query("SELECT group_id, name FROM ".$pre."user_groups");
			while($group = mysql_fetch_array($group_sql)){
				echo "<option value=\"".$group['group_id']."\" "; if($ui['user_group'] == $group['group_id']) echo "selected=\"selected\""; echo">".$group['name']."</option>";
			}
			echo "</select></td>
			</tr>
			</table>";
			$Table->Close();
		}
		echo "<br />";
		$Table->Open("<strong>"._MISC_USER_INFO."</strong>");
		echo "<Table width=100% cellspacing=0 border=0 align=center>
		<tr>
			<td width=\"45%\" align=right>"._LAST_IP .": </td>
			<td width=\"55%\" align=left> <strong>".$ui['last_ip']."</strong></td>
		</tr>
		</table>";
		$Table->Close();
		echo "<br />";	
		$Table->Open("<strong>"._DELETE_USER."</strong>");
		echo "<Table width=100% cellspacing=0 border=0 align=center>
		<tr>
			<td colspan=2 align=center> <strong><a href=\"index.php?find=Admin_Panel&amp;func=Modify User&amp;username=".$ui['username']."&amp;delete=1&amp;user_id=".$ui['user_id']."\">"._DELETE_THIS_USER."</a></strong></td>
		</tr>
		</table>";
		$Table->Close();
	}
	echo "<Table width=100% cellspacing=0 border=0 align=center>
	<tr>
	  <td colspan=\"2\" align=center><br>
		<input type=\"Submit\" name=\"Submit2\" value=\"Update Profile\"></td>
	</tr>
	</Table>
	</form>";
	echo "<br /><br/>";
}else{

	if(isset($_GET['success'])){
		switch ($_GET['success'])
		{
			case 1:
				echo _SUCCESSFULLY_UPDATED_PROFILE."<br /><br />";
			break;
			
			case 2:
				echo _FAILURE_UPDATING_PROFILE."<br /><br />";
			break;
			
			case 3:
				echo _ILLEGAL_EMAIL_."<br /><br />";
			break;
			
			case 4:
				echo _CANT_DELETE_ROOT_ADMIN."<br /><br />";
			break;
			
			case 5:
				echo _USER_DELETED."<br /><br />";
			break;
		}
	}
	
	$Table->Open("<strong>"._SELECT_A_USER."</strong>");
	echo "<form method=\"post\" action=\"index.php?find=Admin_Panel&amp;func=Modify User\">
	<table width=\"100%\" border=\"0\">
	<tr>
	<td align=\"center\">
	<select name=\"user\">";
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