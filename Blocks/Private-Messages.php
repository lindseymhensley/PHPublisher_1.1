<?PHP
/*******************************************************************
 **
 ** Block: Private-Messages.php
 ** Description: Block that displays users new and old messages in
 ** their inbox.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
include($Current_Directory."/Modules/Private Messages/language/".$user->language().".php");
$Total_Messages = $MySQL->Rows("SELECT Username FROM ".$pre."Messages WHERE Username = '".$user->name()."'");
$New_M = $MySQL->Rows("SELECT Old FROM ". $pre ."Messages WHERE Username = '".$user->name()."' AND New = 1");
$Old_M = $MySQL->Rows("SELECT New FROM ". $pre ."Messages WHERE Username = '".$user->name()."' AND Old = 1");
echo "<center><br />"._YOU_HAVE." ".$New_M." "._NEW_AND." ".$Old_M." "._OLD_MESSAGES."<br /><br /><a href=\"index.php?find=Private Messages\">"._GO_TO_INBOX."</a></center>";
?>