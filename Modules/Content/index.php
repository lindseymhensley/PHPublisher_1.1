<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}


if(isset($_GET['content_id']) && !is_numeric($_GET['content_id'])){
	header("Location: ".$base_url."/index.php");
	die();
}

if(isset($_GET['content_id'])){
	$content = $MySQL->Fetch("SELECT * FROM ".$pre."content WHERE content_id = ".$_GET['content_id']);	
	$group_id = explode("Group-", $content['access']);
	if(empty($group_id[1])){
		$group = false;
		switch ($group[0])
		{
			case 0:
				$canview = 1;
			break;
	
			case 1:
				if($user->id() > 1){
					$canview = 1;
				}
			break;
			
			case 2:
				if($user->lvl() >= 2){
					$canview = 1;
				}
			break;
			
			case 3:
				if($user->lvl() >= 3){
					$canview = 1;
				}
			break;
			
			case 99:
				if($user->lvl() == 99){
					$canview = 1;
				}
			break;
		}
	}else{
		$group = true;
		if($group_id[1] == $user->group()){
			$canview = 1;
		}
		if($user->lvl() >= 3){
			$canview = 1;
		}
	}

    switch ($canview)
    {
        case 1:
    		if($content['content_id'] == $_GET['content_id']){
    			ob_start();
    				$Cntnt = $content['content'];
    				if($Censor_Words == 1){
    					$Cntnt = censor($Cntnt);
    				}
    				if($Emoticon_On == 1){
    					$Cntnt = emoticon($Cntnt);
    				}
    				if($BBcode_On == 1){
    					$Cntnt = bbcode($Cntnt);
    				}
					echo eval("?>". $Cntnt."<?");
    				$Contents = ob_get_contents();
    			ob_end_clean();

    			$Table->Open("<b>".$content['title']."</b>");
				if($content['headers'] == 1){
					echo "<table width=\"100%\"  border=\"0\" cellspacing=\"3\" cellpadding=\"0\">
					  <tr>
						<td align=\"left\"class=table width=\"10%\">"._TITLE.":</td><td align=\"left\" class=table width=\"90%\"> ".$content['title']."</td>
					  </tr>
					  <tr>
						<td  align=\"left\" class=table>"._DESCRIPTION.":</td><td  align=\"left\" class=table> ".$content['description']."</td>
					  </tr>
					  <tr>
						<td  align=\"left\" class=table>"._POSTED_ON.":</td><td  align=\"left\" class=table> ".$content['created_on']."</td>
					  </tr>
					  <tr>
						<td  align=\"left\" class=table>"._VIEWS.":</td><td  align=\"left\" class=table> ".$content['views']."</td>
					  </tr>
					  <tr>
						<td  align=\"left\" class=table colspan=2 style=\"text-align: justify;\">".$Contents."</td>
					  </tr>
					</table>";
				}else{
					echo "<table width=\"100%\"  border=\"0\" cellspacing=\"3\" cellpadding=\"0\">
					<tr><td align=\"left\" style=\"text-align: justify;\">".$Contents."</td></tr>
					</table>";
				}
    			$Table->Close();
    			$MySQL->Query("UPDATE ".$pre."content SET views=views+1 WHERE content_id = '".$content['content_id']."'");
    		}else{
    			$Table->Open();
    			echo _PAGE_DOESNT_EXIST;
    			$Table->Close();
    		}
        break;
    
        default:
			if($group == false){
				switch ($content['access'])
				{
					case 1:
						$Table->Open();
						echo _ONLY_REGISTERED_USERS;
						$Table->Close();
					break;
					
					case 2:
						$Table->Open();
						echo _ONLY_PUBLISHERS;
						$Table->Close();
					break;
					
					case 3:
						$Table->Open();
						echo _ONLY_MODERATORS;
						$Table->Close();
					break;
					
					case 4:
						$Table->Open();
						echo _ONLY_ADMINS;
						$Table->Close();
					break;
				}
			}elseif($group == true){
				$Table->Open();
						$name = $MySQL->Fetch("SELECT * FROM ".$pre."user_groups WHERE group_id = '".$group_id[1]."'");
						echo _ONLY_GROUP." ".$name['name']." "._MAY_VIEW;
				$Table->Close();
			}
        break;
	}
		
}else{
	$Table->Open();
	$Count_Content = $MySQL->Rows("SELECT content_id FROM ".$pre."content");
	$Count_Div = $Count_Content / 25;
	$Ro_Count = ceil($Count_Div);
	if(!isset($_GET['show_page']) || empty($_GET['show_page'])){
		$_GET['show_page'] = 1;
	}
	
	if($_GET['show_page'] > $Ro_Count){
		if($Count_Content <= 0){
			$nocontent = 1;
		}elseif($Count_Content > 0){
			$_GET['show_page'] = 1;
		}
	}
	
	$i = 1;
	for($i = 1; $i <= $Ro_Count; $i++){
		if($_GET['show_page'] == $i){
			$second = $i * 25;
			$first = $second - 25;
			$limit_show = "".$first.", 25"; 
		}
	}
	if(isset($nocontent)){
		$limit_show = 1;
	}
	$find_content = $MySQL->Query("SELECT * FROM ".$pre."content LIMIT ".$limit_show);	
	echo "<br><table width=\"100%\" cellspacing=0 cellpadding=0>";
	while($content = mysql_fetch_array($find_content)){
		$group_id = explode("Group-", $content['access']);
		if(empty($group_id[1])){
			switch($content['access'])
			{
				case 1:
					$viewableby = "(<em>Registered Users Only</em>)";
				break;
				
				case 2:
					$viewableby = "(<em>Article Publishers Only</em>)";
				break;
				
				case 3:
					$viewableby = "(<em>Moderators Only</em>)";
				break;
				
				case 99:
					$viewableby = "(<em>Administration Only</em>)";
				break;
			}			
		}else{
			$group = $MySQL->Fetch("SELECT * FROM ".$pre."user_groups WHERE group_id = '".$group_id[1]."'");
			$viewableby = "(<em>Members of the group <strong>".$group['name']."</strong></em>)";
		}
		echo "<tr class=table><td  align=\"left\"><table width=\"100%\" cellspacing=3 cellpadding=0><tr><td  align=\"left\" class=table width=15%>"._TITLE.": </td><td  align=\"left\" class=table width=85%><a href=\"index.php?find=Content&amp;content_id=".$content['content_id']."\">".$content['title']."</a> ".$viewableby."</td></tr><tr><td  align=\"left\" class=table>"._DESCRIPTION.": </td><td  align=\"left\" class=table><em>".$content['description']." ( ".$content['views']." )</em></td></tr></table></td></tr><tr><td></td></tr>";
	}
	echo "</table>";

	if(isset($nocontent)){
		echo _NO_CONTENT."<br><br>";
	}else{			
		echo "<strong>"._CONTENT_PAGE.":</strong> ";
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
			echo "<option value=\"index.php?find=Content&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
		}
		echo "</select></form>";
	}
	$Table->Close();
}

?> 
