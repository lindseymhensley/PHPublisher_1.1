<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if($user->id() <= 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}
if(isset($_POST['change'])){
	if(!empty($_POST['current']) && !empty($_POST['new']) && !empty($_POST['newagain'])){
		$cur = $MySQL->Fetch("SELECT user_password FROM ".$pre."users WHERE user_id = '".$user->id()."'");
		if($cur['user_password'] === sha1(md5(sha1(hexdec(sha1(md5($_POST['current']))))))){
			if($_POST['new'] === $_POST['newagain']){
				$MySQL->Query("UPDATE ".$pre."users SET user_password='".sha1(md5(sha1(hexdec(sha1(md5($_POST['newagain']))))))."' WHERE user_id = '".$user->id()."'");
				setcookie("Cpassword", "");
				setcookie("Cusername", "");
				setcookie("Cusername", $user->name());
				setcookie("Cpassword", sha1(md5(sha1(hexdec(sha1(md5($_POST['newagain'])))))));
				header("Location: ".$base_url."/index.php?find=Members&func=Change Password&success=1");
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Members&func=Change Password&error=3");
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Members&func=Change Password&error=2");
			die();
		}
	}else{
		header("Location: ".$base_url."/index.php?find=Members&func=Change Password&error=1");
		die();
	}
}else{
	$Table->Open();
		if($_GET['error'] == 1){
			echo "<br>"._MISSING_SOMETHING_HERE."<br><br>";
		}elseif($_GET['error'] == 2){
			echo "<br>"._CURRENT_IS_WRONG."<br><br>";
		}elseif($_GET['error'] == 3){
			echo "<br>"._NEW_AGAIN_DONT_MATCH."<br><br>";
		}elseif($_GET['success'] == 1){
			echo "<br>"._SUCCESSFULLY_CHANGED_PASSWORD."<br><br>";
		}
		echo "<br><form action=\"index.php?find=Members&amp;func=Change Password\" method=\"post\">";
		echo "<input name=\"change\" type=\"hidden\" value=\"1\">";
		echo "<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
		  <tr>
			<td align=right width=\"30%\">"._CURRENT_PASS.": </td>
			<td align=left width=\"70%\"><input type=\"password\" name=\"current\"></td>
		  </tr>
			<tr>
			<td align=right>"._NEW_PASS.": </td>
			<td align=left><input type=\"password\" name=\"new\"></td>
		  </tr>
			<tr>
			<td align=right>"._NEW_PASS_AGAIN.": </td>
			<td align=left><input type=\"password\" name=\"newagain\"></td>
		  </tr>
			<tr>
			<td align=right>&nbsp;</td>
			<td align=left><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>
		  </tr>
		</table></form>";
	$Table->Close();
}
?>
