
<?PHP
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->id() == 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}

if(isset($_POST['Send_Msg']) && !empty($_POST['Send_Msg']))
{
    $_POST['To'] = trim($_POST['To']);
    $_POST['Subject'] = trim($_POST['Subject']);
    $_POST['Message'] = trim($_POST['Message']);
	if(empty($_POST['To'])){
		echo _RECEPIENT_MISSING."<br/><br/>";
	}elseif(isset($_POST['To'])){
		if(empty($_POST['Subject'])){
			$_POST['Subject'] = _NONE;
		}
		if(empty($_POST['Message'])){
            $_POST['Message'] = _DEFAULT_MESSAGE_PM;
        }
		if($MySQL->Rows("SELECT username FROM ".$pre."users WHERE username = '".$_POST['To']."'") == 0){
			header("Location: ".$base_url."/index.php?find=Private Messages&message=3&show_page=1&To=".$_POST['To']);
			die();
		}else{
			if(ereg('^[a-zA-Z0-9_-]+$', $_POST['To'])){
				$_POST['Subject'] = str_replace(": ", "_", $_POST['Subject']);
				if(ereg('^[a-zA-Z0-9 ?!_-]+$', $_POST['Subject'])){
					$_POST['Subject'] = str_replace("_", ": ", $_POST['Subject']);
					$SEND = 'INSERT INTO `'.$pre.'Messages` ( `MsgID` , `Username` , `From` , `Subject` , `Sent` , `Message` ) '
					. ' VALUES ( \'\', \''.$_POST['To'].'\', \''.$user->name().'\', \''.$_POST['Subject'].'\', \''.$date.'\', \''.$_POST['Message'].'\' ) ' ;
					$MySQL->Query($SEND);
					header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1&message=4&To=".$_POST['To']);
					die();
				}else{
					header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1&message=5");
					die();
				}
			}else{
				header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1&message=6");
				die();
			}
			
		}
	}
}
$Table->Open();
echo "<br>";
if((isset($_POST['Delete']) && !empty($_POST['Delete'])) || isset($_GET['Delete'])){
	if(isset($_GET['Delete'])){
		if($MySQL->Rows("SELECT MsgID FROM ".$pre."Messages WHERE Old = 1 AND MsgID = ".$_GET['MsgID']) > 0){
			$MySQL->Query("DELETE FROM ".$pre."Messages WHERE Old = 1 AND MsgID = ".$_GET['MsgID']);
			$_SESSION['Block'] = NULL;
			header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1");
			die();
		}
	}else{
		if($_POST['delete_all']){
			if($MySQL->Rows("SELECT MsgID FROM ".$pre."Messages WHERE Old = 1 AND Username = '".$user->name()."'") > 0){
				$run_it = $MySQL->Query("SELECT MsgID FROM ".$pre."Messages WHERE Old = 1 AND Username = '".$user->name()."'");
				while($r = mysql_fetch_array($run_it)){
					$MySQL->Query("DELETE FROM ".$pre."Messages WHERE Old = 1 AND MsgID = ".$r['MsgID']." LIMIT 1");
				}
				$_SESSION['Block'] = NULL;
				header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1");
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1&message=7");
				die();
			}
		}else{
			if(count($msg) > 0){
				foreach($msg as $val){
					$MySQL->Query("DELETE FROM ".$pre."Messages WHERE MsgID = ".$val." LIMIT 1");
				}
				$_SESSION['Block'] = NULL;
				header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1");
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Private Messages&show_page=1&message=7");
				die();
			}
		}
	}
}
switch ($_GET['message'])
{
	case 2:
		echo _NO_SUCH_MSG."<br/><br/>";
	break;
		
	case 3:
		echo _NO_SUCH_USER_MSG_NOT_SENT."<br/><br/>";
	break;
	
	case 4:
		echo _MSG_SENT."<br/><br/>";
	break;
	
	case 5:
		echo _ILLEGAL_CHAR_SUBJECT."<br><br>";
	break;
	
	case 6:
		echo _ILLEGAL_CHAR_TO."<br><br>";
	break;
	
	case 7:
		echo _NO_MESSAGES_SELECTED_FOR_DELETE."<br /><br />";
	break;
}

$Total_Messages = $MySQL->Rows("SELECT Username FROM ".$pre."Messages WHERE Username = '".$user->name()."'");
$New_M = $MySQL->Rows("SELECT Old FROM ". $pre ."Messages WHERE Username = '".$user->name()."' AND New = 1");
$Old_M = $MySQL->Rows("SELECT New FROM ". $pre ."Messages WHERE Username = '".$user->name()."' AND Old = 1");

if($Total_Messages > 0){
	if($New_M > 0){
		echo _YOU_HAVE . " " . $New_M . " " . _NEW_MESSAGES ."<br><br>";
		if($_SESSION['pm_forwarded'] == FALSE){
			if(!isset($_GET['message']) || $_GET['message'] == 4){
				$_SESSION['Block'] = NULL;
				$_SESSION['pm_forwarded'] = TRUE;
				header("Location: ".$base_url."/index.php?find=Private Messages");
				die();
			}
		}else{
			$_SESSION['pm_forwarded'] = FALSE;
		}
	}else{
		echo _NO_NEW_MESSAGES."<br><br>";
	}
}

