<?php
/*******************************************************************
 **
 ** Admin File: Polls/index.php
 ** Description: Create/edit/remove polls and stuff here
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() != 99){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}


if(isset($_GET['create_poll']) || (isset($_GET['update_poll']) && isset($_GET['poll_ID']))){
	$Table->Open();
		if(isset($_POST['create'])){
			if(!empty($_POST['poll_title'])){
				if(!empty($_POST['Choice_1']) && !empty($_POST['Choice_2'])){
					if($_POST['status'] == 'active'){
						$MySQL->Query("UPDATE ".$pre."Polls SET status='open' WHERE status='active'");
					}
					$insert_poll = 'INSERT INTO `'.$pre.'Polls` (`poll_id`, `poll_title`, `Choice_1`, `Choice_2`, `Choice_3`, `Choice_4`, `Choice_5`, `Choice_6`, `Choice_7`, `Choice_8`, `show_results`, `check_box`, `status`) VALUES (\'\', \''.$_POST['poll_title'].'\', \''.$_POST['Choice_1'].'\', \''.$_POST['Choice_2'].'\', \''.$_POST['Choice_3'].'\', \''.$_POST['Choice_4'].'\', \''.$_POST['Choice_5'].'\', \''.$_POST['Choice_6'].'\', \''.$_POST['Choice_7'].'\', \''.$_POST['Choice_8'].'\', \''.$_POST['show_results'].'\', \''.$_POST['check_box'].'\',\''.$_POST['status'].'\')';
					$MySQL->Query($insert_poll);
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&show_page=1&success=1&poll_title=".$_POST['poll_title']);
                    			die();
                		}else{
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&show_page=1&error=3");
                    			die();
                		}
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&show_page=1&error=2");
                		die();
        		}
		}elseif(isset($_POST['update'])){
			if(isset($_GET['poll_ID'])){
				if(!empty($_POST['poll_title'])){
					if(!empty($_POST['Choice_1']) && !empty($_POST['Choice_2'])){
						if($_POST['status'] == 'active'){
							$MySQL->Query("UPDATE ".$pre."Polls SET status='open' WHERE status='active'");
						}
						$MySQL->Query("UPDATE ".$pre."Polls SET poll_title='".$_POST['poll_title']."', Choice_1='".$_POST['Choice_1']."', Choice_2='".$_POST['Choice_2']."', Choice_3='".$_POST['Choice_3']."', Choice_4='".$_POST['Choice_4']."', Choice_5='".$_POST['Choice_5']."', Choice_6='".$_POST['Choice_6']."', Choice_7='".$_POST['Choice_7']."', Choice_8='".$_POST['Choice_8']."', show_results=".$_POST['show_results'].", check_box=".$_POST['check_box'].", status='".$_POST['status']."' WHERE poll_id = '".$_GET['poll_ID']."' ");
						header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&show_page=1&success=2&poll_title=".$_POST['poll_title']);
                        			die();
                			}else{
						header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&error=3&show_page=1");
                        			die();
                			}
				}else{
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&error=2&show_page=1");
                    			die();
            			}
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&error=1&show_page=1");
                		die();
            		}
		}else{
			if(isset($_GET['poll_ID'])){
				$pinfo = $MySQL->Fetch("SELECT poll_title, check_box, show_results, status, Choice_1, Choice_2, Choice_3, Choice_4, Choice_5, Choice_6, Choice_7, Choice_8 FROM ".$pre."Polls WHERE poll_id = '".$_GET['poll_ID']."'");
			}
			if(isset($_GET['poll_ID'])){
				$GET_pollID = "&amp;poll_ID=".$_GET['poll_ID'];
			}else{
				$GET_pollID = "";
			}
			echo "<form method=\"post\" action=\"index.php?find=Admin_Panel&amp;func=Polls&amp;showpage=1&amp;create_poll=1".$GET_pollID."\">";
			if(isset($_GET['poll_ID'])){
				echo "<input name=\"update\" type=\"hidden\" value=\"1\">";
			}else{
				echo "<input name=\"create\" type=\"hidden\" value=\"1\">";
			}
			echo "<table width=\"100%\"  border=\"0\" cellspacing=\"3\" cellpadding=\"0\">
			  <tr>
				<td width=\"20%\" align=right>"._POLL_TITLE.": </td>
				<td width=\"80%\" align=left><input type=\"text\" name=\"poll_title\" value=\"".$pinfo['poll_title']."\"> ("._REQUIRED.")</td>
			  </tr>
				<tr>
				<td align=right>"._OPTION_TYPE.": </td>
				<td align=left><select name=\"check_box\">
							   <option value=\"1\""; if($pinfo['check_box'] == 1){ echo "selected"; } echo ">"._CHECK_BOX."</option>
							   <option value=\"0\""; if($pinfo['check_box'] != 1){ echo "selected"; } echo ">"._RADIO_BUTTON."</option>
							   </select>
				  </td>
			  </tr>
				<tr>
				<td align=right>"._SHOW_RESULTS.": </td>
				<td align=left><select name=\"show_results\">
							   <option value=\"1\""; if($pinfo['show_results'] == 1){ echo "selected"; } echo ">"._YES."</option>
							   <option value=\"0\""; if($pinfo['show_results'] != 1){ echo "selected"; } echo ">"._NO."</option>
							   </select>
				  </td>
			  </tr>    <tr>
				<td align=right>"._POLL_STATUS.": </td>
				<td align=left><select name=\"status\">
							   <option value=\"active\""; if($pinfo['status'] === "active"){ echo "selected"; } echo ">"._ACTIVE."</option>
							   <option value=\"open\""; if($pinfo['status'] === "open"){ echo "selected"; } echo ">"._OPEN."</option>
							   <option value=\"closed\""; if($pinfo['status'] === "closed"){ echo "selected"; } echo ">"._CLOSED."</option>
							   </select>
				  </td>
			  </tr>    <tr>
				<td align=right>"._OPTION_ONE.": </td>
				<td align=left><input type=\"text\" name=\"Choice_1\" value=\"".$pinfo['Choice_1']."\" maxlength=\"50\"> ("._REQUIRED.")</td>
			  </tr>    <tr>
				<td align=right>"._OPTION_TWO.": </td>
				<td align=left><input type=\"text\" name=\"Choice_2\" value=\"".$pinfo['Choice_2']."\" maxlength=\"50\"> ("._REQUIRED.")</td>
			  </tr>    <tr>
				<td align=right>"._OPTION_THREE.": </td>
				<td align=left><input type=\"text\" name=\"Choice_3\" value=\"".$pinfo['Choice_3']."\" maxlength=\"50\"></td>
			  </tr>    <tr>
				<td align=right>"._OPTION_FOUR.": </td>
				<td align=left><input type=\"text\" name=\"Choice_4\" value=\"".$pinfo['Choice_4']."\" maxlength=\"50\"></td>
			  </tr>    <tr>
				<td align=right>"._OPTION_FIVE.": </td>
				<td align=left><input type=\"text\" name=\"Choice_5\" value=\"".$pinfo['Choice_5']."\" maxlength=\"50\"></td>
			  </tr>    <tr>
				<td align=right>"._OPTION_SIX.": </td>
				<td align=left><input type=\"text\" name=\"Choice_6\" value=\"".$pinfo['Choice_6']."\" maxlength=\"50\"></td>
			  </tr>    <tr>
				<td align=right>"._OPTION_SEVEN.": </td>
				<td align=left><input type=\"text\" name=\"Choice_7\" value=\"".$pinfo['Choice_7']."\" maxlength=\"50\"></td>
			  </tr>    <tr>
				<td align=right>"._OPTION_EIGHT.": </td>
				<td align=left><input type=\"text\" name=\"Choice_8\" value=\"".$pinfo['Choice_8']."\" maxlength=\"50\"></td>
			  </tr>
			  <tr>
			  <td align=right></td>
			  <td align=left><input name=\"Submit\" type=\"submit\" value=\"Submit\"> "; if(isset($_GET['poll_ID'])){ echo "<a href=\"index.php?find=Admin_Panel&amp;func=Polls&amp;delete_poll=1&amp;poll_ID=".$_GET['poll_ID']."\">"._DELETE_POLL."<a/> ?"; } echo " </td>
			  </tr>
			</table>		
			</form>";
		}
	$Table->Close();
}elseif(isset($_GET['delete_poll'])){
	$Table->Open();
		if(isset($_GET['poll_ID'])){
			if(isset($_GET['deleted'])){
				$MySQL->Query("DELETE FROM ".$pre."Polls WHERE poll_id = '".$_GET['poll_ID']."' LIMIT 1");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&deleted_d=1&show_page=1&poll_title=".$_GET['poll_title']);
                		die();
        		}else{
				$pinfo = $MySQL->Fetch("SELECT poll_title FROM ".$pre."Polls WHERE poll_id = '".$_GET['poll_ID']."'");
				echo _ARE_YOU_SURE_ON_DELETE_POLL." '<b>".$pinfo['poll_title']."</b>'?";
				echo "<br><br>";
				echo "<a href=\"index.php?find=Admin_Panel&amp;func=Polls&amp;delete_poll=1&amp;show_page=1&amp;poll_ID=".$_GET['poll_ID']."&amp;deleted=1&amp;poll_title=".$pinfo['poll_title']."\">"._YES."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Polls&amp;deleted_d=no&amp;show_page=1&amp;poll_title=".$pinfo['poll_title']."\">"._NO."</a>";
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Polls&error=1&show_page=1");
            		die();
        	}
	$Table->Close();
}else{
	$Table->Open();
		if($_GET['error'] == 1){
			echo "<br>"._MISSING_POLL_ID."<br>";
		}elseif($_GET['error'] == 2){
			echo "<br>"._NO_TITLE_FILLED."<br>";
		}elseif($_GET['error'] == 3){
			echo "<br>"._NO_OPTIONS_FILLED."<br>";
		}elseif($_GET['success'] == 1){
			echo "<br>"._SUCCESSFULLY_CREATED_POLL."<br>";
		}elseif($_GET['success'] == 2){
			echo "<br>"._SUCCESSFULLY_UPDATED_POLL."<br>";
		}elseif($_GET['deleted_d'] === "no"){
			echo "<br>"._POLL_WAS_NOT_DELETED."<br>";
		}elseif($_GET['deleted_d'] == 1){
			echo "<br>"._POLL_WAS_DELETED."<br>";
		}
		if($MySQL->Rows("SELECT status FROM ".$pre."Polls WHERE status='active'") == 0){
			echo "<br>"._NO_ACTIVE_POLL."<br>";
		}elseif($MySQL->Rows("SELECT status FROM ".$pre."Polls WHERE status='active'") > 1){
			$MySQL->Query("UPDATE ".$pre."Polls SET status='open' WHERE status='active'");
			echo "<br>"._MULTIPLE_ACTIVE_POLLS."<br>";
		}
		
		echo _POLLS_ONLY_DISPLAY. " ". $Show_Polls . " "._LAST_POLLS."<br>"
		."<br><a href=\"index.php?find=Admin_Panel&amp;func=Polls&amp;create_poll=1\">"._START_POLL."</a><br>"
		."<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">"
		."<tr>"
		."<td align=left width=\"55%\" class=table>"._POLL_TITLE."</td>"
		."<td align=left width=\"15%\" class=table>"._TOTAL_VOTES."</td>"
		."<td align=left width=\"15%\" class=table>"._POLL_STATUS."</td>"
		."<td align=left width=\"15%\" class=table>"._EDIT_POLL."</td>"
		."</tr>";

		$Count_Polls = $MySQL->Rows("SELECT poll_id FROM ".$pre."Polls");

		$polla = $MySQL->Query("SELECT status, poll_id, poll_title, Total_Votes FROM ".$pre."Polls ORDER BY poll_id DESC LIMIT ". $Show_Polls);
		while($pinfo = mysql_fetch_array($polla)){
			if($pinfo['status'] === "active"){
				$status = "<font color=\"#FF8000\">active</font>";
			}elseif($pinfo['status'] === "open"){
				$status = "<font color=\"#80FF00\">open</font>";
			}else{
				$status = "<font color=\"#FF0000\">closed</font>";
			}
			echo "<tr>"
			."<td align=left class=table>".$pinfo['poll_title']."</td>"
			."<td align=left class=table>".$pinfo['Total_Votes']."</td>"
			."<td align=left class=table>".$status."</td>"
			."<td align=left class=table><a href=\"index.php?find=Admin_Panel&amp;func=Polls&amp;update_poll=1&amp;poll_ID=".$pinfo['poll_id']."\">"._EDIT_POLL."</a>?</td>"
			."</tr>";
		}
		echo "</table>";
		
		if($Count_Polls == 0){
			echo "<br>"._NO_POLLS."<br><BR>";
		}
	$Table->Close();
}
?>
