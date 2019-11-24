<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->id() == 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}
$settings = 1;
include($Current_Directory."/includes/refresh_content.php");
$Table->Open();
	$user->logout();
$Table->Close();
?>
