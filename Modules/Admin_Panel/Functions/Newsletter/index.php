<?php
/*******************************************************************
 **
 ** Admin File: Newsletter/index.php
 ** Description: Here you can send out newsletters to those who have
 ** OPT-IN to recieve emails from the administrator of the site.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 3){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
$Table->Open();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$_POST['message'] = htmlentities(trim($_POST['message']));
	$_POST['subject'] = htmlentities(trim($_POST['subject']));
	if(empty($_POST['subject']) || empty($_POST['message']) || $_POST['send'] == 0){
		echo _SOME_FIELDS_ARE_MISSING;
	}else{
		if($_POST['send'] == 1){
			$sql = $MySQL->Query("SELECT user_email FROM ".$pre."users");
		}else{
			$sql = $MySQL->Query("SELECT user_email FROM ".$pre."users WHERE newsletter");
		}
		$countt = 0;
		while($ur = mysql_fetch_array($sql)){
			if(mail($ur['user_email'], $_POST['subject'], $_POST['message'], "From: ".$Admin_Email) == FALSE){
				$failed = true;
				break;
			}else{
				$countt++;
			}
		}
		if($failed == true){
			echo _SEND_MAIL_FAILED;
		}else{
			echo _SEND_MAIL_SUCCESS." ".$countt." users!";
		}
	}
}
?>
<form method="post" action="index.php?find=Admin_Panel&func=Newsletter">
<table width="75%"  border="0" cellspacing="0" cellpadding="5" align="center">
  <tr>
    <td width="20%" align="left"><?php echo _SUBJECT; ?>:</td>
    <td width="80%" align="left"><input type="text" name="subject" value="<?php echo $_POST['subject'] ?>"></td>
  </tr>
  <tr>
    <td valign="top" align="left"><?php echo _MESSAGE; ?>:</td>
    <td align="left"><textarea name="message" cols="55" rows="10"><?php echo $_POST['message']; ?></textarea></td>
  </tr>
    <tr>
    <td align="left"><?php echo _SEND_TO; ?>:</td>
    <td align="left"><input name="send" type="radio" value="1"><?php echo _ALL ?> (<?php $t_opt = $MySQL->Rows("SELECT user_id FROM ".$pre."users"); echo $t_opt." "._USERS; ?>)<br>
      <input name="send" type="radio" value="2"><?php echo _OPT_IN ?> (<?php $t_opt = $MySQL->Rows("SELECT user_id FROM ".$pre."users WHERE newsletter = 1"); echo $t_opt." "._USERS; ?>)</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>
</form>
<?php
$Table->Close();
?>