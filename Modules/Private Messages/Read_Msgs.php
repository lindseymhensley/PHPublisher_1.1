<?PHP
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->id() == 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}

if(!isset($_GET['MsgID']) || empty($_GET['MsgID'])){
	header("Location: ".$base_url."/index.php?find=Private Messages");
	die();
}elseif(isset($_GET['MsgID'])){
	if(!is_numeric($_GET['MsgID'])){
		header("Location: ".$base_url."/index.php?find=Private Messages");
		die();
	}else{
		if(($r = $MySQL->Fetch("SELECT * FROM ".$pre."Messages WHERE Username = '".$user->name()."' AND MsgID = ".$_GET['MsgID'])) == false){
			header("Location: ".$base_url."/index.php?find=Private Messages");
			die();
		}
	}
}
$From = $r['From'];
$Sent = $r['Sent'];
$Subject = $r['Subject'];
$Message = htmlspecialchars($r['Message']);
$MsgID = $r['MsgID'];
if(($r == true) && ($r['Old'] != 1)){
	$_SESSION['Block'] = NULL;
	$MySQL->Query("UPDATE ".$pre."Messages SET New = 0, Old = 1 WHERE MsgID = ".$MsgID);
}
if($Censor_Words == 1){
	$Message = censor($Message);
}
if($Emoticon_On == 1){
	$Message = emoticon($Message);
}
if($BBcode_On == 1){
	$Message = bbcode($Message);
}
$Table->Open();
echo "<br><br><table width=\"90%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
  <tr>
    <td width=\"40%\" align=\"left\" class=table><strong>"._FROM."</strong>: ".$From."</td>
    <td width=\"60%\" align=\"left\" class=table><strong>"._RECIEVED."</strong>: ".$Sent."</td>
  </tr>
  <tr>
    <td colspan=\"2\" align=\"left\" class=table><strong>"._SUBJECT."</strong>: ".htmlspecialchars($Subject)."</td>
  </tr>
  <tr>
    <td colspan=\"2\" align=\"left\" class=table><strong>"._MESSAGE."</strong>:
	<br><br>
	".nl2br($Message)."
</td>
  </tr>
  <tr>
<td colspan=\"2\" align=\"center\" class=table>[ <a href=\"index.php?find=Private Messages&amp;Delete=1&amp;MsgID=".$MsgID."\">"._DELETE."</a> | <a href=\"index.php?find=Private Messages&amp;file=Send_Msgs&amp;To=".$From."&amp;Subject=Re: ".$Subject."\">"._REPLY."</a> | <a href=\"index.php?find=Private Messages&amp;file=Send_Msgs&amp;To=".$From."&amp;Subject=Re: ".$Subject."&amp;Quote=1&amp;MsgID=".$MsgID."\">"._QUOTE_REPLY."</a> |<a href=\"index.php?find=Private Messages\">"._GO_TO_INBOX."</a> ]</td>
  </tr>
</table>";
echo "<br><br>";
$Table->Close();
?>
