<?php 
/*******************************************************************
 **
 ** Admin File: index.php
 ** Description: Lists all the administrative functions
 **                                                  
 *******************************************************************/ 
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 1){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
ob_start();
	if(($handle = @fopen("http://phpublisher.net/txt/version.txt", "r")) !== false){
		$Most_Current_Version = @file("http://phpublisher.net/txt/version.txt");
		$Most_Current_Version = (string)$Most_Current_Version[0];
		$Current_Version = (string)$version;
		if(($Most_Current_Version !== $Current_Version)){
			$Table->Open("<strong>"._NEW_VERSION."</strong>");
			echo _NEW_PHPUBLISHER;
			$Table->Close();
			echo "<br />";
		}
	}
	$UpGrade = ob_get_contents();
ob_end_clean();
echo $UpGrade;

if(isset($_GET['func'])){
	if(file_exists($Current_Directory."/Modules/Admin_Panel/Functions/".$_GET['func']."/index.php")){
		include($Current_Directory."/Modules/Admin_Panel/Functions/".$_GET['func']."/index.php");
	}else{
		include($Current_Directory."/Modules/Admin_Panel/Functions/index.php");
	}
}else{
	include($Current_Directory."/Modules/Admin_Panel/Functions/index.php");
} 
?>