echo "<a href=\"index.php?find=Private Messages&amp;file=Send_Msgs\">"._SEND_A_MSG."</a><br><br>
      <form action=\"index.php?find=Private Messages\" method=\"post\">
	  <table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
      <tr>
      <td width=\"7%\" align=center class=table>"._DELETE."</td>
      <td width=\"17%\" align=center class=table>"._FROM."</td>
      <td width=\"45%\" align=center class=table>"._SUBJECT."</td>
      <td width=\"31%\" align=center class=table>"._RECIEVED."</td>
      </tr>";
	  
if($Total_Messages == "0"){
	echo "</table><br>"._NO_MSGS;
}else{

	$Count_Div = $Total_Messages / 10;
	$Ro_Count = ceil($Count_Div);
	if(empty($_GET['show_page'])){
		$_GET['show_page'] = 1;
	}
	
	if($_GET['show_page'] > $Ro_Count){
		$_GET['show_page'] = 1;
	}
	
	$i = 1;
	for($i = 1; $i <= $Ro_Count; $i++){
		if($_GET['show_page'] == $i){
			$second = $i * 10;
			$first = $second - 10;
			$limit_show = "".$first.", 10"; 
		}
	}
	if($Total_Messages <= 5){
		$limit_show = $Total_Messages;
	}	
	$msg_count = 0;
	if($Total_Messages > 0){
		if(empty($_SESSION['GetMsg'])){
			$_SESSION['GetMsg'] = $MySQL->Query("SELECT * FROM ".$pre."Messages WHERE Username = '".$user->name()."' ORDER BY MsgID DESC LIMIT ". $limit_show);
		}
		$i = 1;
		while($r = mysql_fetch_array($_SESSION['GetMsg'])) {
			if($_SESSION['PM']['Total_Messages'] > 0 && (empty($_SESSION['PM']['Messages_Sessioned']) || ($_SESSION['PM']['Messages_Sessioned'] !== $_GET['show_page']))){
				$show_msgs = true;
			}
			if($show_msgs !== false){
				$_SESSION['PM']['MsgID'][$i] = $r['MsgID'];
				$_SESSION['PM']['From'][$i] = $r['From'];
				$_SESSION['PM']['Subject'][$i] = htmlspecialchars($r['Subject']);
				$_SESSION['PM']['Sent'][$i] = $r['Sent'];
				$_SESSION['PM']['New'][$i] = $r['New'];
				if($_SESSION['PM']['New'][$i] == 1){
					$_SESSION['PM']['Subject'][$i] = "*".htmlspecialchars($r['Subject']);
				}
				if($_GET['show_page'] == $Ro_Count){
					$difference = $_SESSION['PM']['Total_Messages'] - $first;
				}else{
					$difference = 10;
				}
				if(($i == $difference) && (empty($_SESSION['PM']['Messages_Sessioned']) || $_SESSION['PM']['Messages_Sessioned'] !== $_GET['show_page'])){
					if(empty($_GET['show_page'])){
						$_GET['show_page'] = 1;
					}
					$_SESSION['PM']['Messages_Sessioned'] = $_GET['show_page'];
					header("location: ".$base_url."/index.php?find=Private Messages&show_page=".$_GET['show_page']);
					die();
				}
			}
			if($i <= $Total_Messages){
				echo "<tr>
				<td width=\"7%\" align=center class=table>"; if($_SESSION['PM']['New'][$i] != 1){ echo "<input type=\"checkbox\" name=\"msg[".$msg_count++."]\" value=\"".$_SESSION['PM']['MsgID'][$i]."\">"; }else{ echo "---"; } echo "</td>
				<td width=\"17%\" align=center class=table>".$_SESSION['PM']['From'][$i]."</td>
				<td width=\"45%\" align=left class=table> &nbsp;&nbsp;<a href=\"index.php?find=Private Messages&amp;file=Read_Msgs&amp;MsgID=".$_SESSION['PM']['MsgID'][$i]."\">".$_SESSION['PM']['Subject'][$i]."</a></td>
				<td width=\"31%\" align=center class=table>".$_SESSION['PM']['Sent'][$i]."</td>
				</tr>";
			}
			$i++;
		}
	}
	echo "<td colspan=\"4\" align=\"left\"><input name=\"delete_all\" type=\"checkbox\" value=\"1\"> Delete ALL your messages?<br /><input name=\"Delete\" type=\"submit\" value=\"Delete\"></td></table></form>";

	echo "<strong>"._PAGE.":</strong> ";
	echo "	
	<script language=\"javascript\" type=\"text/javascript\" >
	function jumpto(x){
	
	if (document.jump.show_page.value != \"null\") {
		document.location.href = x
		}
	}
	</script>
	
	<form name=\"jump\" action=\"index.php\" method=\"get\">
	<select name=\"show_page\" onChange=\"jumpto(document.jump.show_page.options[document.jump.show_page.options.selectedIndex].value)\">";
	
	for($i = 1; $i <= $Ro_Count; $i++){
		echo "<option value=\"index.php?find=Private Messages&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
	}
	echo "</select></form>";

}
echo "<br><br>";
$Table->Close();
?>
