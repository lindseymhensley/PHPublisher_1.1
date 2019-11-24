<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if(isset($_GET['code']) && isset($_GET['username'])){
	$verifying = $MySQL->Fetch("SELECT verify_code, username FROM ".$pre."users WHERE username = '".$_GET['username']."'");
	if(!empty($verifying['verify_code']) && !empty($_GET['code']) && !empty($_GET['username'])){
		if($verifying['verify_code'] == $_GET['code']){
			$MySQL->Query("UPDATE ".$pre."users SET user_password = verify_code, verify_code = '".md5(crypt(hexdec(md5(rand(1,99999999999)))))."' WHERE username = '".$verifying['username']."'");
			$Table->Open();
				echo "<br>"._CHANGED_PASSWORD."<br><br>";
			$Table->Close();
		}else{
			header("Location: ".$base_url."/index.php?find=Members&file=Verify&error=1");
			die();
		}
	}else{
		header("Location: ".$base_url."/index.php?find=Members&file=Verify&error=2");
		die();
	}
}else{
	$Table->Open();
        if(isset($_GET['error'])){
            switch ($_GET['error'])
            {
                case 1:
                    echo "<br>"._VERIFICATION_WRONG."<br>";
                break;

                case 2:
                    echo "<br>"._MISSING_IMPORTANT_FIELDS."<br>";
                break;
            }
        }

		echo "<form action=\"index.php\" method=\"get\">";
		echo "<input type=\"hidden\" name=\"find\" value=\"Members\">";
		echo "<input type=\"hidden\" name=\"file\" value=\"Verify\">";
		echo "<br><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td align=\"right\" width=\"30%\">"._USERNAME.": </td>
            <td align=\"left\" width=\"70%\"><input name=\"username\" type=\"text\"></td>
          </tr>
          <tr>
            <td align=\"right\">"._VERIFICATION_CODE.": </td>
            <td align=\"left\"><input name=\"code\" type=\"text\"></td>
          </tr>
          <tr>
            <td align=\"right\"><input name=\"Verify\" type=\"submit\" value=\"Verify\"></td>
            <td align=\"left\">&nbsp;</td>
          </tr>
        </table></form>";
	$Table->Close();
}

?>
