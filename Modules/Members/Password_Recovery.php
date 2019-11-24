<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if(!isset($_POST['processing'])){
	$Table->Open(_STEP1);
		echo _WELCOME_TO_RECOVERY;
		
		if(isset($_GET['error'])){
            switch ($_GET['error'])
            {
                case 0:
                    echo "<br><br> Nice try...";
                break;
                
                case 1:
                    echo "<br><br>"._SERROR." : "._USER_NOT_FOUND;
                break;
                
                case 2:
                    echo "<br><br>"._SERROR." : "._INCORRECT_ANSWER;
                break;
			}
		}

		echo "<br><form action=\"index.php?find=Members&amp;file=Password_Recovery\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"processing\" value=\"yes\">";
		echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td align=right width=\"40%\">"._USERNAME.": &nbsp;</td>
			<td align=left width=\"60%\"> &nbsp;<input name=\"username\" type=\"text\" /></td>
		  </tr>
		</table><br><input name=\"Submit\" type=\"submit\" value=\"Submit\"></form>";
	$Table->Close();
}elseif(isset($_POST['processing'])){
	$find_user_name = "SELECT user_id, username, user_security_question FROM ".$pre."users WHERE username = '".$_POST['username']."'";
	$question = $MySQL->Fetch($find_user_name);
	$user_exists = $MySQL->Rows($find_user_name);
	if($question['user_id'] == 1){
		header("Location: ".$base_url."/index.php?find=Members&file=Password_Recovery&error=0");
	}
	if(isset($_POST['step2'])){
		$stepp = _SUCCESS;
	}else{
		$stepp = _STEP2;
	}
	$Table->Open($stepp);
		if(!isset($_POST['step2'])){
			if($user_exists > 0){
				echo $question['username']._USER_FOUND;
				echo "<br><br><form action=\"index.php?find=Members&amp;file=Password_Recovery\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"processing\" value=\"yes\">";
				echo "<input type=\"hidden\" name=\"step2\" value=\"1\">";
				echo "<input type=\"hidden\" name=\"username\" value=\"".$question['username']."\">";
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
				echo "<tr><td align=right width=\"40%\"> "._SECURITY_QUESTION.": &nbsp;</td><td align=left width=\"60%\"> &nbsp;".$question['user_security_question']."?</td></tr>";
				echo "<tr><td align=right> "._SECURITY_ANSWER.": &nbsp;</td><td align=left> &nbsp;<input name=\"answer\" type=\"text\"></td></tr>";
				echo "</table><input name=\"Submit\" type=\"submit\" value=\"Submit\"></form>";
			}else{
				header("Location: ".$base_url."/index.php?find=Members&file=Password_Recovery&error=1&username=".$_POST['username']);
			}
		}elseif(isset($_POST['step2'])){
			if(strtolower($_POST['answer']) == strtolower($question['user_security_answer'])){
				
				$random_keys = array("a","b","5","f","g","h","i","j","k","c","8","9","n","o","p","q","r","s","d","e","2","3","4","l","m","0","1","6","t","u","v","w","7","x","y","z");
				for($i = 1; $i <= 8; $i++){
					$pass_letter[$i] = $random_keys[mt_rand(0,35)];
				}
				$new_password = $pass_letter[1].$pass_letter[2].$pass_letter[3].$pass_letter[4].$pass_letter[5].$pass_letter[6].$pass_letter[7].$pass_letter[8];
				$verify_code = sha1(md5(sha1(hexdec(sha1(md5($new_password))))));
				$verification_link = $base_url."/index.php?find=Members&file=Verify&username=".$question['username']."&code=".$verify_code;
				
				$MySQL->Query("UPDATE ".$pre."users set verify_code='".$verify_code."' WHERE username='".$question['username']."'");
				
				$pass_message = 
"Attention ".$question['username']."!

You or someone has requested to have your password recovered.
Below is the information provided to recovering your password!

Simply click the link below.
Verification Link: ".$verification_link."
Or visit ".$base_url."/index.php?find=Members&file=Verify and enter your username and the verification code below
Verification Code: ".$verify_code."
New Password: ".$new_password." (ONLY IF YOU USE THE VERIFICATION CODE OR LINK)

If you wish for your password to stay the same simply do not click the link or use the vericiation code.";
								
				mail($question['user_email'],"Password Recovery Request", $pass_message, "From: ".$Admin_Email) or die("Failed emailing message!");
				echo "<br>"._PASS_SENT."<i>".$question['user_email']."</i>.<br><br>";
			}else{
				header("Location: ".$base_url."/index.php?find=Members&file=Password_Recovery&error=2");
			}
		}
	$Table->Close();
}
?>
