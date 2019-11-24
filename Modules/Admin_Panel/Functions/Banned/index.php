<?php
/*******************************************************************
 **
 ** Admin File: Banned/index.php
 ** Description: Unruly users & ip addresses are given 0 access here
 **                                                  
 *******************************************************************/ 
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}

if(isset($_POST['bann'])){
	$bann = $_POST['bann'];
}elseif(isset($_GET['bann'])){
	$bann = $_GET['bann'];
}

switch($bann)
{
	case 1:
		$reason = htmlspecialchars(trim($_POST['reason']));
		if(empty($reason)){
			$reason = _DEFAULT_BAN_REASON;
		}
		$ui = $MySQL->Fetch("SELECT user_id, username FROM ".$pre."users WHERE user_id = '".$_POST['user']."' LIMIT 1");
		if($ui === FALSE){
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned");
			die();
		}
		if($_POST['user'] != 1 && $ui['root_admin'] != 1){
			$banned = $MySQL->Rows("SELECT user_id FROM ".$pre."banned_users WHERE user_id = '".$ui['user_id']."' LIMIT 1");
			if($banned == 0){
				$MySQL->Query("INSERT INTO `".$pre."banned_users` VALUES ('".$ui['user_id']."', '".$ui['username']."', '".$reason."')");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=5&username=".$ui['username']);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=1");
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=3");
			die();
		}
	break;
	
	case 2:
		$reason = htmlspecialchars(trim($_POST['reason']));
		if(empty($reason)){
			$reason = _DEFAULT_BAN_REASON;
		}
		if(ereg('^([0-9]{2,3})+\.([0-9]{2,3})+\.([0-9]{2,3})+\.([0-9]{2,3})$', $_POST['ip'])){
			$banned = $MySQL->Rows("SELECT ip FROM ".$pre."banned_ip WHERE ip = '".$_POST['ip']."' LIMIT 1");
			if($banned == 0){
				$MySQL->Query("INSERT INTO `".$pre."banned_ip` VALUES ('', '".$_POST['ip']."', '".$reason."')");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=4&ip=".$_POST['ip']);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=1");
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=2");
			die();
		}
	break;
	
	case 3:
		if(isset($_GET['user'])){
			$ui = $MySQL->Fetch("SELECT user_id, username FROM ".$pre."banned_users WHERE user_id = '".$_GET['user']."' LIMIT 1");
			if($ui == TRUE){
				$MySQL->Query("DELETE FROM ".$pre."banned_users WHERE user_id = '".$ui['user_id']."' LIMIT 1");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=8&username=".$ui['username']);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=6");
				die();
			}
		}elseif(isset($_GET['ip_id'])){
			$ipi = $MySQL->Fetch("SELECT ip_id FROM ".$pre."banned_ip WHERE ip_id = '".$_GET['ip_id']."' LIMIT 1");
			if($ipi == TRUE){
				$MySQL->Query("DELETE FROM ".$pre."banned_ip WHERE ip_id = '".$ipi['ip_id']."' LIMIT 1");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=7&ip=".$ipi['ip']);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned&message=6");
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Banned");
			die();
		}
	break;
}

switch ($_GET['message'])
{
	case 1:
		echo _ALREADY_BANNED."<br /><br />";
	break;
	
	case 2:
		echo _ILLEGAL_IP."<br /><br />";
	break;
	
	case 3:
		echo _CANT_BAN_ADMIN_GUEST."<br /><br />";
	break;
	
	case 4:
		echo _IP_BANNED."<br /><br />";
	break;
	
	case 5:
		echo _USER_BANNED."<br /><br />";
	break;
	
	case 6:
		echo _NOT_BANNED."<br /><br />";
	break;
	
	case 7:
		echo _IP_UNBANNED."<br /><br />";
	break;
	
	case 8:
		echo _USER_UNBANNED."<br /><br />";
	break;
}

echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		<td width=\"48%\"><a href=\"index.php?find=Admin_Panel&amp;func=Banned#ban_user\">"._BAN_A_USER."</a>"; 
			$Table->Open("<strong>"._BANNED_USERS."</strong>");
				echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr>
				<td class=\"table\" width=\"25%\" align=center>"._USERNAME."</td>
				<td class=\"table\" width=\"55%\" align=center>"._REASON."</td>
				<td class=\"table\" width=\"20%\" align=center>"._ACTIONS."</td>
				</tr>";
				$user_sql = $MySQL->Query("SELECT reason, user_id, username FROM ".$pre."banned_users ORDER BY username");
				while($banned_user = mysql_fetch_array($user_sql)){
					echo"<tr>
					<td class=\"table\">".$banned_user['username']."</td>
					<td class=\"table\">".$banned_user['reason']."</td>
					<td class=\"table\" align=center><a href=\"index.php?find=Admin_Panel&amp;func=Banned&amp;bann=3&amp;user=".$banned_user['user_id']."\">"._UNBAN."</a></td>
					</tr>";
				}
				echo "</table>"; 
			$Table->Close();		
			echo "<br />";
			$Table->Open("<strong>"._BAN_A_USER."</strong>");
			echo "<a name=\"ban_user\"><form action=\"index.php?find=Admin_Panel&amp;func=Banned\" method=\"post\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td width=\"35%\" align=\"right\">"._USERNAME.":</td>
					<td align=left width=\"65%\">
					<select name=\"user\">";
					$user_info = $MySQL->Query("SELECT user_id, username, root_admin FROM ".$pre."users ORDER BY username ASC");
					while($usr_info = mysql_fetch_array($user_info)){
						if($usr_info['user_id'] == 1 || $usr_info['root_admin'] == 1){
							echo "";
						}else{
							echo "<option value=\"".$usr_info['user_id']."\">".$usr_info['username']."</option>";
						}
					}
					echo "</select>
					</td>
				</tr>
				<tr>
					<td align=\"right\">"._REASON.":</td>
					<td align=left><input type=\"text\" name=\"reason\" maxlength=\"255\"></td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"Ban\"></td>
				</tr>
			</table>
			<input name=\"bann\" type=\"hidden\" value=\"1\">
			</form>";
			$Table->Close();
		echo "</td><td width=\"4%\"></td><td width=\"48%\"><a href=\"index.php?find=Admin_Panel&amp;func=Banned#ban_ip\">"._BAN_A_IP."</a>"; 
			$Table->Open("<strong>"._BANNED_IPS."</strong>");
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr>
				<td class=\"table\" width=\"25%\" align=center>"._IP."</td>
				<td class=\"table\" width=\"55%\" align=center>"._REASON."</td>
				<td class=\"table\" width=\"20%\" align=center>"._ACTIONS."</td>
				</tr>";
				$ip_sql = $MySQL->Query("SELECT ip, reason, ip_id FROM ".$pre."banned_ip");
				while($banned_ip = mysql_fetch_array($ip_sql)){
					echo"<tr>
					<td class=\"table\">".$banned_ip['ip']."</td>
					<td class=\"table\">".$banned_ip['reason']."</td>
					<td class=\"table\" align=center><a href=\"index.php?find=Admin_Panel&amp;func=Banned&amp;bann=3&amp;ip_id=".$banned_ip['ip_id']."\">"._UNBAN."</a></td>
					</tr>";
				}
				echo "</table>";
			$Table->Close();
			echo "<br />";
			$Table->Open("<strong>"._BAN_A_IP."</strong>");
			echo "<a name=\"ban_ip\"><form action=\"index.php?find=Admin_Panel&amp;func=Banned\" method=\"post\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td width=\"35%\" align=\"right\">"._IP.":</td>
					<td width=\"65%\" align=left><input type=\"text\" name=\"ip\" maxlength=\"15\"></td>
				</tr>
				<tr>
					<td align=\"right\">"._REASON.":</td>
					<td align=left><input type=\"text\" name=\"reason\" maxlength=\"255\"></td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"Ban\"></td>
				</tr>
			</table>
			<input name=\"bann\" type=\"hidden\" value=\"2\">
			</form>";
			$Table->Close();
		echo "</td></tr>
</table>";

?>