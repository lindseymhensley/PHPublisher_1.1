<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

class polls
{
	function polls($MySQL, $pre, $Table, $Current_Directory, $user, $Emoticon_On, $base_url, $Guest_Allowed, $date, $Censor_Words, $BBcode_On, $Show_Polls)
	{
		$this->MySQL = $MySQL;
		$this->pre = $pre;
		$this->Table = $Table;
		$this->Current_Directory = $Current_Directory;
		$this->user = $user;
		$this->Emoticon_On = $Emoticon_On;
		$this->base_url = $base_url;
		$this->Guest_Allowed = $Guest_Allowed;
		$this->date = $date;
		$this->Censor_Words = $Censor_Words;
		$this->BBcode_On = $BBcode_On;
		$this->Show_Polls = $Show_Polls;
	}

	function choice($pnum, $poll_id)
	{
		if(!isset($_SESSION['pinfo'][$poll_id][$pnum])){
			$_SESSION['pinfo'][$poll_id][$pnum] = $this->MySQL->Fetch("SELECT Choice_1, Choice_2, Choice_3, Choice_4, Choice_5, Choice_6, Choice_7, Choice_8, check_box FROM ".$this->pre."Polls WHERE poll_id = '".$poll_id."'");
		}
		if($_SESSION['pinfo'][$poll_id][$pnum]['check_box'] != 1){
		
			if(!empty($_SESSION['pinfo'][$poll_id][$pnum]['Choice_'.$pnum])){
				echo "<tr><td width=\"30%\" align=\"right\"><input name=\"Choice\" type=\"radio\" value=\"".$pnum."\" /> </td><td align=\"left\" width=\"70%\"> ".$_SESSION['pinfo'][$poll_id][$pnum]['Choice_'.$pnum]."</td></tr>";
			}
		
		}else{
			if(!empty($_SESSION['pinfo'][$poll_id][$pnum]['Choice_'.$pnum])){
				$choice = "Choice_".$pnum;
				echo "<tr><td width=\"30%\" align=\"right\"><input name=\"Choice_".$pnum."\" type=\"checkbox\" value=\"".$pnum."\" /> </td><td align=\"left\" width=\"70%\"> ".$_SESSION['pinfo'][$poll_id][$pnum]['Choice_'.$pnum]."</td></tr>";
			}
		}
		
	}
	
	function active($poll_id = "")
	{		
		if(!empty($poll_id)){
			$pinfo = $this->MySQL->Fetch("SELECT status, poll_id, check_box, poll_title, Total_Votes FROM ".$this->pre."Polls WHERE poll_id = '".$poll_id."'");
		}elseif(empty($poll_id)){
			$pinfo = $this->MySQL->Fetch("SELECT status, poll_id, check_box, poll_title, Total_Votes FROM ".$this->pre."Polls WHERE status = 'active'");
		}
		if($pinfo['status'] === 'active' || $pinfo['status'] === 'open'){
			echo "<form action=\"index.php?find=Polls&amp;Vote=1&amp;poll_id=".$pinfo['poll_id']."\" method=\"post\">";
			echo "<input name=\"check_box\" type=\"hidden\" value=\"".$pinfo['check_box']."\" />";
			echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
			echo "<tr><td align=\"center\"><b>".$pinfo['poll_title']."</b><br /><br /></td></tr>";
			echo "<tr><td align=\"center\">";
			echo "<table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";	
			for($i = 1; $i <= 8; $i++){
				$this->choice($i, $pinfo['poll_id']);
			}
			echo "</table><br /><input name=\"Vote\" type=\"submit\" value=\"Vote\" /></td></tr></table></form>";
			echo "<center>[ <a href=\"index.php?find=Polls&amp;show_polls=1\">"._POLLS."</a> | <a href=\"index.php?find=Polls&amp;show_results=1&amp;poll_id=".$pinfo['poll_id']."\">"._RESULTS."</a> ]</center><br />";
			echo "<center>"._TOTAL_VOTES.": ".$pinfo['Total_Votes']."</center><br />";
		}else{
			if(empty($_GET['no_session'])){
				header("Location: ".$this->base_url."/index.php?find=Polls&show_results=1&poll_id=".$poll_id);
				die();
			}
		}
	}
	
	function poll_id()
	{
		$pID = $this->MySQL->Fetch("SELECT poll_id FROM ".$this->pre."Polls WHERE status = 'active'");
		return $pID['poll_id'];
	}
	
