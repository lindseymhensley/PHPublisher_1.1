<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if(empty($_GET['show_page'])){
	$_GET['show_page'] = 1;
}
if(isset($_GET['vote'])){
	if(empty($_POST['Comment_Content'])){
		echo _COMMENT_NOT_ADDED."<br><br>";
	}elseif(isset($_POST['Comment_Content'])){
		if(isset($_POST['update_comment'])){
			$this->MySQL->Query("UPDATE ".$this->pre."Poll_Comments SET Comment_Content = '".$_POST['Comment_Content']."' WHERE Comment_ID = '".$_POST['Comment_ID']."' AND Poll_ID = '".$_POST['PG_ID']."'");
			echo _COMMENT_UPDATED."<br /><br />";
			unset($_SESSION['Poll_Comments_Set']);
			unset($_SESSION['Poll_Comment_Date']);
			unset($_SESSION['Poll_Comment_Author']);
			unset($_SESSION['Poll_Comment']);
			unset($_SESSION['Poll_Comment_ID']);	
			unset($_SESSION['Poll_Comments_Page']);
			unset($_SESSION['Poll_Count']);
			unset($_SESSION['poll_sql']);
		}else{
			if($this->user->id() > 1){
				$_POST['email'] = $this->user->extract('user_email');
				$_POST['name'] = $this->user->name();
			}else{
				$check_userlist = $this->MySQL->Rows("SELECT username FROM ".$this->pre."users WHERE username = '".$_POST['name']."'");
				if($check_userlist > 0){
					header("Location: ".$this->base_url."/index.php?find=Polls&show_page=".$_GET['show_page']."&show_results=1&poll_id=".$_GET['poll_id']."&error=user_already_registered&Comment_Content=".$_POST['Comment_Content']);
					die();
				}
			}
			if(ereg('^[a-zA-Z0-9_-]+$', $_POST['name'])){
				if(strpos($_POST['email'], '@')){
					if(empty($_POST['email'])){
						echo _EMAIL_MISSING;
					}elseif(isset($_POST['email'])){
						$name = "<a href=\"mailto:".$_POST['email']."\">".$_POST['name']."</a>";
						$Insert_Comment = 'INSERT INTO `'.$this->pre.'Poll_Comments` (`Comment_ID`, `Poll_ID`, `Comment_Author`, `Comment_Date`, `Comment_Time`, `Comment_Content`) VALUES (\'\', \''.$_GET['poll_id'].'\', \''.$name.'\', \''.$this->date.'\', \''.time().'\', \''.$_POST['Comment_Content'].'\')';
						if($this->user->id() >= 2){
								$this->MySQL->Query("UPDATE ".$this->pre."users SET user_posts=user_posts+1 WHERE user_id = '".$this->user->id()."'");
						}
						$this->MySQL->Query($Insert_Comment);
						echo _COMMENT_ADDED."<br><br>";
						unset($_SESSION['Poll_Comments_Set']);
						unset($_SESSION['Poll_Comment_Date']);
						unset($_SESSION['Poll_Comment_Author']);
						unset($_SESSION['Poll_Comment']);
						unset($_SESSION['Poll_Comment_ID']);	
						unset($_SESSION['Poll_Comments_Page']);
						unset($_SESSION['Poll_Count']);
						unset($_SESSION['poll_sql']);
					}
				}else{
					header("Location: ".$this->base_url."/index.php?find=Polls&show_page=".$_GET['show_page']."&show_results=1&poll_id=".$_GET['poll_id']."&error=illegal_email&Comment_Content=".$_POST['Comment_Content']);
					die();
				}
			}else{
				header("Location: ".$this->base_url."/index.php?find=Polls&show_page=".$_GET['show_page']."&show_results=1&poll_id=".$_GET['poll_id']."&error=illegal_name&Comment_Content=".$_POST['Comment_Content']);
				die();
			}
		}
	}
}
if(isset($_GET['Yes_Deleted'])){
	echo "<b>"._COMMENT_REMOVED."</b><br><br>";
}elseif(isset($_GET['Not_Deleted'])){
	echo "<b>"._COMMENT_DOESNT_EXIST."</b><br><br>";
}elseif(isset($_GET['error'])){
	if($_GET['error'] === "illegal_name"){
		echo "<b>"._ILLEGAL_NAME."</b><br><br>";
	}elseif($_GET['error'] === "illegal_email"){
		echo "<b>"._ILLEGAL_EMAIL."</b><br><br>";
	}elseif($_GET['error'] === "user_already_registered"){
		echo "<b>"._USER_REGISTERED."</b><br><br>";
	}
}

