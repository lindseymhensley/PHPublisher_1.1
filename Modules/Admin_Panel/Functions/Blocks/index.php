<?php
/*******************************************************************
 **
 ** Admin File: Blocks/index.php
 ** Description: Move, edit, add delete shift blocks here, you can
 ** decide what you want the menu layout to be with ease.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
switch ($_GET['Message'])
{
	case 1:
		echo _BLOCK_UPDATED."<br /><br />";
	break;	
	case 2:
		echo _BLOCK_CREATED."<br /><br />";
	break;	
	case 3:	
		echo _BLOCK_DELETED."<br /><br />";
	break;	
	case 4:
		echo _CANT_SHIFT."<br /><br />";
	break;
	case 5:
		echo _BLOCK_DOESNT_EXIST."<br /><br />";
	break;
}

if($_POST['Submit']){
	if(isset($_POST['bid']) && is_numeric($_POST['bid'])){
		$checktrue = $MySQL->Rows("SELECT Block_ID FROM ".$pre."Blocks WHERE Block_ID = '".$_POST['bid']."'");
		if($checktrue == 0){
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=4");
			die();
		}
		if(!empty($_POST['Title']) && isset($_POST['Access']) && isset($_POST['Side']) && isset($_POST['Status'])){
			if(!is_numeric($_POST['File'])){
				$newname = explode(".", $_POST['File']);
				$MySQL->Query("UPDATE ".$pre."Blocks SET Block_Title='".$_POST['Title']."', Block_File=1, Block_File_Name='".$newname[0]."', Block_Access=".$_POST['Access'].", Block_Status=".$_POST['Status']." WHERE Block_ID = '".$_POST['bid']."'");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=1");
				die();
			}else{
				if(!empty($_POST['Content'])){
					$MySQL->Query("UPDATE ".$pre."Blocks SET Block_Title='".$_POST['Title']."', Block_Content='".$_POST['Content']."', Block_File=0, Block_Access=".$_POST['Access'].", Block_Status=".$_POST['Status']." WHERE Block_ID = '".$_POST['bid']."'");
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=1");
					die();
				}else{
					echo _CONTENT_MISSING."<br><br>";
				}
			}
		}else{
			echo _FIELDS_MISSING."<br><br>";
		}
	}else{
		if(!empty($_POST['Title']) && isset($_POST['Access']) && isset($_POST['Side']) && isset($_POST['Status'])){
			$cbs = $MySQL->Query("SELECT Block_lvl FROM ".$pre."Blocks WHERE Block_Side = '".$_POST['Side']."' ORDER BY Block_lvl DESC LIMIT 1");
			while($block = mysql_fetch_array($cbs)){
				$block_lvl = $block['Block_lvl'] + 1;
			}
			if(!is_numeric($_POST['File'])){
				$newname = explode(".", $_POST['File']);
				$insert_block = "INSERT INTO `".$pre."Blocks` VALUES ('', '".$_POST['Title']."', '', '".$_POST['Side']."', '1', '".$newname[0]."', '".$block_lvl."', '".$_POST['Access']."', '".$_POST['Status']."')";
				$MySQL->Query($insert_block);
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=2");
				die();
			}else{
				if(!empty($_POST['Content'])){
					$insert_block = "INSERT INTO `".$pre."Blocks` VALUES ('', '".$_POST['Title']."', '".$_POST['Content']."', '".$_POST['Side']."', '0', '', '".$block_lvl."', '".$_POST['Access']."', '".$_POST['Status']."')";
					$MySQL->Query($insert_block);
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=2");
					die();
				}else{
					echo _CONTENT_MISSING."<br><br>";
				}
			}
		}else{
			echo _FIELDS_MISSING."<br><br>";
		}
	}
}elseif(isset($_GET['Delete'])){
	if(isset($_GET['bid']) && is_numeric($_GET['bid'])){
		$checktrue = $MySQL->Rows("SELECT Block_ID FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."'");
		if($checktrue == 0){
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=4");
			die();
		}
		if(isset($_GET['Confirm'])){
			$MySQL->Query("DELETE FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."' LIMIT 1");
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=3");
			die();
		}else{
			$binfo = $MySQL->Fetch("SELECT Block_Title, Block_ID FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."'");
			echo _ARE_YOUR_SURE_DELETE_BLOCK." <strong>".$binfo['Block_Title']."</strong>?<br /><br />";
			echo "<a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Delete=1&amp;bid=".$binfo['Block_ID']."&amp;Confirm=1\">"._YES."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Blocks\">"._NO."</a><br /><br /><em>"._BLK_INFO_BELOW."</em><br /><br />";
		}
	}else{
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks");
		die();
	}
}elseif(isset($_GET['Move'])){
	if(isset($_GET['bid']) && is_numeric($_GET['bid'])){
		$checktrue = $MySQL->Rows("SELECT Block_ID FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."'");
		if($checktrue == 0){
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=4");
			die();
		}
		switch ($_GET['Move'])
		{
			case "Left":
			case "Right":
				if($MySQL->Rows("SELECT * FROM ".$pre."Blocks WHERE Block_Side='".$_GET['Move']."'") > 0){
					$getlvl = $MySQL->Query("SELECT Block_lvl FROM ".$pre."Blocks WHERE Block_Side='".$_GET['Move']."' ORDER BY Block_lvl DESC LIMIT 1");
					while($lvl = mysql_fetch_array($getlvl)){
						$newblvl = $lvl['Block_lvl'] + 1;
					}
				}else{
					$newblvl = 0;
				}
				$MySQL->Query("UPDATE ".$pre."Blocks SET Block_Side='".$_GET['Move']."', Block_lvl=".$newblvl." WHERE Block_ID = '".$_GET['bid']."'");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks");
			break;
			
			default:
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks");
				die();
			break;	
		}
	}else{
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks");
		die();
	}
}elseif(isset($_GET['Shift'])){
	if(isset($_GET['bid']) && is_numeric($_GET['bid'])){
		$checktrue = $MySQL->Rows("SELECT Block_ID FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."'");
		if($checktrue == 0){
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=4");
			die();
		}
		switch ($_GET['Shift'])
		{
			case "Up":
			case "Down":
				$blk_info = $MySQL->Fetch("SELECT * FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."'");
				switch ($_GET['Shift'])
				{ 
					case "Up":
						$desired_lvl = $blk_info['Block_lvl'] - 1;
						$how_show = "ASC";
					break;
					
					case "Down":
						$desired_lvl = $blk_info['Block_lvl'] + 1;
						$how_show = "DESC";
					break;
				}
				$mi = $MySQL->Query("SELECT * FROM ".$pre."Blocks WHERE Block_Side = '".$blk_info['Block_Side']."' ORDER BY Block_lvl ".$how_show." LIMIT 1");
				while($blks_info = mysql_fetch_array($mi)){
					$fl_block = $blks_info['Block_lvl'];
				}
				if($blk_info['Block_lvl'] == $fl_block){
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks&Message=4");
					die();
				}else{
					switch ($_GET['Shift'])
					{
						case "Up":
							$MySQL->Query("UPDATE ".$pre."Blocks SET Block_lvl=Block_lvl+1 WHERE Block_lvl = '".$desired_lvl."' AND Block_Side = '".$blk_info['Block_Side']."'");
							$MySQL->Query("UPDATE ".$pre."Blocks SET Block_lvl=Block_lvl-1 WHERE Block_ID = '".$blk_info['Block_ID']."'");
						break;
						
						case "Down":
							$MySQL->Query("UPDATE ".$pre."Blocks SET Block_lvl=Block_lvl-1 WHERE Block_lvl = '".$desired_lvl."' AND Block_Side = '".$blk_info['Block_Side']."'");
							$MySQL->Query("UPDATE ".$pre."Blocks SET Block_lvl=Block_lvl+1 WHERE Block_ID = '".$blk_info['Block_ID']."'");
						break;
					}
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks");
					die();
				}
			break;
			
			default:
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks");
				die();
			break;	
		}
	}else{
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Blocks");
		die();
	}
}else{
	echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Blocks#block\">"._CREATE_A_BLOCK."</a></div>";
	$Table->Open();
	echo "<table width=\"100%\"  border=\"0\">"
		."<tr>"
		."<td width=\"48%\" align=\"center\">"._LEFT_BLOCKS."</td>"
		."<td width=\"4%\" align=\"center\">&nbsp;</td>"
		."<td width=\"48%\" align=\"center\">"._RIGHT_BLOCKS."</td>"
		."</tr>"
		."<tr>"
		."<td align=\"center\" valign=\"top\"><br>";
		$block_query = $MySQL->Query("SELECT Block_Access, Block_lvl, Block_ID, Block_Title, Block_Status FROM ".$pre."Blocks WHERE Block_Side = 'Left' ORDER BY Block_lvl ASC");
		$last_block = $MySQL->Query("SELECT Block_lvl FROM ".$pre."Blocks WHERE Block_Side = 'Left' ORDER BY Block_lvl DESC LIMIT 1");
		while($last = mysql_fetch_array($last_block)){
			$L_Blk = $last['Block_lvl'];
		}
	
		while($blk = mysql_fetch_array($block_query)){
			if($blk['Block_Access'] == 0){
				$access_lvl = _ALL_USERS;
			}elseif($blk['Block_Access'] == 1){
				$access_lvl = _REGISTERED_USERS;
			}elseif($blk['Block_Access'] == 2){
				$access_lvl = _ARTICLE_PUBLISHER;
			}elseif($blk['Block_Access'] == 3){
				$access_lvl = _MODERATOR;
			}elseif($blk['Block_Access'] == 99){
				$access_lvl = _ADMIN_ONLY;
			}
			if($blk['Block_Status'] == 1){
				$status = "<font color=\"#80FF00\">"._ACTIVE."</font>";
			}else{
				$status = "<font color=\"#FF0000\">"._IN_ACTIVE."</font>";
			}
			if($blk['Block_lvl'] == 0){
				$move = "<a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Down&amp;bid=".$blk['Block_ID']."\">"._DOWN."</a>";
			}elseif($blk['Block_lvl'] == $L_Blk){
				$move = "<a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Up&amp;bid=".$blk['Block_ID']."\">"._UP."</a>";
			}else{
				$move = "<a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Up&amp;bid=".$blk['Block_ID']."\">"._UP."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Down&amp;bid=".$blk['Block_ID']."\">"._DOWN."</a>";
			}
			echo "<table width=\"85%\"  border=\"0\" class=\"table\">"
			."<tr>"
			."<td width=\"25%\">"._TITLE.":</td>"
			."<td width=\"75%\">".$blk['Block_Title']."</td>"
			."</tr>"
			."<tr>"
			."<td>"._ACCESS.":</td>"
			."<td>".$access_lvl."</td>"
			."</tr>"
			."<tr>"
			."<td>"._STATUS.":</td>"
			."<td>".$status."</td>"
			."</tr>"
			."<tr>"
			."<td>"._ACTIONS.":</td>"
			."<td><a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;bid=".$blk['Block_ID']."#block\">"._EDIT."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;bid=".$blk['Block_ID']."&amp;Delete=1\">"._DELETE."</a></td>"
			."</tr>"
			."<tr>"
			."<td>"._MOVE.":</td>"
			."<td> <a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Move=Right&amp;bid=".$blk['Block_ID']."\">"._RIGHT."</a> | ".$move."</td>"
			."</tr>"
			."</table><br>";
		}
	unset($move);
	echo "</td>"
		."<td align=\"center\">&nbsp;</td>"
		."<td align=\"center\" valign=\"top\"><br>";
		$block_query = $MySQL->Query("SELECT Block_Access, Block_lvl, Block_ID, Block_Title, Block_Status FROM ".$pre."Blocks WHERE Block_Side = 'Right' ORDER BY Block_lvl ASC");
		$last_block = $MySQL->Query("SELECT Block_lvl FROM ".$pre."Blocks WHERE Block_Side = 'Right' ORDER BY Block_lvl DESC LIMIT 1");
		while($last = mysql_fetch_array($last_block)){
			$L_Blk = $last['Block_lvl'];
		}
		while($blk = mysql_fetch_array($block_query)){
			if($blk['Block_Access'] == 0){
				$access_lvl = _ALL_USERS;
			}elseif($blk['Block_Access'] == 1){
				$access_lvl = _REGISTERED_USERS;
			}elseif($blk['Block_Access'] == 2){
				$access_lvl = _ARTICLE_PUBLISHER;
			}elseif($blk['Block_Access'] == 3){
				$access_lvl = _MODERATOR;
			}elseif($blk['Block_Access'] == 99){
				$access_lvl = _ADMIN_ONLY;
			}
			if($blk['Block_Status'] == 1){
				$status = "<font color=\"#80FF00\">"._ACTIVE."</font>";
			}else{
				$status = "<font color=\"#FF0000\">"._IN_ACTIVE."</font>";
			}
			if($blk['Block_lvl'] == 0){
				$move = "<a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Down&amp;bid=".$blk['Block_ID']."\">"._DOWN."</a>";
			}elseif($blk['Block_lvl'] == $L_Blk){
				$move = "<a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Up&amp;bid=".$blk['Block_ID']."\">"._UP."</a>";
			}else{
				$move = "<a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Up&amp;bid=".$blk['Block_ID']."\">"._UP."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Shift=Down&amp;bid=".$blk['Block_ID']."\">"._DOWN."</a>";
			}
			echo "<table width=\"85%\"  border=\"0\" class=\"table\">"
			."<tr>"
			."<td width=\"25%\">"._TITLE.":</td>"
			."<td width=\"75%\">".$blk['Block_Title']."</td>"
			."</tr>"
			."<tr>"
			."<td>"._ACCESS.":</td>"
			."<td>".$access_lvl."</td>"
			."</tr>"
			."<tr>"
			."<td>"._STATUS.":</td>"
			."<td>".$status."</td>"
			."</tr>"
			."<tr>"
			."<td>"._ACTIONS.":</td>"
			."<td><a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;bid=".$blk['Block_ID']."#block\">"._EDIT."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;bid=".$blk['Block_ID']."&amp;Delete=1\">"._DELETE."</a></td>"
			."</tr>"
			."<tr>"
			."<td>"._MOVE.":</td>"
			."<td> <a href=\"index.php?find=Admin_Panel&amp;func=Blocks&amp;Move=Left&amp;bid=".$blk['Block_ID']."\">"._LEFT."</a> | ".$move."</td>"
			."</tr>"
			."</table><br>";
		}
	echo "</td>"
		."</tr>"
		."</table>";
	$Table->Close();
}
echo "<br />";
$Table->Open("<strong><a href=\"index.php?find=Admin_Panel&amp;func=Blocks#block\">"._CREATE_BLOCK."</a></strong>");
if(isset($_GET['bid']) && is_numeric($_GET['bid'])){
	$binfo = $MySQL->Fetch("SELECT * FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."'");
	$checktrue = $MySQL->Rows("SELECT Block_ID FROM ".$pre."Blocks WHERE Block_ID = '".$_GET['bid']."'");
	if($checktrue == 0){
		header("Location: ".$base_url."/index.php?find=Admin_Panel&amp;func=Blocks&Message=4");
		die();
	}
}
echo "<a name=\"block\"><form action=\"index.php?find=Admin_Panel&amp;func=Blocks\" method=\"post\">"
	."<table width=\"45%\"  border=\"0\" align=center>"
	."<tr>"
	."<td width=\"25%\" align=left>Title</td>"
	."<td width=\"75%\" align=left><input type=\"text\" name=\"Title\" value=\"".$binfo['Block_Title']."\"></td>"
	."</tr>"
	."<tr>"
	."<td align=left>File</td>"
	."<td align=left><select name=\"File\">"
	."<option value=\"0\" "; if($binfo['Block_File'] == 0) echo "selected"; echo ">Not using a file</option>";
	if ($handle = opendir($Current_Directory.'/Blocks/')) { 
		while (false !== ($block = readdir($handle))) { 
			if($block === "." || $block === ".." || $block === ".htaccess" || $block === "index.htm"){
				echo "";
			}else{
				$bn = explode(".", $block);
				echo "<option value=\"".$block."\" "; if($binfo['Block_File_Name'] === $bn[0]) echo "selected"; echo ">".$block."</option>"; 
			}
		} 
		closedir($handle); 
	}
echo "</select>";
	if(isset($_GET['bid'])){
		echo "<input type=\"hidden\" name=\"bid\" value=\"".$_GET['bid']."\">";
	}
	$block_content = str_replace("&", "&amp;", $binfo['Block_Content']);
	echo"</td>"
	."</tr>"
	."<tr>"
	."<td align=left valign=\"top\">"._CONTENT."</td>"
	."<td align=left><textarea name=\"Content\" cols=\"50\" rows=\"10\">".$block_content."</textarea></td>"
	."</tr>"
	."<tr>"
	."<td align=left>"._ACCESS."</td>"
	."<td align=left><select name=\"Access\">"
	."<option value=\"0\" "; if($binfo['Block_Access'] == 0) echo "selected"; echo ">"._ALL."</option>"
	."<option value=\"1\" "; if($binfo['Block_Access'] == 1) echo "selected"; echo ">"._MEMBERS."</option>"
	."<option value=\"2\" "; if($binfo['Block_Access'] == 2) echo "selected"; echo ">"._ARTICLE_PUBLISHER."</option>"
	."<option value=\"3\" "; if($binfo['Block_Access'] == 3) echo "selected"; echo ">"._MODERATOR."</option>"
	."<option value=\"99\" "; if($binfo['Block_Access'] == 99) echo "selected"; echo ">"._ADMINS."</option>"
	."</select></td>"
	."</tr>"
	."<tr>"
	."<td align=left>"._SIDE."</td>"
	."<td align=left><select name=\"Side\">"
	."<option value=\"Left\" "; if($binfo['Block_Side'] === "Left") echo "selected"; echo ">"._LEFT."</option>"
	."<option value=\"Right\" "; if($binfo['Block_Side'] === "Right") echo "selected"; echo ">"._RIGHT."</option>"
	."</select></td>"
	."</tr>"
	."<tr>"
	."<td align=left>"._STATUS."</td>"
	."<td align=left><select name=\"Status\">"
	."<option value=\"1\" "; if($binfo['Block_Status'] == 1) echo "selected"; echo ">"._ACTIVE."</option>"
	."<option value=\"0\" "; if($binfo['Block_Status'] == 0) echo "selected"; echo ">"._IN_ACTIVE."</option>"
	."</select></td>"
	."</tr>"
	."<tr>"
	."<td>&nbsp;</td>"
	."<td align=left><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>"
	."</tr>"
	."</table>"
	."</form>";
$Table->Close();
?>