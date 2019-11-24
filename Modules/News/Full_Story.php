<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if(empty($_GET['show_page'])){
	$_GET['show_page'] = 1;
}

if(isset($_GET['Show_Full']) && isset($_GET['Article_ID'])){
	if(!is_numeric($_GET['Article_ID'])){
		header("Location: ".$base_url."/index.php");
		die();
	}
	
	if(($post = $MySQL->Fetch("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Date, Article_Author FROM ".$pre."News_Articles WHERE Article_ID = '".$_GET['Article_ID']."'")) !== false){		
		$Table->Open();	
		if(empty($_SESSION['Art_Ses'][$_GET['Article_ID']])){
			$_SESSION['Article_ID'][$_GET['Article_ID']] = $post['Article_ID'];
			$_SESSION['Article_Title'][$_GET['Article_ID']] = $post['Article_Title'];
			$_SESSION['Article_Topic'][$_GET['Article_ID']] = "<b><a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$post['Article_Topic']."\">".$post['Article_Topic']."</a>:</b>";
			if($_SESSION['Article_Topic'][$_GET['Article_ID']] != "none.gif" && isset($_SESSION['Article_Topic'][$_GET['Article_ID']])){
				$topic = $MySQL->Fetch("SELECT img FROM ".$pre."topics WHERE name = '".$post['Article_Topic']."'");
				$_SESSION['Article_Topic_Img'][$_GET['Article_ID']] = "<img src=\"".$base_url."/images/topics/".$topic['img']."\">";
			}else{
				$_SESSION['Article_Topic_Img'][$_GET['Article_ID']] = "";
			}
			$_SESSION['Article_Story'][$_GET['Article_ID']] = nl2br($post['Article_Story']);
			$_SESSION['Article_Full'][$_GET['Article_ID']] = nl2br($post['Article_Full_Story']);
			$_SESSION['Article_Views'][$_GET['Article_ID']] = $post['Article_Views'];
			switch($Censor_Words)
			{
				case 1:
					$_SESSION['Article_Full'][$_GET['Article_ID']] = censor($_SESSION['Article_Full'][$_GET['Article_ID']]);
					$_SESSION['Article_Story'][$_GET['Article_ID']] = censor($_SESSION['Article_Story'][$_GET['Article_ID']]);
				break;
			}
			switch($Emoticon_On)
			{
				case 1:
					$_SESSION['Article_Full'][$_GET['Article_ID']] = emoticon($_SESSION['Article_Full'][$_GET['Article_ID']]);
					$_SESSION['Article_Story'][$_GET['Article_ID']] = emoticon($_SESSION['Article_Story'][$_GET['Article_ID']]);
				break;
			}
			switch($BBcode_On == 1)
			{
				case 1:
					$_SESSION['Article_Full'][$_GET['Article_ID']] = bbcode($_SESSION['Article_Full'][$_GET['Article_ID']]);
					$_SESSION['Article_Story'][$_GET['Article_ID']] = bbcode($_SESSION['Article_Story'][$_GET['Article_ID']]);
				break;
			}
			$_SESSION['Comment_Count'][$_GET['Article_ID']] = $MySQL->Rows("SELECT Comment_ID FROM ".$pre."Article_Comments WHERE Story_ID = ".$_SESSION['Article_ID'][$_GET['Article_ID']]."");
			$_SESSION['Article_Date'][$_GET['Article_ID']] = $post['Article_Date'];
			$_SESSION['Article_Author'][$_GET['Article_ID']] = _POSTED_BY ." ". $post['Article_Author'];
			
			$_SESSION['Art_Ses'][$_GET['Article_ID']] = $post['Article_ID'];
			header("Location: ".$base_url."/index.php?find=News&show_page=".$_GET['show_page']."&file=Full_Story&Show_Full=1&Article_ID=".$_GET['Article_ID']);
		}
		$MySQL->Query("UPDATE ".$pre."News_Articles SET Article_Views=Article_Views+1 WHERE Article_ID = '".$_GET['Article_ID']."'");
		$_SESSION['Article_Views'][$_GET['Article_ID']]++;
		$_SESSION['Articles_Views'][$_GET['Article_ID']]++;
		echo "<div align=left>";
		echo "<b>"._TOPIC."/ "._TITLE.":</b> ".$_SESSION['Article_Topic'][$_GET['Article_ID']]." ".$_SESSION['Article_Title'][$_GET['Article_ID']];
		echo "<br /><b>"._POSTED_ON.":</b> ".$_SESSION['Article_Date'][$_GET['Article_ID']];
		echo "<br /><b>"._AUTHOR.":</b> ".$_SESSION['Article_Author'][$_GET['Article_ID']];
		echo "<br /><b>"._READS.":</b> ".$_SESSION['Article_Views'][$_GET['Article_ID']]."<br /><br />";
		echo "<b>"._STORY.":</b><br /> ".$_SESSION['Article_Story'][$_GET['Article_ID']];
		if(!empty($_SESSION['Article_Full'][$_GET['Article_ID']])){
			echo "<br /><br /><b>"._FULL_STORY.":</b><br /> ".$_SESSION['Article_Full'][$_GET['Article_ID']];	
		}
		echo "</div>";
		$Table->Close();
		echo "<br />";	
	
		if($_POST['Submit']){
			if(empty($_POST['Comment_Content'])){
				echo _COMMENT_NOT_ADDED."<br /><br />";	
			}elseif(isset($_POST['Comment_Content'])){
				if(isset($_POST['update_comment'])){
					$MySQL->Query("UPDATE ".$pre."Article_Comments SET Comment_Content = '".$_POST['Comment_Content']."' WHERE Comment_ID = '".$_POST['Comment_ID']."' AND Story_ID = '".$_POST['PG_ID']."'");
					echo _COMMENT_UPDATED."<br /><br />";
					unset($_SESSION['Comments_Set']);
					unset($_SESSION['Comment_Date']);
					unset($_SESSION['Comment_Author']);
					unset($_SESSION['Comment']);
					unset($_SESSION['Comment_ID']);				
					unset($_SESSION['Comments_Page']);
					unset($_SESSION['Com']);
				}else{
					if($user->id() > 1){
						$_POST['email'] = $user->extract('user_email');
						$_POST['name'] = $user->name();
						$name = "<a href=\"index.php?find=Profile&amp;user_id=".$user->id()."\">".$_POST['name']."</a>";
					}else{
						$check_userlist = $MySQL->Rows("SELECT username FROM ".$pre."users WHERE username = '".$_POST['name']."'");
						if($check_userlist > 0){
							header("Location: ".$base_url."/index.php?find=News&show_page=".$_GET['show_page']."&file=Full_Story&Show_Full=1&Article_ID=".$_GET['Article_ID']."&error=user_already_registered&Comment_Content=".$_POST['Comment_Content']);
							die();
						}
						$name = "<a href=\"mailto:".$_POST['email']."\">".$_POST['name']."</a>";
					}
					if(ereg('^[a-zA-Z0-9_-]+$', $_POST['name'])){
						if(strpos($_POST['email'], '@')){
							if(empty($_POST['email'])){
								echo _EMAIL_MISSING;
							}elseif(isset($_POST['email'])){
								$Insert_Comment = 'INSERT INTO `'.$pre.'Article_Comments` (`Comment_ID`, `Story_ID`, `Comment_Author`, `Comment_Date`, `Comment_Time`, `Comment_Content`) VALUES (\'\', \''.$_GET['Article_ID'].'\', \''.$name.'\', \''.$date.'\', \''.time().'\', \''.$_POST['Comment_Content'].'\')';
								if($user->id() >= 2){
									$MySQL->Query("UPDATE ".$pre."users SET user_posts=user_posts+1 WHERE user_id = '".$user->id()."'");
								}
								$MySQL->Query($Insert_Comment);
								echo _COMMENT_ADDED."<br /><br />";
								unset($_SESSION['Comments_Set']);
								unset($_SESSION['Comment_Date']);
								unset($_SESSION['Comment_Author']);
								unset($_SESSION['Comment']);
								unset($_SESSION['Comment_ID']);				
								unset($_SESSION['Comments_Page']);
								unset($_SESSION['Com']);
							}							
						}else{
							header("Location: ".$base_url."/index.php?find=News&show_page=".$_GET['show_page']."&file=Full_Story&Show_Full=1&Article_ID=".$_GET['Article_ID']."&error=illegal_email&Comment_Content=".$_POST['Comment_Content']);
							die();
						}
					}else{
						header("Location: ".$base_url."/index.php?find=News&show_page=".$_GET['show_page']."&file=Full_Story&Show_Full=1&Article_ID=".$_GET['Article_ID']."&error=illegal_name&Comment_Content=".$_POST['Comment_Content']);
						die();
					}
				}
			}
		}
		
		switch($_GET['Yes_Deleted'])
		{
			case TRUE:
				echo "<b>"._COMMENT_REMOVED."</b><br /><br />";
			break;
		}
		switch($_GET['Not_Deleted'])
		{
			case TRUE:
				echo "<b>"._COMMENT_DOESNT_EXIST."</b><br /><br />";
			break;
		}		
		switch($_GET['error']){
			case "illegal_name":
				echo "<b>"._ILLEGAL_NAME."</b><br /><br />";
			break;
			case "illegal_email":
				echo "<b>"._ILLEGAL_EMAIL."</b><br /><br />";
			break;
			case "user_already_registered":
				echo "<b>"._USER_REGISTERED."</b><br /><br />";
			break;
		}
		
		if(isset($_GET['Delete'])){
			if(isset($_GET['Confirm'])){
				if($user->lvl() >= 3){
					if(isset($_GET['Comment_ID'])){
						$Check_Comment_Exists = $MySQL->Fetch("SELECT * FROM ".$pre."Article_Comments WHERE Comment_ID = '".$_GET['Comment_ID']."'");
						if($Comment_ID == $Check_Comment_Exists['Comment_ID']){
							$MySQL->Query("DELETE FROM ".$pre."Article_Comments WHERE Comment_ID = '".$Check_Comment_Exists['Comment_ID']."' LIMIT 1");
							$_SESSION['Comment_Count'][$_GET['Article_ID']]--;
							$_SESSION['Comments_Count'][$_GET['Article_ID']]--;
							if($_SESSION['Comment_Count'][$_GET['Article_ID']] < 0 || $_SESSION['Comments_Count'][$_GET['Article_ID']] < 0){
								$_SESSION['Comment_Count'][$_GET['Article_ID']] = 0;
								$_SESSION['Comments_Count'][$_GET['Article_ID']] = 0;
							}
							header("Location: ".$base_url."/index.php?find=News&show_page=".$_GET['show_page']."&file=Full_Story&Show_Full=1&Article_ID=".$_GET['Article_ID']."&Yes_Deleted=1");
							die();
						}else{
							header("Location: ".$base_url."/index.php?find=News&file=Full_Story&show_page=".$_GET['show_page']."&Show_Full=1&Article_ID=".$_GET['Article_ID']."&Not_Deleted=1");
							die();
						}
					}
				}
			}else{
				echo _DELETE_THIS_COMMENT."<br /><br />";
				echo "<a href=\"index.php?find=News&amp;file=Full_Story&amp;Show_Full=1&amp;Article_ID=".$_GET['Article_ID']."&amp;Comment_ID=".$_GET['Comment_ID']."&amp;Delete=1&amp;Confirm=1\">"._YES."</a> | <a href=\"index.php?find=News&amp;Show_Full=1&amp;Article_ID=".$_GET['Article_ID']."\">"._NO."</a>";
				unset($_SESSION['Comments_Set']);
				unset($_SESSION['Comment_Date']);
				unset($_SESSION['Comment_Author']);
				unset($_SESSION['Comment']);
				unset($_SESSION['Comment_ID']);				
				unset($_SESSION['Comments_Page']);
				unset($_SESSION['Com']);
				$Comm = $MySQL->Fetch("SELECT Comment_Date, Comment_Author, Comment_Content, Comment_ID FROM ".$pre."Article_Comments WHERE Comment_ID = '".$_GET['Comment_ID']."' ");
				$Comment_Date = $Comm['Comment_Date'];
				$Comment_Author = $Comm['Comment_Author'];
				$Comment = nl2br(htmlspecialchars($Comm['Comment_Content']));
				$Comment_ID = $Comm['Comment_ID'];
				$ca = explode(">", $Comm['Comment_Author']);
				$cb = explode("<", $ca[1]);
				$cauthor = $cb[0];
				$causer = $MySQL->Fetch("SELECT user_posts FROM ".$pre."users WHERE username = '".$cauthor."'");
				if($Censor_Words == 1){
					$Comment = censor($Comment);
				}
				if($Emoticon_On == 1){
					$Comment = emoticon($Comment);
				}
				if($BBcode_On == 1){
					$Comment = bbcode($Comment);
				}
				$user->theme("comment.tpl", array("Author_Posts" => $causer['user_posts'], "url" => $base_url, "Comment_Date" => $Comment_Date, "Comment_Author" => $Comment_Author,"Comment_Content" => $Comment));
			}
		}else{
			if(empty($_SESSION['Com']['count'][$_GET['Article_ID']])){
				$_SESSION['Com']['count'][$_GET['Article_ID']] = $MySQL->Rows("SELECT Story_ID FROM ".$pre."Article_Comments WHERE Story_ID = '".$_GET['Article_ID']."'");
			}
			$Count_Div = $_SESSION['Com']['count'][$_GET['Article_ID']] / 5;
			$Ro_Count = ceil($Count_Div);
			if(!isset($_GET['show_page']) || empty($_GET['show_page'])){
				$_GET['show_page'] = 1;
			}
			
			if($_GET['show_page'] > $Ro_Count){
				if($_SESSION['Com']['count'][$_GET['Article_ID']] <= 0){
					$nocomments = 1;
				}elseif($_SESSION['Com']['count'][$_GET['Article_ID']] > 0){
					$_GET['show_page'] = 1;
				}
			}
			
			$i = 1;
			for($i = 1; $i <= $Ro_Count; $i++){
				if($_GET['show_page'] == $i){
					$second = $i * 5;
					$first = $second - 5;
					$limit_show = "".$first.", 5"; 
				}
			}
			if(isset($nocomments)){
				$limit_show = 1;
			}elseif($_SESSION['Com']['count'][$_GET['Article_ID']] <= 5){
				$limit_show = $_SESSION['Com']['count'][$_GET['Article_ID']];
			}
			if(empty($_SESSION['Com']['SQL'][$_GET['Article_ID']])){
				$_SESSION['Com']['SQL'][$_GET['Article_ID']] = $MySQL->Query("SELECT Comment_Date, Comment_Author, Comment_Content, Comment_ID FROM ".$pre."Article_Comments WHERE Story_ID = ".$_GET['Article_ID']." ORDER BY Comment_Time ASC LIMIT ".$limit_show);
			}
			$i = 1;			
			while($Comments = mysql_fetch_array($_SESSION['Com']['SQL'][$_GET['Article_ID']])){				
				if($user->lvl() >= 3){
					$Delete_Comment = "<a href=\"index.php?find=News&amp;show_page=".$_GET['show_page']."&amp;file=Full_Story&amp;Show_Full=1&amp;Article_ID=".$_GET['Article_ID']."&amp;Comment_ID=".$Comments['Comment_ID']."&amp;Delete=1\">"._DELETE_COMMENT."</a>";
				}	
				
				$ca = explode(">", $Comments['Comment_Author']);
				$cb = explode("<", $ca[1]);
				$cauthor = $cb[0]; 
				
				if(empty($_GET['show_page'])){
					$_GET['show_page'] = 1;
				}
				if(($user->name() == $cauthor) || ($user->lvl() >= 3)){
					$Edit_Comment = "<a href=\"index.php?find=News&amp;file=Full_Story&amp;show_page=".$_GET['show_page']."&amp;Show_Full=1&amp;Article_ID=".$_GET['Article_ID']."&amp;Comment_ID=".$Comments['Comment_ID']."&amp;Edit=1#Post_Comment\">"._EDIT_COMMENT."</a>";
				}
				
				if((empty($_SESSION['Comments_Set'][$_GET['Article_ID']]) || $_SESSION['Comments_Set'][$_GET['Article_ID']] <= time()) && ($_SESSION['Comments_Page'][$_GET['Article_ID']] !== $_GET['show_page'])){
					$session_comments = 1;
				}else{
					if(($_SESSION['Comments_Page'][$_GET['Article_ID']] !== $_GET['show_page'])){
						$session_comments = 1;
					}
				}

				if(isset($session_comments)){
					$_SESSION['Comment_Date'][$_GET['Article_ID']][$i] = $Comments['Comment_Date'];
					$_SESSION['Comment_Author'][$_GET['Article_ID']][$i] = $Comments['Comment_Author'];
					$_SESSION['Comment'][$_GET['Article_ID']][$i] = nl2br(htmlspecialchars($Comments['Comment_Content']));
					$_SESSION['Comment_ID'][$_GET['Article_ID']][$i] = $Comments['Comment_ID'];

					if(user_info($cauthor, "user_id") !== false){
						$user_avatar = user_info($cauthor, "user_avatar");
						$user_sig = user_info($cauthor, "user_sig");
						$user_posts = user_info($cauthor, "user_posts");
						$user_group = user_info($cauthor, "user_group");
						if(!empty($user_avatar)){
							$find_avatar = @fopen($user_avatar, "r");
							if($find_avatar !== false){
								$size = GetImageSize($user_avatar);
								$aspectRat = (float)($size[1] / $size[0]);
					
								if(($size[0] <= 50) && ($size[1] <= 50)){
									$_SESSION['Comment_Author_Avatar'][$_GET['Article_ID']][$cauthor] =  "<img src=\"".$user_avatar."\" alt=\"avatar\" width=\"".$size[0]."\" height=\"".$size[1]."\"/>";
								}else{
									$width_ratio = $size[0] / 50;
									$height_ratio = $size[1] / 50;
					
									if($width_ratio >= $height_ratio){
									  $newY = 50 * $aspectRat;
									  $newX = 50;
									}else{
									  $newY = 50;
									  $newX = 50 * (1/$aspectRat);
									}
									$_SESSION['Comment_Author_Avatar'][$_GET['Article_ID']][$cauthor] = "<img src=\"".$user_avatar."\" alt=\"avatar\" width=\"".$newX."\" height=\"".$newY."\"/>";
								}
							}else{
								$_SESSION['Comment_Author_Avatar'][$_GET['Article_ID']][$cauthor] = "[X]";
							}
						}					
						$_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor] = nl2br(htmlspecialchars($user_sig));
						$_SESSION['Comment_Author_Posts'][$_GET['Article_ID']][$cauthor] = $user_posts;
						$_SESSION['Comment_Author_Group'][$_GET['Article_ID']][$cauthor] = $user_group;
					}else{
						$_SESSION['Comment_Author_Avatar'][$_GET['Article_ID']][$cauthor] = "";
						$_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor] = "";
						$_SESSION['Comment_Author_Posts'][$_GET['Article_ID']][$cauthor] = 1;
						$_SESSION['Comment_Author_Group'][$_GET['Article_ID']][$cauthor] = "None";
					}

					switch($Censor_Words)
					{
						case 1:
							$_SESSION['Comment'][$_GET['Article_ID']][$i] = censor($_SESSION['Comment'][$_GET['Article_ID']][$i]);
							$_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor] = censor($_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor]);
						break;
					}
					switch($Emoticon_On)
					{
						case 1:
							$_SESSION['Comment'][$_GET['Article_ID']][$i] = emoticon($_SESSION['Comment'][$_GET['Article_ID']][$i]);
							$_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor] = emoticon($_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor]);
						break;
					}
					switch($BBcode_On)
					{
						case 1:
							$_SESSION['Comment'][$_GET['Article_ID']][$i] = bbcode($_SESSION['Comment'][$_GET['Article_ID']][$i]);
							$_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor] = bbcode($_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor]);
						break;
					}
					
					if($_GET['show_page'] == $Ro_Count){
						$difference = $_SESSION['Com']['count'][$_GET['Article_ID']] - $first;
					}else{
						$difference = 5;
					}
					if(($i == $difference) && isset($session_comments)){
						if(isset($_GET['Not_Deleted'])){
							$add_deleted = "&amp;Not_Deleted=1";
						}elseif(isset($_GET['Yes_Deleted'])){
							$add_deleted = "&amp;Yes_Deleted=1";
						}
						if(empty($_GET['show_page'])){
							$_GET['show_page'] = 1;
						}
						$_SESSION['Comments_Set'][$_GET['Article_ID']] = time()+500;
						$_SESSION['Comments_Page'][$_GET['Article_ID']] = $_GET['show_page'];
						header("Location: ".$base_url."/index.php?find=News&show_page=".$_GET['show_page']."&file=Full_Story&Show_Full=1&Article_ID=".$_GET['Article_ID'].$add_deleted);
						die();
					}
				}
				$user->theme("comment.tpl", array("Author_Group" => $_SESSION['Comment_Author_Group'][$_GET['Article_ID']][$cauthor], "Edit_Comment" => $Edit_Comment, "Author_Avatar" => $_SESSION['Comment_Author_Avatar'][$_GET['Article_ID']][$cauthor], "Author_Sig" => $_SESSION['Comment_Author_Sig'][$_GET['Article_ID']][$cauthor], "Author_Posts" => $_SESSION['Comment_Author_Posts'][$_GET['Article_ID']][$cauthor], "url" => $base_url, "Comment_Date" => $_SESSION['Comment_Date'][$_GET['Article_ID']][$i], "Comment_Author" => $_SESSION['Comment_Author'][$_GET['Article_ID']][$i], "Comment_Content" => $_SESSION['Comment'][$_GET['Article_ID']][$i], "Delete_Comment" => $Delete_Comment));
				$i++;
			}

			if($_SESSION['Com']['count'][$_GET['Article_ID']] > 5){
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
					echo "<option value=\"index.php?find=News&amp;file=Full_Story&amp;Article_ID=".$_GET['Article_ID']."&amp;Show_Full=1&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
				}
				echo "</select></form>";
			}

			$fvalues = "?find=News&amp;file=Full_Story&amp;show_page=".$_GET['show_page']."&amp;Article_ID=".$_GET['Article_ID']."&amp;Show_Full=1";
			post_comment($fvalues);
		}
	}
}else{
	header("location: ".$base_url."/index.php");
	die();
}

?>