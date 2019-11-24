<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if(empty($_SESSION['Top10']['Web_Links'])){
	$_SESSION['Top10']['Web_Links'] = $MySQL->Query("SELECT title, id FROM ".$pre."link ORDER BY views DESC LIMIT 10");
}
$r = 1;
include($Current_Directory."/Modules/Web Links/language/english.php");
echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"15%\" align=\"left\"><strong>"._RANK."</strong></td>
    <td width=\"85%\" align=\"left\"><strong>"._TITLE."</strong></td>
  </tr>";


include_once($Current_Directory."/includes/functions.php");
while($link = mysql_fetch_array($_SESSION['Top10']['Web_Links'])){
	echo "<tr><td align=\"left\">".$r.".</td><td align=\"left\"><a href=\"index.php?find=Web Links&wlid=".$link['id']."\" target=\"_blank\">".strr(stripslashes($link['title']), 15)."</a></td></tr>";
	$r++;
}
unset($r);

echo "</table>";
?>