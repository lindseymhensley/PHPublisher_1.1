<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if($user->id() <= 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}
if($_POST['Update'] == 1){
	if(!empty($_POST['email']) && !empty($_POST['security_q']) && !empty($_POST['security_a'])){
		if(strpos($_POST['email'], '@')){
			if(strlen($_POST['sig']) > $Site_Max_Sig_Length){
				header("Location: ".$base_url."/index.php?find=Members&func=Profile&success=5");
				die();
			}
			function str_ills($string, $type){
				if($type == 1){
					return htmlspecialchars($string);
				}elseif($type == 2){
					return addslashes($string);
				}elseif($type == 3){
					return htmlspecialchars(addslashes($string));
				}
			}
			if(!empty($_POST['homepage'])){
				if(ereg('^http://+[a-zA-Z0-9_.-]+\.([A-Za-z]{2,4})$', $_POST['homepage'])){
					$MySQL->Query("UPDATE ".$pre."users SET user_email = '".$_POST['email']."', user_website = '".str_ills($_POST['website'], 3)."', user_avatar = '".str_ills($_POST['avatar'], 3)."', user_icq = '".str_ills($_POST['icq'], 3)."', user_occ = '".str_ills($_POST['occ'], 3)."', user_from = '".str_ills($_POST['from'], 3)."', user_interests = '".str_ills($_POST['interests'], 3)."', user_sig = '".$_POST['sig']."', user_viewemail = '".str_ills($_POST['viewemail'], 3)."', user_aim = '".str_ills($_POST['aim'], 3)."', user_yim = '".str_ills($_POST['yim'], 3)."', user_msnm = '".str_ills($_POST['msnm'], 3)."', newsletter = '".str_ills($_POST['newsletter'], 3)."', user_security_question = '".str_ills($_POST['security_q'], 3)."', user_security_answer = '".str_ills($_POST['security_a'], 3)."' WHERE user_id = '".$user->id()."'");
					header("Location: ".$base_url."/index.php?find=Members&func=Profile&success=1");
					die();
				}else{
					header("Location: ".$base_url."/index.php?find=Members&func=Profile&success=4");
					die();
				}
			}else{
				$MySQL->Query("UPDATE ".$pre."users SET user_email = '".$_POST['email']."', user_website = '".str_ills($_POST['website'], 3)."', user_avatar = '".str_ills($_POST['avatar'], 3)."', user_icq = '".str_ills($_POST['icq'], 3)."', user_occ = '".str_ills($_POST['occ'], 3)."', user_from = '".str_ills($_POST['from'], 3)."', user_interests = '".str_ills($_POST['interests'], 3)."', user_sig = '".$_POST['sig']."', user_viewemail = '".str_ills($_POST['viewemail'], 3)."', user_aim = '".str_ills($_POST['aim'], 3)."', user_yim = '".str_ills($_POST['yim'], 3)."', user_msnm = '".str_ills($_POST['msnm'], 3)."', newsletter = '".str_ills($_POST['newsletter'], 3)."', user_security_question = '".str_ills($_POST['security_q'], 3)."', user_security_answer = '".str_ills($_POST['security_a'], 3)."' WHERE user_id = '".$user->id()."'");
				header("Location: ".$base_url."/index.php?find=Members&func=Profile&success=1");
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Members&func=Profile&success=3");
			die();
		}
	}else{
		header("Location: ".$base_url."/index.php?find=Members&func=Profile&success=2");
		die();
	}
}

$Table->Open("<strong>"._YOUR_PROFILE_INFO."</strong>");
if(isset($_GET['success'])){
    switch ($_GET['success'])
    {
        case 1:
            echo "<br>"._SUCCESSFULLY_UPDATED_PROFILE."<br><br>";
        break;
        
        case 2:
            echo "<br>"._FAILURE_UPDATING_PROFILE."<br><br>";
        break;
        
        case 3:
            echo "<br>"._ILLEGAL_EMAIL."<br><br>";
        break;
		
		case 4:
            echo "<br>"._ILLEGAL_HP."<br><br>";
        break;
		
    	case 5:
            echo "<br>"._ILLEGAL_SIG." ".$Site_Max_Sig_Length."<br><br>";
        break;
	}
}
echo "	
<script type=\"text/javascript\">
<!--
var MessageMax  = \"".$Site_Max_Sig_Length."\";
var Override    = \"\";
	function CheckLength(){
    	MessageLength  = document.profile.sig.value.length;
    	message  = \"\";
	        if (MessageMax > 0){
    	        message = \"The maximum allowed length is \" + MessageMax + \" characters.\";
	        }
	        else{
            message = \"\";
        	}
        alert(message + \" You have used \" + MessageLength + \" characters.\");
	}
	
	function ValidateForm(){
    	MessageLength  = document.profile.sig.value.length;
    	errors = \"\";
    		if (MessageMax !=0){
        		if (MessageLength > MessageMax){
            		errors = \"The maximum allowed length is \" + MessageMax + \" characters. Current Characters: \" + MessageLength;
            	}
            }
			if (errors != \"\" && Override == \"\"){
				alert(errors);
				return false;
			}
			else{
				document.profile.submit.disabled = true;
				return true;
			}
	}
-->
</script>

<form method=\"post\" action=\"index.php?find=Members&amp;func=Profile\" name=\"profile\">
<input type=\"hidden\" name=\"Update\" value=\"1\">
<br />
<Table width=100% cellspacing=0 border=0 align=center>
<tr>
	<td width=\"45%\" align=right>"._EMAIL ." "._REQUIRED .":</td>
	<td width=\"55%\" align=left><input type=\"text\" value=\"". $user->extract("user_email") ."\" name=\"email\" maxlength=\"50\"></td>
</tr>
<tr>
	<td align=right valign=\"top\">"._HOMEPAGE .":</td>
	<td align=left><input type=\"text\" value=\"". $user->extract("user_website") ."\" name=\"website\" maxlength=\"75\"> <br />("._EXWL.")</td>
</tr>
<tr>
	<td align=right>"._AVATAR .": ("._WEBL.")</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_avatar") ."\" name=\"avatar\"> "._MAX_SIZE."</td>
</tr>

<tr>
	<td align=right>"._OCCUPATION .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_occ") ."\" name=\"occ\" maxlength=\"25\"></td>
</tr>
<tr>
	<td align=right>"._FROM .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_from") ."\" name=\"from\" maxlength=\"25\"></td>
