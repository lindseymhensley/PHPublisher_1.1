<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

$Table->Open("<b>Restricted Area</b>");
if($user->id() > 1){
	header("Location: ".$base_url."/index.php?find=Members");
	die();
}elseif($user->id() == 1){
	if(isset($_GET['Sec_Error'])){
		echo _SECURITY_CODE_MISSING;
	}
	
	if(isset($_POST['submitted'])){
		if($Security_Login == 1){
			$datekey = date("F j");
			$rcode = hexdec(md5($HTTP_USER_AGENT . $_POST['rand'] . $datekey));
			$code = substr($rcode, 2, 6);
			if($_POST['security_code'] != $code){
				header("Location: ".$base_url."/index.php?find=Members&file=Login&Sec_Error=1");
				die();
			}elseif(!isset($_POST['security_code'])){
				header("Location: ".$base_url."/index.php?find=Members&file=Login&Sec_Error=1");
				die();
			}
		}		
		if(empty($_POST['usr_name']) || empty($_POST['passwrd'])){
			echo _USER_PASS_EMPTY;
		}elseif(isset($_POST['usr_name']) && isset($_POST['passwrd'])){
			if(($checkuser = $MySQL->Fetch("SELECT username FROM ".$pre."users WHERE username ='".$_POST['usr_name']."'")) !== FALSE){ 
				$checkpass = sha1(md5(sha1(hexdec(sha1(md5($_POST['passwrd']))))));
				if(($valid = $MySQL->Fetch("SELECT username FROM ".$pre."users WHERE username ='".$checkuser['username']."' AND user_password = '".$checkpass."'")) !== FALSE){ 
					if(isset($_POST['remember_me'])){
						setcookie("Cusername", $valid['username'], time()+345600, $GLOBALS['Cookie_Path'], $GLOBALS['Cookie_Domain']);
						setcookie("Cpassword", $checkpass, time()+345600, $GLOBALS['Cookie_Path'], $GLOBALS['Cookie_Domain']);
					}else{
						setcookie("Cusername", $valid['username']);
						setcookie("Cpassword", $checkpass);
					}
					$MySQL->Query("UPDATE ".$pre."users_online SET time_stamp=".time().", reg = 1 WHERE ip = '".$_SERVER['REMOTE_ADDR']."'");
					$user->updateip();
					session_unset();
					session_destroy();
					header("Location: ".$base_url."/index.php?find=Members");	
                    die();
				}else{
					echo _INCORRECT_PASSWORD; 
				} 
			}else{
				echo _INCORRECT_USERNAME;
			}

		}else{
			echo _ERROR;
		}
	}
	
	?><br>
	<form method="post" action="index.php?find=Members&amp;file=Login">
	<input name="submitted" type="hidden" value="1">
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align=center>
	<table width="100%"  border="0" cellspacing="5" cellpadding="0">
	  <tr>
		<td width="25%" align=left><?=_USERNAME ?>:</td>
		<td width="75%" align=left><input type="text" name="usr_name"></td>
	  </tr>
	  <tr>
		<td align=left><?=_PASSWORD ?>:</td>
		<td align=left><input type="password" name="passwrd"></td>
	  </tr>
	  <? if($Security_Login == 1){ ?>
	  	  <tr align=left>
		<td align=left><?=_SECURITY_CODE ?>:</td>
		<td align=left><?php
			$rand = rand(1,10000);
		?>
		<input name="rand" type="hidden" value="<?=$rand ?>">
		<input name="Security_Login" type="hidden" value="1">
		<img src="<?=$base_url ?>/Modules/Members/img.php?rand=<?=$rand ?>&amp;base_url=<?=$base_url ?>"></td>
	  	  </tr>
	  	  <tr>
		<td align=left><?=_SECURITY_VERIFY ?>:</td>
		<td align=left><input name="security_code" type="text" maxlength="6"></td>
	  	  </tr>
		  <? } ?>
	  	  <tr>
		<td align=left><?=_REMEMBER_ME ?>:</td>
		<td align=left><input type="checkbox" name="remember_me" value="1"></td>
	  	  </tr>
	  	  <tr>
		<td align=left>&nbsp;</td>
		<td align=left><input type="submit" name="Submit" value="<?=_LOGIN ?>"></td>
	  </tr>
	</table><br>[ <a href="index.php?find=Members&amp;file=Register"><?=_REGISTER ?></a> ]<br>[ <a href="index.php?find=Members&amp;file=Password_Recovery"><?=_PASS_RECOVERY ?></a> ]
		</td>
		</tr>
	</table>
	</form><br>

	<?php
}else{
	echo _ERROR;
}
$Table->Close();
?>