if(isset($_GET['Delete'])){
	if(isset($_GET['Confirm'])){
		if($this->user->lvl() == 99){
			if(isset($_GET['Comment_ID'])){
				$Check_Comment_Exists = $this->MySQL->Fetch("SELECT Comment_ID FROM ".$this->pre."Poll_Comments WHERE Comment_ID = '".$_GET['Comment_ID']."'");
				if($_GET['Comment_ID'] == $Check_Comment_Exists['Comment_ID']){
					$this->MySQL->Query("DELETE FROM ".$this->pre."Poll_Comments WHERE Comment_ID = '".$_GET['Comment_ID']."' LIMIT 1");
					header("Location: ".$this->base_url."/index.php?find=Polls&show_page=".$_GET['show_page']."&show_results=1&poll_id=".$_GET['poll_id']."&Yes_Deleted=1");
                    die();
            	}else{
					header("Location: ".$this->base_url."/index.php?find=Polls&show_page=".$_GET['show_page']."&show_results=1&poll_id=".$_GET['poll_id']."&Not_Deleted=1");
                    die();
                }
			}else{
				header("Location: ".$this->base_url."/index.php");
                die();
            }
		}else{
			header("Location: ".$this->base_url."/index.php");
			die();
		}
	}else{
		echo _DELETE_THIS_COMMENT."<br><br>";
		echo "<a href=\"index.php?find=Polls&amp;show_page=".$_GET['show_page']."&amp;show_results=1&amp;poll_id=".$_GET['poll_id']."&amp;Comment_ID=".$_GET['Comment_ID']."&amp;Delete=1&amp;Confirm=1\">"._YES."</a> | <a href=\"index.php?find=Polls&amp;show_results=1&amp;poll_id=".$_GET['poll_id']."\">"._NO."</a><br><br>";
		unset($_SESSION['Poll_Comments_Set']);
		unset($_SESSION['Poll_Comment_Date']);
		unset($_SESSION['Poll_Comment_Author']);
		unset($_SESSION['Poll_Comment']);
		unset($_SESSION['Poll_Comment_ID']);	
		unset($_SESSION['Poll_Comments_Page']);
		unset($_SESSION['Poll_Count']);
		unset($_SESSION['poll_sql']);
		$Comm = $this->MySQL->Fetch("SELECT Comment_Date, Comment_Author, Comment_Content, Comment_ID FROM ".$this->pre."Poll_Comments WHERE Comment_ID = '".$_GET['Comment_ID']."' ");
		$Comment_Date = $Comm['Comment_Date'];
		$Comment_Author = $Comm['Comment_Author'];
		$Comment = nl2br(htmlspecialchars($Comm['Comment_Content']));
		$Comment_ID = $Comm['Comment_ID'];
		$ca = explode(">", $Comm['Comment_Author']);
		$cb = explode("<", $ca[1]);
		$cauthor = $cb[0];
		$causer = $this->MySQL->Fetch("SELECT user_posts FROM ".$this->pre."users WHERE username = '".$cauthor."'");
		if($this->Censor_Words == 1){
			$Comment = censor($Comment);
		}
		if($this->Emoticon_On == 1){
			$Comment = emoticon($Comment);
		}
		if($this->BBcode_On == 1){
			$Comment = bbcode($Comment);
		}
		$this->user->theme("comment.tpl", array("Author_Posts" => $causer['user_posts'], "url" => $this->base_url, "Comment_Date" => $Comment_Date, "Comment_Author" => $Comment_Author,"Comment_Content" => $Comment));
	}
}else{
	if(empty($_SESSION['Poll_Count'][$_GET['poll_id']])){
		$_SESSION['Poll_Count'][$_GET['poll_id']] = $this->MySQL->Rows("SELECT poll_id FROM ".$this->pre."Poll_Comments WHERE poll_id = '".$_GET['poll_id']."'");
	}
	$Count_Div = $_SESSION['Poll_Count'][$_GET['poll_id']] / 5;
	$Ro_Count = ceil($Count_Div);
	if(!isset($_GET['show_page']) || empty($_GET['show_page'])){
		$_GET['show_page'] = 1;
	}
	
	if($_GET['show_page'] > $Ro_Count){
		if($_SESSION['Poll_Count'][$_GET['poll_id']] <= 0){
			$nocomments = 1;
		}elseif($_SESSION['Poll_Count'][$_GET['poll_id']] > 0){
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
	}elseif($_SESSION['Poll_Count'][$_GET['poll_id']] <= 5){
		$limit_show = $_SESSION['Poll_Count'][$_GET['poll_id']];
	}
	if(empty($_SESSION['poll_sql'][$_GET['poll_id']])){
		$_SESSION['poll_sql'][$_GET['poll_id']] = $this->MySQL->Query("SELECT Comment_Date, Comment_Author, Comment_Content, Comment_ID FROM ".$this->pre."Poll_Comments WHERE Poll_ID = '".$_GET['poll_id']."' ORDER BY Comment_Time ASC LIMIT ".$limit_show);
	}
	$i = 1;
	while($Comments = mysql_fetch_array($_SESSION['poll_sql'][$_GET['poll_id']])){
			if($this->user->lvl() == 99){
				$Delete_Comment = "<a href=\"index.php?find=Polls&amp;show_page=".$_GET['show_page']."&amp;show_results=1&amp;poll_id=".$_GET['poll_id']."&amp;Comment_ID=".$Comments['Comment_ID']."&amp;Delete=1\">"._DELETE_COMMENT."</a>";
			}
			$ca = explode(">", $Comments['Comment_Author']);
			$cb = explode("<", $ca[1]);
			$cauthor = $cb[0];
			
			if(($this->user->name() == $cauthor) || ($this->user->lvl() >= 3)){
				$Edit_Comment = "<a href=\"index.php?find=Polls&amp;show_page=".$_GET['show_page']."&amp;show_results=1&amp;poll_id=".$_GET['poll_id']."&amp;Comment_ID=".$Comments['Comment_ID']."&amp;&Edit=1#Post_Comment\">"._EDIT_COMMENT."</a>";
			}
			
			if((empty($_SESSION['Poll_Comments_Set'][$_GET['poll_id']]) || $_SESSION['Poll_Comments_Set'][$_GET['poll_id']] <= time()) && ($_SESSION['Poll_Comments_Page'][$_GET['poll_id']] !== $_GET['show_page'])){
				$session_comments = 1;
			}else{
				if(($_SESSION['Poll_Comments_Page'][$_GET['poll_id']] !== $_GET['show_page'])){
					$session_comments = 1;
				}
			}
			
			if(isset($session_comments)){
				$_SESSION['Poll_Comment_Date'][$_GET['poll_id']][$i] = $Comments['Comment_Date'];
				$_SESSION['Poll_Comment_Author'][$_GET['poll_id']][$i] = $Comments['Comment_Author'];
				$_SESSION['Poll_Comment'][$_GET['poll_id']][$i] = nl2br(htmlspecialchars($Comments['Comment_Content']));
				$_SESSION['Poll_Comment_ID'][$_GET['poll_id']][$i] = $Comments['Comment_ID'];
					
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
								$_SESSION['Poll_Comment_Author_Avatar'][$_GET['poll_id']][$cauthor] =  "<img src=\"".$user_avatar."\" alt=\"avatar\" width=\"".$size[0]."\" height=\"".$size[1]."\"/>";
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
								$_SESSION['Poll_Comment_Author_Avatar'][$_GET['poll_id']][$cauthor] = "<img src=\"".$user_avatar."\" alt=\"avatar\" width=\"".$newX."\" height=\"".$newY."\"/>";
							}
						}else{
							$_SESSION['Poll_Comment_Author_Avatar'][$_GET['poll_id']][$cauthor] = "[X]";
						}
					}					
					$_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor] = nl2br(htmlspecialchars($user_sig));
					$_SESSION['Poll_Comment_Author_Posts'][$_GET['poll_id']][$cauthor] = $user_posts;
					$_SESSION['Poll_Comment_Author_Group'][$_GET['poll_id']][$cauthor] = $user_group;
				}else{
					$_SESSION['Poll_Comment_Author_Avatar'][$_GET['poll_id']][$cauthor] = "";
					$_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor] = "";
					$_SESSION['Poll_Comment_Author_Posts'][$_GET['poll_id']][$cauthor] = 1;
					$_SESSION['Poll_Comment_Author_Group'][$_GET['poll_id']][$cauthor] = "None";
				}
				
				if($this->Censor_Words == 1){
					$_SESSION['Poll_Comment'][$_GET['poll_id']][$i] = censor($_SESSION['Poll_Comment'][$_GET['poll_id']][$i]);
					$_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor] = censor($_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor]);
				}
				if($this->Emoticon_On == 1){
					$_SESSION['Poll_Comment'][$_GET['poll_id']][$i] = emoticon($_SESSION['Poll_Comment'][$_GET['poll_id']][$i]);
					$_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor] = emoticon($_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor]);
				}
				if($this->BBcode_On == 1){
					$_SESSION['Poll_Comment'][$_GET['poll_id']][$i] = bbcode($_SESSION['Poll_Comment'][$_GET['poll_id']][$i]);
					$_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor] = bbcode($_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor]);
				}
				
				if($_GET['show_page'] == $Ro_Count){
					$difference = $Count_Comments - $first;
				}else{
					$difference = 5;
				}
				
				if(($i == $difference) && isset($session_comments)){
					if(isset($_GET['Not_Deleted'])){
						$add_deleted = "&amp;Not_Deleted=1";
					}elseif(isset($_GET['Yes_Deleted'])){
						$add_deleted = "&amp;Yes_Deleted=1";
					}
					$_SESSION['Poll_Comments_Set'][$_GET['poll_id']] = time()+500;
					$_SESSION['Poll_Comments_Page'][$_GET['poll_id']] = $_GET['show_page'];
					header("Location: ".$this->base_url."/index.php?find=Polls&show_page=".$_GET['show_page']."&show_results=1&poll_id=".$_GET['poll_id'].$add_deleted);
					die();
				}
            }

			$this->user->theme("comment.tpl", array("Author_Group" => $_SESSION['Poll_Comment_Author_Group'][$_GET['poll_id']][$cauthor], "Edit_Comment" => $Edit_Comment, "Author_Avatar" => $_SESSION['Poll_Comment_Author_Avatar'][$_GET['poll_id']][$cauthor], "Author_Sig" => $_SESSION['Poll_Comment_Author_Sig'][$_GET['poll_id']][$cauthor], "Author_Posts" => $_SESSION['Poll_Comment_Author_Posts'][$_GET['poll_id']][$cauthor],"Comment_Date" => $_SESSION['Poll_Comment_Date'][$_GET['poll_id']][$i], "Comment_Author" => $_SESSION['Poll_Comment_Author'][$_GET['poll_id']][$i], "Comment_Content" => $_SESSION['Poll_Comment'][$_GET['poll_id']][$i], "Delete_Comment" => $Delete_Comment, "url" => $this->base_url));
			$i++;
	}
		
	if($_SESSION['Poll_Count'][$_GET['poll_id']] > 5){
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
			echo "<option value=\"index.php?find=Polls&amp;poll_id=".$_GET['poll_id']."&amp;show_results=1&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
		}
		echo "</select></form>";
	}
	
	$fvalues = "?find=Polls&amp;show_results=1&amp;show_page=".$_GET['show_page']."&amp;vote=1&amp;poll_id=".$_GET['poll_id'];
	post_comment($fvalues);
}

?>
