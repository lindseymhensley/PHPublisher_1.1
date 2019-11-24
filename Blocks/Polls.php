<?php
/*******************************************************************
 **
 ** Block: Polls.php
 ** Description: Block to display the current poll if any
 **                                                  
 *******************************************************************/ 


if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
include($Current_Directory."/Modules/Polls/language/".$user->language().".php");
$Is_there_a_Poll = $MySQL->Rows("SELECT poll_id FROM ".$pre."Polls WHERE status='active'");
	
define("INCLUDED_IN_BLOCK", true);
if($Is_there_a_Poll == 0){
	echo _NO_ACTIVE_POLLS;
}elseif($Is_there_a_Poll > 1){
	echo _TO_MANY_ACTIVE_POLLS;
}else{
	if(empty($_GET['Vote']) && empty($_GET['voted'])){
			$included_inblock = 1;
			include_once($Current_Directory."/Modules/Polls/index.php");
	}else{
		echo "Your vote has been registered, thank you for your participation in our survey!";
	}
}
?>