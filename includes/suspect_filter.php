<?php

//********************************************************************************************
//
// Security Feature Name: Suspect Filter
//
// Description: Searchs browser parameters for preset arrays containing all of php's program 
// execution functions, aswell as the mysql_query function. If finds any of these functions it 
// kills the script  immediately. It also searches for mysql commands,  on the chance it finds 
// one, it will log the find, and on the third find it kills the script.
//
// IMPORTANT NOTE: You can insert the name of mysql tables you wish to protect into the 
// $illegal_keywords
//
// Version: 2.1.0
// Scripted and Copyright (c) by 2005 Timothy Hensley
//
//********************************************************************************************* 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if($Enable_Suspect_Filter == 1){
	$log_location = $Current_Directory."/log/security_log.php";
	global $log_location;
	
	$illegal = array("truncate","mysql_query(", "base64_encode(", "base64_decode(", "escapeshellarg(", "exec(", "passthru(", "proc_closes(", "proc_get_status(", "proc_nice(", "proc_open(", "proc_terminate(", "shell_exec(", "system(", $pre."Article_Comments", $pre."Blocks", $pre."Messages", $pre."Modules", $pre."News_Articles", $pre."Poll_Comments", $pre."Polls", $pre."banned_ip", $pre."banned_users", $pre."censor_words", $pre."content", $pre."counter", $pre."download_categories", $pre."downloads", $pre."link", $pre."link_categories", $pre."settings", $pre."smilies", $pre."stats_today", $pre."topics", $pre."user_groups", $pre."users"); 
	
	function log_suspect($suspect){
		if(!@file_exists($GLOBALS['log_location'])){
			die("Either you gave the wrong location of the log file, or it does not exist.");		
		}	
		$hand = @fopen($GLOBALS['log_location'], "a");	
		$log_content = 
		"
		Date: ".date("F j, Y, g:i:s a")."
		IP Adress: ".$_SERVER['REMOTE_ADDR']."
		Items: ".$suspect."	
		Action: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."
		";
					
		if(!@fwrite($hand, $log_content)){
			if(!@chmod($GLOBALS['log_location'], 0777)){
				if(!@fwrite($hand, $log_content)){
					die("There appears to be a file permission error. You must chmod the log file to 0777");
				}
			}
		}	
	}
	foreach($illegal as $variable => $value){
		if(strpos(strtolower($_SERVER['REQUEST_URI']), $illegal[$variable]) !== FALSE){
			log_suspect($illegal[$variable]);
			$stop = 3;
		}
	}
	
	if($stop >= 3){
		require($Current_Directory."/includes/headers.php");
		echo $Header;
		echo "<table width=\"65%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
		<tr>
		<td>";
		$Table->Open("Suspicious activity detected!");
		echo "<div style=\"text-align: left;\">Halting Script... DONE.<br /><BR />Logging Information... DONE. <br /><br />Have a nice day :-)</div>";
		$Table->Close();
		echo"</td>
		</tr>
		</table></body></html>";
		die();
	}
}
?>