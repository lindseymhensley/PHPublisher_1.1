<?
if(!session_id()){
	session_start();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Install PHPublisher! - Step 4</title>
<link href="style-css.css" rel="stylesheet" type="text/css" />
</head>
<body>  <div style="text-align: center; font-weight: bold; font-size:25px; margin:10px;">PHPublisher Installation Step 4 </div>
<div style="text-align: center; margin:10px;">| -&gt; First Step -&gt; Language -&gt; MySQL Information -&gt; <strong>Admin Account</strong> -&gt; Ready? -&gt; Lets do this! &lt;- | </div>

<?php
if(isset($_POST['Submit'])){
	$_POST['host'] = $_SESSION['Install']['dbhost'];
	$_POST['user'] = $_SESSION['Install']['dbuser'];
	$_POST['pass'] = $_SESSION['Install']['dbpass'];
	$_POST['name'] = $_SESSION['Install']['dbname'];
}

if(empty($_SESSION['Install']['pre']) || !isset($_SESSION['Install']['pre'])){
	$_SESSION['Install']['pre'] = "php_";
}

include(dirname(__FILE__)."/language/".$_SESSION['Install']['Language'].".php");
$Mysql_Connection = @mysql_connect($_POST['host'], $_POST['user'], $_POST['pass']); 
if(@mysql_select_db($_POST['name'], $Mysql_Connection) !== true){
	echo "
	<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td><div style=\"text-align: center; background-color: rgb(232, 232, 240);\"><span style=\"margin: 50px;\"><strong>"._DATABASE_INFO_ERROR."</strong></span></div></td>
	</tr>
	</table>
	</body>
	</html>
	";
	die();
} 

if(isset($_POST['Submit'])){
	if(empty($_POST['username']) || empty($_POST['password1']) || empty($_POST['password2']) || empty($_POST['email'])){
		echo _MISSING_REQ_FIELDS."<br><br>";
	}else{
		if(empty($_POST['sec_q']) || !isset($_POST['sec_q']) || empty($_POST['sec_a']) || !isset($_POST['sec_a'])){
			echo _SECURITY_QUESTIONS_MISSING;
		}else{
			$user_name = strtolower($username);
			if("guest" === $user_name) {
				echo _CAN_NOT_BE_GUEST;
			}else{
				if ($_POST['password1'] == $_POST['password2']) {
					if(ereg('^[a-zA-Z0-9_-]+$', $_POST['username']))
					{ 
						if(strpos($_POST['email'], '@')){
							$MD5_Password = sha1(md5(sha1(hexdec(sha1(md5($_POST['password2']))))));
							$regdate = date("F j, Y"); 
							if(!empty($_POST['homepage'])){
								if(ereg('^http://+[a-zA-Z0-9_.-]+\.([A-Za-z]{2,4})$', $_POST['homepage'])){
									$_SESSION['Install']['Account'] = 'INSERT INTO `'.$_SESSION['Install']['pre'].'users` ( `user_id` , `username` , `user_email` , `user_website` , `user_regdate` , `user_icq` , `user_occ` , `user_from` , `user_interests` , `user_aim` , `user_yim` , `user_msnm` , `user_password` , `newsletter` , `user_security_question` , `user_security_answer`, `user_theme`, `root_admin`, `user_level`) VALUES (\'\', \''.$_POST['username'].'\', \''.$_POST['email'].'\', \''.$_POST['homepage'].'\', \''.$regdate.'\', \''.$_POST['icq'].'\', \''.$_POST['occupation'].'\', \''.$_POST['user_location'].'\', \''.$_POST['interests'].'\', \''.$_POST['aim'].'\', \''.$_POST['yim'].'\', \''.$_POST['msn'].'\', \''.$MD5_Password.'\', \'1\', \''.$_POST['sec_q'].'\', \''.$_POST['sec_a'].'\', \'PHPublisher\', \'1\', \'99\')';
									echo "
									<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
									<tr>
									<td align=\"center\" style=\"background-color: rgb(232, 232, 240);\"><div style=\"margin: 50px\">"._SUCCESSFUL_REGISTER."<br /><br />"._USERNAME.": ".$_POST['username']."<br />"._PASSWORD.": ".$_POST['password2']."<br /><br /><a href=\"Step5.php\">"._CONTINUE."</a></div></td>
									</tr>
									</table>
									</body>
									</html>
									";
									die();
								}else{
									echo _ILLEGAL_HP."<br><br>"; 	
								}
							}else{
								$_SESSION['Install']['Account'] = 'INSERT INTO `'.$_SESSION['Install']['pre'].'users` ( `user_id` , `username` , `user_email` , `user_website` , `user_regdate` , `user_icq` , `user_occ` , `user_from` , `user_interests` , `user_aim` , `user_yim` , `user_msnm` , `user_password` , `newsletter` , `user_security_question` , `user_security_answer`, `user_theme`, `root_admin`, `user_level`) VALUES (\'\', \''.$_POST['username'].'\', \''.$_POST['email'].'\', \''.$_POST['homepage'].'\', \''.$regdate.'\', \''.$_POST['icq'].'\', \''.$_POST['occupation'].'\', \''.$_POST['user_location'].'\', \''.$_POST['interests'].'\', \''.$_POST['aim'].'\', \''.$_POST['yim'].'\', \''.$_POST['msn'].'\', \''.$MD5_Password.'\', \'1\', \''.$_POST['sec_q'].'\', \''.$_POST['sec_a'].'\', \'PHPublisher\', \'1\', \'99\')';
								echo "
								<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
								<td align=\"center\" style=\"background-color: rgb(232, 232, 240);\"><div style=\"margin: 50px\">"._SUCCESSFUL_REGISTER."<br /><br />"._USERNAME.": ".$_POST['username']."<br />"._PASSWORD.": ".$_POST['password2']."<br /><br /><a href=\"Step5.php\">"._CONTINUE."</a></div></td>
								</tr>
								</table>
								</body>
								</html>
								";
								die();
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
$_SESSION['Install']['dbhost'] = $_POST['host'];
$_SESSION['Install']['dbname'] = $_POST['name'];
$_SESSION['Install']['dbuser'] = $_POST['user'];
$_SESSION['Install']['dbpass'] = $_POST['pass'];
$_SESSION['Install']['pre'] = $_POST['pre'];

?>
<div style="background-color: rgb(232, 232, 240);">&nbsp;</div>
<form action="Step4.php" method="post">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_USERNAME ?></strong>:</div></td>
    <td width="*%" align="left"><div style="margin:10px;"><strong><input type="text" name="username" value="<?=$_POST['username'] ?>" maxlength="15"></strong></div></td>
    <td width="65%" align=left><div style="margin:10px;"><?=_REQUIRED ?></div></td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_PASSWORD ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="password" name="password1" maxlength="15"></strong></div></td>
    <td  align=left><div style="margin:10px;"><?=_REQUIRED ?></div></td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_CONFIRM_PASSWORD ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="password" name="password2" maxlength="15"></strong></div></td>
    <td  align=left><div style="margin:10px;"><?=_REQUIRED ?></div></td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_EMAIL ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="email" value="<?=$_POST['email'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;"><?=_REQUIRED ?></div></td>
  </tr>
  <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left style="background-color: rgb(232, 232, 240);">&nbsp;</td>
    <td  align=left style="background-color: rgb(232, 232, 240);">&nbsp;</td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_HOMEPAGE ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="homepage" value="<?=$_POST['homepage'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;">(<?=_LINK_EX ?>)</div></td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_OCCUPATION ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="occupation" value="<?=$_POST['occupation'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;"></div></td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_LOCATION ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="user_location" value="<?=$_POST['location'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;"></div></td>
  </tr>
    <tr>
    <td valign="top"  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_INTERESTS ?></strong>:</div></td>
    <td  align=left><div style="margin:10px;"><strong><textarea name="interests" rows="5" cols="22"><?=$_POST['interests'] ?></textarea></strong></div></td>
    <td  align=left valign="top"><div style="margin:10px;"></div></td>
  </tr>
  <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left style="background-color: rgb(232, 232, 240);">&nbsp;</td>
    <td  align=left style="background-color: rgb(232, 232, 240);">&nbsp;</td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_ICQ ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="icq" value="<?=$_POST['icq'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;"></div></td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_AIM ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="aim" value="<?=$_POST['aim'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;"></div></td>
  </tr>
    <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_YIM ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="yim" value="<?=$_POST['yim'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;"></div></td>
  </tr>
  <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_MSN ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="msn" value="<?=$_POST['msn'] ?>"></strong></div></td>
    <td  align=left><div style="margin:10px;"></div></td>
  </tr>
  <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left style="background-color: rgb(232, 232, 240);">&nbsp;</td>
    <td  align=left style="background-color: rgb(232, 232, 240);">&nbsp;</td>
  </tr>
    <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_SECURITY_QUESTION ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="sec_q" value="<?=$_POST['sec_q'] ?>"  maxlength="255"></strong></div></td>
    <td  align=left> <div style="margin:10px;"><?=_REQUIRED ?></div></td>
  </tr>
    <tr>
    <td  align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_SECURITY_ANSWER ?></strong>:</div></td>
    <td  align="left"><div style="margin:10px;"><strong><input type="text" name="sec_a" value="<?=$_POST['sec_a'] ?>" maxlength="50"></strong></div></td>
    <td  align=left><div style="margin:10px;"><?=_REQUIRED ?></div></td>
  </tr>
    <tr>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
    <td  align=left>&nbsp;</td>
  </tr>
    <tr>
    <td  align=left>&nbsp;</td>
    <td align=center><input type="submit" name="Submit" value="<?=_REGISTER_ROOT_ADMIN_ACCOUNT ?>"></td>
    <td  align=left>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
