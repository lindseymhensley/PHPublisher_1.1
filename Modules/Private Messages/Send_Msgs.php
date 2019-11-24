<?PHP
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->id() == 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}

$Table->Open();
echo "<br><form action=\"index.php?find=Private Messages\" method=\"post\" name=\"Message\">
<table width=\"90%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
  <tr>
    <td width=\"40%\" class=table valign=\"top\" align=\"left\">"._TO.":<br> 
      <input type=\"text\" name=\"To\" value=\"".$_GET['To']."\"></td>
    <td  align=\"left\"width=\"60%\" class=table>"._SENT_ON.": ".$date."</td>
  </tr>
  <tr>
    <td align=\"left\" colspan=2 class=table valign=\"top\">"._SUBJECT.":<br><input name=\"Subject\" type=\"text\" value=\"".$_GET['Subject']."\"></td>
  </tr>"; 
  
  	if($Emoticon_On == 1){
		echo  "<tr><td colspan=2 align=\"left\" class=table>";
		echo "<script language=\"JavaScript\" type=\"text/javascript\">
			function emoticon(text) {
				var txtarea = document.Message.Message;
				text = ' ' + text + ' ';
				if (txtarea.createTextRange && txtarea.caretPos) {
					var caretPos = txtarea.caretPos;
					caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
					txtarea.focus();
				} else {
					txtarea.value  += text;
					txtarea.focus();
				}
			}
		</script>";
		$find_smilies = $MySQL->Query("SELECT * FROM ".$pre."smilies ORDER BY smilie_id ASC");
		$i = 1;
		echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
		while($emo = mysql_fetch_array($find_smilies)){
			if($i == 1){
				echo "<tr>"
				."<td align=center><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$base_url."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
			}elseif($i >=2 && $i < 5){
				echo "<td align=center><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$base_url."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
			}elseif($i == 5){
				echo "<td align=center><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$base_url."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>"
				."</tr>";
				unset($i);
				$i = 0;
			}
			$i++;
		}
		echo "<tr><td align=center colspan=7>";
		if($BBcode_On == 1){
			echo toolbar("Message", "Message");
		}	
		echo "</td></tr></table>";
	}
	$message = NULL;
  	if($_GET['Quote'] == 1)
  	{
		if(is_numeric($_GET['MsgID'])){
			if(($msg_content = $MySQL->Fetch("SELECT Message FROM ".$pre."Messages WHERE MsgID = ".$_GET['MsgID']." AND Username = '".$user->name()."'")) !== FALSE)
			{
				$message = "[quote]".$msg_content['Message']."[/quote]";
			}
		}
  	}
  echo "<tr>
    <td colspan=2 align=left class=table>"._MESSAGE.":<br>
    <textarea name=\"Message\" cols=\"55\" rows=\"10\">".$message."</textarea>
</td>
  </tr>
  <tr>
    <td align=\"left\" colspan=\"2\" align=\"left\">      <a href=\"index.php?find=Private Messages\">"._GO_TO_INBOX."</a><br><br>
	<input name=\"Send_Msg\" type=\"hidden\" value=\"1\">
	<input type=\"submit\" name=\"Send\" value=\"Send\"></td>
  </tr>
</table>
</form>";
$Table->Close();
?>
