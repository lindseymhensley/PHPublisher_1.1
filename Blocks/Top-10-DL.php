<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

$t10_dl_sql = $MySQL->Query("SELECT title, id FROM ".$pre."downloads ORDER BY downloads DESC LIMIT 10");
$r = 1;
include($Current_Directory."/Modules/Downloads/language/english.php");
echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"15%\" align=\"left\"><strong>"._RANK."</strong></td>
    <td width=\"85%\" align=\"left\"><strong>"._TITLE."</strong></td>
  </tr>";


include_once($Current_Directory."/includes/functions.php");
while($download = mysql_fetch_array($t10_dl_sql)){
	echo "<tr><td align=\"left\">".$r.".</td><td align=\"left\"><a href=\"index.php?find=Downloads&dlid=".$download['id']."\">".strr(stripslashes($download['title']), 15)."</a></td></tr>";
	$r++;
}
unset($r);

echo "</table>";
?>