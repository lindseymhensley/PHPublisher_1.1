<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if($user->lvl() >= 1){
	$_POST['name'] = $user->name();
}
if(!empty($_POST['name'])){
	if(!empty($_POST['email'])){
		if(!empty($_POST['message'])){
			$message = "
"._NAME.": ".$_POST['name']."
"._EMAIL.": ".$_POST['email']."
"._SUBJECT.": ".$_POST['subject']."
"._MESSAGE.": ".$_POST['message']."

"._IP_ADDR.": ".$_SERVER['REMOTE_ADDR']."
";
			$Table->Open();
			if(mail($Admin_Email, $_POST['subject'], $message,"From: ".$email) !== FALSE){
				echo _MSG_SENT;
			}else{
				echo _ERROR_MSG_NOT_SENT;
			}
			$Table->Close();
		}else{
			echo _MISSING_MESSAGE;
		}
	}else{
		echo _MISSING_EMAIL;
	}
}else{
	echo _MISSING_NAME;
}

?>