	function vote()
	{
		if(isset($_POST['Choice']) || isset($_POST['check_box'])){
			if(isset($_GET['poll_id'])){
				//$pinfo = $this->MySQL->Fetch("SELECT * FROM ".$this->pre."Polls WHERE poll_id = '".$_GET['poll_id']."'");
				if($_POST['check_box'] == 1){
					for($i = 1; $i <= 8; $i++){
						if(isset($_POST['Choice_'.$i])){
							$this->MySQL->Query("UPDATE ".$this->pre."Polls SET Answer_".$i."=Answer_".$i."+1, Total_Votes=Total_Votes+1 WHERE poll_id = '".$_GET['poll_id']."'");
						}
					}
					setcookie("VOTED_ON", $_GET['poll_id']);
				}else{
					$this->MySQL->Query("UPDATE ".$this->pre."Polls SET Answer_".$_POST['Choice']."=Answer_".$_POST['Choice']."+1, Total_Votes=Total_Votes+1 WHERE poll_id = '".$_GET['poll_id']."'");
					setcookie("VOTED_ON", $_GET['poll_id']);
				}
			}else{
				header("Location: ".$this->base_url."/index.php");
				die();
			}
		}else{
			header("Location: ".$this->base_url."/index.php");
			die();
		}
		
	}
	
	function results()
	{
		if(isset($_GET['poll_id'])){
			$pinfo = $this->MySQL->Fetch("SELECT poll_title, Answer_1, Answer_2, Answer_3, Answer_4, Answer_5, Answer_6, Answer_7, Answer_8, Choice_1, Choice_2, Choice_3, Choice_4, Choice_5, Choice_6, Choice_7, Choice_8, Total_Votes, show_results FROM ".$this->pre."Polls WHERE poll_id = '".$_GET['poll_id']."'");
			if($this->user->lvl() == 99){
				$pinfo['show_results'] = 1;
			}
			if($pinfo['show_results'] == 1){
				$this->Table->Open("<b>".$pinfo['poll_title']."</b>");
				echo "<table width=\"*%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"3\" align=\"center\">";
				for($i = 1; $i <= 8; $i++){
					if(!empty($pinfo['Choice_'.$i])){ 
						if($pinfo['Total_Votes'] > 0){
							$per[$i] = $pinfo['Answer_'.$i] / $pinfo['Total_Votes'];
							if($pinfo['Answer_'.$i] < $pinfo['Total_Votes']){
								$percentage = explode("0.", number_format($per[$i], 2));
							}else{
								$percentage[1] = 100;
							}
							$px[$i] = ceil($percentage[1] * 1.5);
						}else{
							$px[$i] = 1;
							$percentage[1] = 0;
						}
						echo "<tr>
						<td align=\"left\" width=\"*%\">".$pinfo['Choice_'.$i]." </td>
						<td width=\"150px\" align=\"left\"><img src=\"".$this->base_url."/images/Choice_".$i.".gif\" width=\"".$px[$i]."px\" height=\"10px\"></td>
						<td align=\"left\" width=\"*%\">".$percentage[1]."% (".$pinfo['Answer_'.$i].")</td>
						</tr>";
					}
				}
				echo "</table><br />";
				$this->Table->Close();
				echo "<br /><br />";
				include($this->Current_Directory."/Modules/Polls/comments.php");
			}elseif($pinfo['show_results'] == 0){
				$this->Table->Open();
					echo "<br />"._NOT_DISPLAYING_RESULTS."<br /><br />";
				$this->Table->Close();
			}else{
				$this->Table->Open();
					echo _VOTE_SENT;
				$this->Table->Close();
			}
		}else{
			header("Location: ".$this->base_url."/index.php");
            die();
		}
	}
	
	function show_polls(){
		$Count_Polls = $this->MySQL->Rows("SELECT poll_id FROM ".$this->pre."Polls");

		$info = $this->MySQL->Query("SELECT poll_id, poll_title, Total_Votes, status FROM ".$this->pre."Polls ORDER BY poll_id DESC LIMIT ". $this->Show_Polls);		
		echo _POLLS_ONLY_DISPLAY." ".$this->Show_Polls." "._LAST_POLLS."<br /><br /><table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">";
		while($pinfo = mysql_fetch_array($info)){
			echo "<tr><td align=\"left\"><a href=\"index.php?find=Polls&amp;show_poll=1&amp;poll_id=".$pinfo['poll_id']."\">".$pinfo['poll_title'] . "</a> &nbsp; ( <i>"._TOTAL_VOTES.": ".$pinfo['Total_Votes']."</i> ) &nbsp; :: ".$pinfo['status']."</td></tr>";
		}
		echo "</table><br />";
		
		if($Count_Polls == 0){
			echo "<br />"._NO_POLLS."<br /><br />";
		}
		
	}
}

?>
