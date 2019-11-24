<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

$Table->Open();
if($user->id() > 1){
	header("Location: ".$base_url."/index.php");
	die();
}
if(isset($_POST['Submit'])){
	if(empty($_POST['username']) || empty($_POST['password1']) || empty($_POST['password2']) || empty($_POST['email'])){
		echo _MISSING_REQ_FIELDS."<br><br>";
	}else{
		if(empty($_POST['Newsletter'])){
			echo _NEWSLETTER_DECIDE;
		}else{
			if(empty($_POST['Iunderstand']) || !isset($_POST['Iunderstand'])){
				echo _TERMS_AGREE;
			}else{
				if(empty($_POST['sec_q']) || !isset($_POST['sec_q']) || empty($_POST['sec_a']) || !isset($_POST['sec_a'])){
					echo _SECURITY_QUESTIONS_MISSING;
				}else{
					if($MySQL->Rows("SELECT username FROM ".$pre."users WHERE username = '".$_POST['username']."'") > 0) {
						echo _USERNAME_INUSE;
					}else{
						if ($_POST['password1'] == $_POST['password2']) {
							if(ereg('^[a-zA-Z0-9_-]+$', $_POST['username']))
							{ 
								if(strpos($_POST['email'], '@')){
									if(!empty($_POST['homepage'])){
										if(ereg('^http://+[a-zA-Z0-9_.-]+\.([A-Za-z]{2,4})$', $_POST['homepage'])){
											$MD5_Password = sha1(md5(sha1(hexdec(sha1(md5($_POST['password2']))))));
											$regdate = date("F j, Y"); 
											$Insert_User = 'INSERT INTO `'.$pre.'users` ( `user_id` , `username` , `user_email` , `user_website` , `user_regdate` , `user_icq` , `user_occ` , `user_from` , `user_interests` , `user_aim` , `user_yim` , `user_msnm` , `user_password` , `newsletter` , `user_security_question` , `user_security_answer`, `user_theme`) VALUES (\'\', \''.$_POST['username'].'\', \''.$_POST['email'].'\', \''.$_POST['homepage'].'\', \''.$regdate.'\', \''.$_POST['icq'].'\', \''.$_POST['occupation'].'\', \''.$_POST['user_location'].'\', \''.$_POST['interests'].'\', \''.$_POST['aim'].'\', \''.$_POST['yim'].'\', \''.$_POST['msn'].'\', \''.$MD5_Password.'\', \''.$_POST['Newsletter'].'\', \''.$_POST['sec_q'].'\', \''.$_POST['sec_a'].'\', \''.$GLOBALS['Site_Theme'].'\')';
											$MySQL->Query($Insert_User);
											echo "Username: ".$_GET['username']." has been successfully registered!<br />
											Below is the information you provided that will be required to login.
											<br />
											<br />"._USERNAME.": ".$_POST['username']."
											<br />"._PASSWORD.": ".$_POST['password2']."
											<br />
											<br /><a href=\"index.php?find=Members&file=Login\>You may now login</a>.";
											$process_complete = true;
										}else{
											echo _ILLEGAL_HP."<br><br>"; 	
										}
									}else{
										$MD5_Password = sha1(md5(sha1(hexdec(sha1(md5($_POST['password2']))))));
										$regdate = date("F j, Y"); 
										$Insert_User = 'INSERT INTO `'.$pre.'users` ( `user_id` , `username` , `user_email` , `user_website` , `user_regdate` , `user_icq` , `user_occ` , `user_from` , `user_interests` , `user_aim` , `user_yim` , `user_msnm` , `user_password` , `newsletter` , `user_security_question` , `user_security_answer`, `user_theme`) VALUES (\'\', \''.$_POST['username'].'\', \''.$_POST['email'].'\', \''.$_POST['homepage'].'\', \''.$regdate.'\', \''.$_POST['icq'].'\', \''.$_POST['occupation'].'\', \''.$_POST['user_location'].'\', \''.$_POST['interests'].'\', \''.$_POST['aim'].'\', \''.$_POST['yim'].'\', \''.$_POST['msn'].'\', \''.$MD5_Password.'\', \''.$_POST['Newsletter'].'\', \''.$_POST['sec_q'].'\', \''.$_POST['sec_a'].'\', \''.$GLOBALS['Site_Theme'].'\')';
										$MySQL->Query($Insert_User);
											echo "Username: ".$_GET['username']." has been successfully registered!<br />
											Below is the information you provided that will be required to login.
											<br />
											<br />"._USERNAME.": ".$_POST['username']."
											<br />"._PASSWORD.": ".$_POST['password2']."
											<br />
											<br /><a href=\"index.php?find=Members&file=Login\>You may now login</a>.";
											$process_complete = true;
									}
								}else{ 
									echo _ILLEGAL_EMAIL."<br><br>"; 				
								}
							}else{ 
								echo _INVALID_CHARACTERS_USR."<br><br>"; 
							} 				
						}else{
							echo _PASSWORD_SAME;
						}
					}
				}
			}
		}
	}
}
if($process_complete !== true){
?>
<form action="index.php?find=Members&amp;file=Register" method="post">
<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="29%" align=left><?=_USERNAME ?>:</td>
    <td width="15%" align=left><input type="text" name="username" value="<?=$_POST['username'] ?>" maxlength="15"></td>
    <td width="56%" align=left><?=_REQUIRED ?></td>
  </tr>
  <tr>
    <td  align=left><?=_PASSWORD ?>:</td>
    <td  align=left><input type="password" name="password1" maxlength="15"></td>
    <td  align=left><?=_REQUIRED ?></td>
  </tr>
  <tr>
    <td  align=left><?=_CONFIRM_PASSWORD ?>: </td>
    <td  align=left><input type="password" name="password2" maxlength="15"></td>
    <td  align=left><?=_REQUIRED ?></td>
  </tr>
  <tr>
    <td  align=left><?=_EMAIL ?>:</td>
    <td  align=left><input type="text" name="email" value="<?=$_POST['email'] ?>" maxlength="50"></td>
    <td  align=left><?=_REQUIRED ?></td>
  </tr>
  <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
  </tr>
  <tr>
    <td  align=left valign="top"><?=_HOMEPAGE ?>:</td>
    <td  align=left><input type="text" name="homepage" value="<?=$_POST['homepage'] ?>" maxlength="75"></td>
    <td  align=left>(<?=_EXWL ?>)</td>
  </tr>
  <tr>
    <td  align=left><?=_OCCUPATION ?>:</td>
    <td  align=left><input type="text" name="occupation" value="<?=$_POST['occupation'] ?>" maxlength="25"></td>
    <td  align=left></td>
  </tr>
  <tr>
    <td  align=left><?=_LOCATION ?>:</td>
    <td  align=left><input type="text" name="user_location" value="<?=$_POST['location'] ?>" maxlength="25"></td>
    <td  align=left></td>
  </tr>
    <tr>
    <td  align=left><?=_INTERESTS ?>:</td>
    <td  align=left><br><textarea name="interests" rows="5" cols="22" maxlength="150"><?=$_POST['interests'] ?></textarea></td>
    <td  align=left></td>
  </tr>
  <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
  </tr>
  <tr>
    <td  align=left><?=_ICQ ?>: </td>
    <td  align=left><input type="text" name="icq" value="<?=$_POST['icq'] ?>" maxlength="25"></td>
    <td  align=left></td>
  </tr>
  <tr>
    <td  align=left><?=_AIM ?>: </td>
    <td  align=left><input type="text" name="aim" value="<?=$_POST['aim'] ?>" maxlength="25"></td>
    <td  align=left></td>
  </tr>
    <tr>
    <td  align=left><?=_YIM ?>: </td>
    <td  align=left><input type="text" name="yim" value="<?=$_POST['yim'] ?>" maxlength="25"></td>
    <td  align=left></td>
  </tr>
  <tr>
    <td  align=left><?=_MSN ?>: </td>
    <td  align=left><input type="text" name="msn" value="<?=$_POST['msn'] ?>" maxlength="25"></td>
    <td  align=left></td>
  </tr>
  <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
  </tr>
    <tr>
    <td  align=left><?=_SECURITY_QUESTION ?>: </td>
    <td  align=left><input type="text" name="sec_q" value="<?=$_POST['sec_q'] ?>"  maxlength="255"></td>
    <td  align=left> <?=_REQUIRED ?> <?=_NOTICE_ON_SECURITY_Q ?></td>
  </tr>
    <tr>
    <td  align=left><?=_SECURITY_ANSWER ?>: </td>
    <td  align=left><input type="text" name="sec_a" value="<?=$_POST['sec_a'] ?>" maxlength="50"></td>
    <td  align=left><?=_REQUIRED ?></td>
  </tr>
    <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
  </tr>
    <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
  </tr>
  <tr>
    <td  align=left><?=_SUBSCRIBE_TO_NEWSLETTER ?> </td>
    <td align=center><input name="Newsletter" type="radio" value="1" <? if($_POST['Newsletter'] == 1) echo "checked"; ?>>
      <?=_YES ?>
      <input name="Newsletter" type="radio" value="2" <? if($_POST['Newsletter'] == 2) echo "checked"; ?>>
      <?=_NO ?></td>
    <td  align=left><?=_REQUIRED ?></td>
  </tr>
  <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
  </tr>
    <tr>
    <td  align=left><?=_DO_YOU_UNDERSTAND ?></td>
    <td align=center><input type="checkbox" name="Iunderstand">
      <?=_YES ?></td>
    <td  align=left><?=_REQUIRED ?></td>
  </tr>
    <tr>
    <td  align=left>&nbsp;</td>
    <td align=center><input type="submit" name="Submit" value="<?=_REGISTER_NOW ?>"></td>
    <td  align=left>&nbsp;</td>
  </tr>
</table>
</form>
<?php
}
$Table->Close();
?>
