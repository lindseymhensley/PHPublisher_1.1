<?php

/*******************************************************************
 **
 ** File: functions.php
 ** Description: Contains useful functions used around the site 
 ** wither for security purposes, display, or making tasks easier
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

/*
** Function prep() prepares all data going into a query, this is considered a "Best Practice" by php.net
*/
/*
function prep($input)
{
	$input = html_entity_decode($input); 
	if(get_magic_quotes_gpc()){ 
		$input = stripslashes($input); 
	}
	if(!is_numeric($input)){
	   $input = "'" . mysql_real_escape_string($input) . "'";
	}
	return $input; 
} 

foreach ($_POST as $variable => $value){
	for($i = 0; $i <= count($_POST); $i++){
		$_POST[$variable] = prep($_POST[$variable]);
	}
}
*/
/*
** Function strr() allows X amount of characters before the string is minimized
*/

function user_info($username, $column){
	global $pre, $MySQL;
	if(empty($_SESSION['User'][$username])){
		$_SESSION['User'][$username] = $MySQL->Fetch("SELECT * FROM ".$pre."users WHERE username = '".$username."'");
	}
	if($_SESSION['User'][$username] !== FALSE){
		if($column == "user_group"){
			if(empty($_SESSION['User_Group'][$username])){
				$_SESSION['User_Group'][$username] = $MySQL->Fetch("SELECT name FROM ".$pre."user_groups WHERE group_id = '".$_SESSION['User'][$username]['user_group']."'");
			}
			if($_SESSION['User_Group'][$username] !== FALSE){
				return $_SESSION['User_Group'][$username]['name'];
			}else{
				switch($_SESSION['User'][$username]['user_level'])
				{
					case 99:
						return  "Administrator";
					break;
			
					case 3:
						return "Moderator";
					break;
			
					case 2:
						return "Article Publisher";
					break;
			
					default:
						return "Member";
					break;
				}
			}
		}else{		
			return $_SESSION['User'][$username][$column];
		}
	}else{
		return FALSE;
	}
}

function strr($string, $maxlength, $last_part = "..."){
	if(strlen($string) > $maxlength){
		return substr($string, 0, $maxlength).$last_part;
	}else{
		return $string;
	}
}

?>