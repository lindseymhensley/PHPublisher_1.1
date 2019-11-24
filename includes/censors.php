<?php

/*******************************************************************
 **
 ** File: censors.php
 ** Description: A file designed to search the database for specific
 ** words listed to be replaced by more desirable ones.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

function censor($string){
	global $MySQL, $pre;

	if(!isset($_SESSION['Bad_Words_Set'])){
		$Bad_Word_sql = $MySQL->Query("SELECT bad_word FROM ".$pre."censor_words");
		$BW_Count = $MySQL->Rows("SELECT bad_word FROM ".$pre."censor_words");
		$BWC = $BW_Count - 1;
		unset($i);
		$cv = 0;
		while($bad_word = mysql_fetch_array($Bad_Word_sql)){			
				$_SESSION['Bad_Words'][$cv] = $bad_word[0];
				if($cv == $BWC){
					$_SESSION['Bad_Words_SET'] = 1;
				}
			$cv++;
		}
	}

	if(!isset($_SESSION['Replacement_SET'])){
		$Replace_sql = $MySQL->Query("SELECT replacement FROM ".$pre."censor_words");
		$Replace_Count = $MySQL->Rows("SELECT replacement FROM ".$pre."censor_words");
		$RC = $Replace_Count - 1;
		unset($i);
		$i = 0;
		while($replace = mysql_fetch_array($Replace_sql)){			
				$_SESSION['Replacement'][$i] = $replace[0];
				if($i == $RC){
					$_SESSION['Replacement_SET'] = 1;
				}
			$i++;
		}
	}

	$string = str_replace($_SESSION['Bad_Words'], $_SESSION['Replacement'], $string);
	return $string;
}
?>