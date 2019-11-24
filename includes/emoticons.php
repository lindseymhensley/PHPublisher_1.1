<?php
/*******************************************************************
 **
 ** File: emoticons.php
 ** Description: if the admin has the site set to allow emoticons
 ** this function will search and replace all elements in it.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

function emoticon($string){
	global $MySQL, $pre;
	if(!isset($_SESSION['emot_code'])){
		$sql_code = $MySQL->Query("SELECT smilie_code FROM ".$pre."smilies");
		$code_count = $MySQL->Rows("SELECT smilie_code FROM ".$pre."smilies");
		$Cc = $code_count - 1;
		$i = 0;
		while($emcode = mysql_fetch_array($sql_code)){
			$_SESSION['emot']['code'][$i] = $emcode[0];
			if($i == $Cc){
				$_SESSION['emot_code'] = 1;
			}
			$i++;
		}
	}
	if(!isset($_SESSION['emot_img'])){
		$sql_img = $MySQL->Query("SELECT smilie_img FROM ".$pre."smilies");
		$img_count = $MySQL->Rows("SELECT smilie_img FROM ".$pre."smilies");
		$Ic = $img_count - 1;
		$i = 0;
		while($emimg = mysql_fetch_array($sql_img)){
			$_SESSION['emot']['img'][$i] = "<img src=\"".$GLOBALS['base_url']."/images/smilies/".$emimg[0]."\" alt=\"Smilie\">";
			if($i == $Ic){
				$_SESSION['emot_img'] = 1;
			}
			$i++;
		}
	}
	$string = str_replace($_SESSION['emot']['code'], $_SESSION['emot']['img'], $string);
	return $string;
}

?>