</tr>
<tr>
	<td align=right valign=top>"._INTERESTS.":</td>
	<td align=left><textarea name=\"interests\" cols=\"50\" rows=\"5\" maxlength=\"150\">". $user->extract("user_interests") ." </textarea></td>
</tr>
<tr>
	<td align=right valign=top>"._SIGNATURE.":</td>
	<td align=left><textarea name=\"sig\"  cols=\"50\" rows=\"5\">". $user->extract("user_sig") ." </textarea><div align=\"center\">(<a href=\"javascript:CheckLength()\">Check Length</a>)</div></td>
</tr>
<tr>
	<td align=right>"._VIEW_EMAIL .":</td>
	<td align=left><input type=\"checkbox\" value=\"1\" name=\"viewemail\""; if($user->extract("user_viewemail") == 1){ echo "checked"; } echo "></td>
</tr>
<tr>
	<td align=right>"._ICQ .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_icq") ."\" name=\"icq\" maxlength=\"25\"></td>
</tr>
<tr>
	<td align=right>"._AIM .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_aim") ."\" name=\"aim\" maxlength=\"25\"></td>
</tr>
<tr>
	<td align=right>"._YIM .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_yim") ."\" name=\"yim\" maxlength=\"25\"></td>
</tr>
<tr>
	<td align=right>"._MSN .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_msnm") ."\" name=\"msnm\" maxlength=\"50\"></td>
</tr>
<tr>
	<td align=right>"._SUBSCRIBE_TO_NEWSLETTER .":</td>
	<td align=left><input type=\"checkbox\" value= \"1\" name=\"newsletter\""; if($user->extract("newsletter") == 1){ echo "checked"; } echo "></td>
</tr>
<tr>
	<td align=right>"._SECURITY_QUESTION." "._REQUIRED .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_security_question") ."\" name=\"security_q\" maxlength=\"255\"></td>
</tr>
<tr>
	<td align=right>"._SECURITY_ANSWER .""._REQUIRED .":</td>
	<td align=left><input type=\"text\" value= \"". $user->extract("user_security_answer") ."\" name=\"security_a\" maxlength=\"50\"></td>
</tr>
<tr>
  <td colspan=\"2\" align=center><br>
    <input onClick=\"return ValidateForm()\" type=\"Submit\" name=\"Submit\" value=\"Update My Profile\"></td>
</tr>
</Table>
</form>";
$Table->Close();
?>


