<?php
/*******************************************************************
 **
 ** Admin File: Content/index.php
 ** Description: Here you can add/edit/remove custom content pages
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
if(isset($_POST['update']) && !empty($_POST['update'])){
	if(isset($_GET['content_id']) && !empty($_GET['content_id'])){
		if(isset($_POST['title']) && !empty($_POST['title'])){
			if(isset($_POST['description']) && !empty($_POST['description'])){
				if(isset($_POST['content']) && !empty($_POST['content'])){
					$MySQL->Query("UPDATE ".$pre."content SET title='".$_POST['title']."', description='".$_POST['description']."', content='".$_POST['content']."', headers='".$_POST['headers']."', access='".$_POST['access']."' WHERE content_id = '".$_POST['content_id']."'");
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&success=1&show_page=1&title=".$_POST['title']);
                    			die();
                		}else{
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&content_id=".$_GET['content_id']."&check_content=1&title=".$_POST['title']."&description=".$_POST['description']."&content=".$_POST['content']."&error=3&updated=1");
                    			die();
                		}
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&content_id=".$_POST['content_id']."&check_content=1&title=".$_POST['title']."&description=".$_POST['description']."&content=".$_POST['content']."&error=2&updated=1");
                		die();
            		}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&content_id=".$_POST['content_id']."&check_content=1&title=".$_POST['title']."&description=".$_POST['description']."&content=".$_POST['content']."&error=1&updated=1");
            		die();
    		}
	}else{
		echo _MISSING_CONTENT_ID;
	}
}elseif(isset($_POST['create']) && !empty($_POST['create'])){
	if(isset($_POST['title']) && !empty($_POST['title'])){
		if(isset($_POST['description']) && !empty($_POST['description'])){
			if(isset($_POST['content']) && !empty($_POST['content'])){
				$content_insert = 'INSERT INTO `'.$pre.'content` (`content_id`, `title`, `description`, `content`, `views`, `created_on`, `headers`, `access`) VALUES (\'\', \''.$_POST['title'].'\', \''.$_POST['description'].'\', \''.$_POST['content'].'\', \'0\', \''.$date.'\', \''.$_POST['headers'].'\', \''.$_POST['access'].'\')'; 
				$MySQL->Query($content_insert);
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&success=2&show_page=1&title=".$_POST['title']);
                die();
            }else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&check_content=1&title=".$_POST['title']."&description=".$_POST['description']."&content=".$_POST['content']."&error=3&updated=1");
                die();
            }
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&check_content=1&title=".$_POST['title']."&description=".$_POST['description']."&content=".$_POST['content']."&error=2&updated=1");
            die();
        }
	}else{
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&check_content=1&title=".$_POST['title']."&description=".$_POST['description']."&content=".$_POST['content']."&error=1&updated=1");
        die();
    }
}elseif(isset($_GET['delete']) && !empty($_GET['delete'])){
	if(isset($_GET['content_id']) && !empty($_GET['content_id'])){
		$content_exists = $MySQL->Rows("SELECT content_id FROM ".$pre."content WHERE content_id = '".$_GET['content_id']."'");
		if($content_exists > 0){
			if(isset($_GET['confirm']) && !empty($_GET['confirm'])){
				$content = $MySQL->Fetch("SELECT title FROM ".$pre."content WHERE content_id='".$_GET['content_id']."'");
				$MySQL->Query("DELETE FROM ".$pre."content WHERE content_id = '".$_GET['content_id']."' LIMIT 1");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&deleted=1&title=".$content['title']."");
                die();
            }else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&content_id=".$_GET['content_id']."&check_content=1&sure=1");
                die();
           	}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1&doesntexists=1");
            die();
        }
	}else{
		echo _MISSING_CONTENT_ID;
	}
}elseif(isset($_GET['check_content']) && !empty($_GET['check_content'])){
	if(isset($_GET['content_id']) && !empty($_GET['content_id'])){
		$content = $MySQL->Fetch("SELECT content_id, headers, access, title, description, created_on, content FROM ".$pre."content WHERE content_id = '".$_GET['content_id']."'");
	}
	switch ($_GET['error'])
	{
		case 1:
				echo _MISSING_TITLE."<br><br>";
		break;

		case 2:
				echo _MISSING_DESC."<br><br>";
		break;

		case 3:
				echo _MISSING_CONTENT."<br><br>";
		break;
		
		default:	
			if($content['content_id'] == $_GET['content_id']){		
				
				if(isset($_GET['sure']) && !empty($_GET['sure'])){
					echo _ARE_U_SURE."<br><a href=\"index.php?find=Admin_Panel&amp;func=Content&amp;delete=1&amp;content_id=".$_GET['content_id']."&amp;confirm=1\">"._YES."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Content&amp;notdeleted=1&amp;show_page=1\">"._NO."</a><br><br>";
				}
				
				$Table->Open();
				
				if(isset($_GET['content_id'])){
					$conID = "&amp;content_id=".$_GET['content_id'];
				}else{
					$conID = "";
				}
				
				echo "<form method=\"post\" action=\"index.php?find=Admin_Panel&amp;showpage=1&amp;func=Content".$conID."\" name=\"content\">
				<table width=\"100%\"  border=\"0\" cellspacing=\"3\" cellpadding=\"0\">";
				if(isset($_GET['content_id'])){
					  echo "<tr><td width=20% align=left>"._ID.": </td><td align=left width=85%>".$_GET['content_id']."</td></tr>";
				}
				echo "<tr>
					<td width=20% align=left>"._TITLE.": </td>
					<td width=80% align=left><input type=\"text\" name=\"title\" value=\""; if(isset($_GET['updated'])){ echo $_GET['title']; }else{ echo $content['title']; } echo "\"></td>
				  </tr>
				  <tr>
					<td align=left>"._DESC.": </td>
					<td align=left><input type=\"text\" name=\"description\" value=\""; if(isset($_GET['updated'])){ echo $_GET['description']; }else{ echo $content['description']; } echo "\"></td>
				  </tr>
				  <tr>
					<td align=left>"._DATE.": </td>
					<td align=left>"; if(isset($_GET['content_id'])){ echo $content['created_on']; }else{ echo $date; } echo "</td>
				  </tr>
				   <tr>
					<td align=left>"._SHOW_HEADERS.": </td>
					<td align=left><select name=\"headers\">
					<option value=\"1\""; if($content['headers'] == 1){ echo "selected=\"selected\"";  } echo ">"._YES."</option>
					<option value=\"0\""; if($content['headers'] == 0){ echo "selected=\"selected\"";  } echo ">"._NO."</option></select></td>
				  </tr>
				  <tr>
					<td align=left>"._VIEWABLE_BY.": </td>
					<td align=left><select name=\"access\">
					<option value=\"0\""; if($content['access'] == 0){ echo "selected=\"selected\"";  } echo ">"._ALL."</option>
					<option value=\"1\""; if($content['access'] == 1){ echo "selected=\"selected\"";  } echo ">"._MEMBERS."</option>";
					$group_sql = $MySQL->Query("SELECT group_id, name FROM ".$pre."user_groups");
					while($group = mysql_fetch_array($group_sql)){
						echo "<option value=\"Group-".$group['group_id']."\""; if($content['access'] == "Group-".$group['group_id']){ echo "selected=\"selected\"";  } echo ">".$group['name']."</option>";
					}
					echo "<option value=\"2\""; if($content['access'] == 2){ echo "selected=\"selected\"";  } echo ">"._ARTICLE_PUBLISHER."</option>";
					echo "<option value=\"3\""; if($content['access'] == 3){ echo "selected=\"selected\"";  } echo ">"._MODERATOR."</option>";
					echo "<option value=\"99\""; if($content['access'] == 99){ echo "selected=\"selected\"";  } echo ">"._ADMINS."</option>
					</select></td>
				  </tr>
				  <tr>
					<td align=left valign=top>"._CONTENT.": </td>
					<td align=left>
					<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				  <tr>
					<td align=left>			<table width=\"80%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=left>";
					if($Emoticon_On == 1){
						echo "<script language=\"JavaScript\" type=\"text/javascript\">
						function emoticon(text) {
							var txtarea = document.content.content;
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
						$find_smilies = $MySQL->Query("SELECT smilie_code, smilie_img FROM ".$pre."smilies ORDER BY smilie_id ASC");
						$i = 1;
						
						while($emo = mysql_fetch_array($find_smilies)){
							if($i == 1){
								echo "<tr>"
								."<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$base_url."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
							}elseif($i >=2 && $i < 5){
								echo "<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$base_url."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
							}elseif($i == 5){
								echo "<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$base_url."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>"
								."</tr>";
								unset($i);
								$i = 0;
							}
							$i++;
						}
					}
					echo "<tr><td align=right colspan=7>";
				
					if($BBcode_On == 1){
						echo toolbar("content", "content");
					}
					echo "</td></tr></table></td>
				  </tr>
				</table>
			
				<textarea name=\"content\" cols=55 rows=10>"; if(isset($_GET['updated'])){ echo $_GET['content']; }else{ echo htmlspecialchars($content['content']); } echo "</textarea>
					<br>";
					if(isset($_GET['content_id'])){
						echo "<input name=\"update\" type=\"hidden\" value=\"1\">";
						echo "<input name=\"content_id\" type=\"hidden\" value=\"".$_GET['content_id']."\">";
					}else{
						echo "<input name=\"create\" type=\"hidden\" value=\"1\">";
					}
					echo "<input type=\"submit\" name=\"Submit\" value=\"Submit\">"; if(isset($_GET['content_id'])){ echo " [ <a href=\"index.php?find=Admin_Panel&amp;func=Content&amp;content_id=".$content['content_id']."&amp;delete=1\">"._DELETE."</a>? ]"; } echo "</td>
				  </tr>
				</table>
				</form>";
				$Table->Close();
			}else{
				$Table->Open();
				echo _CONTENT_PAGE_DOES_NOT_EXIST;
				$Table->Close();
			}
		break;
	}
}else{
	if($_GET['success'] == 1){
		echo "<br>"._SUCCESSFULLY_UPDATED_CONTENT."<br><br>";
	}elseif($_GET['success'] == 2){
		echo "<br>"._SUCCESSFULLY_CREATED_CONTENT."<br><br>";
	}elseif($_GET['notdeleted'] == 1){
		echo "<br>"._NO_CONTENT_DELETED."<br><br>";
	}elseif($_GET['deleted'] == 1){
		echo "<br>"._CONTENT_DELETED."<br><br>";
	}elseif($_GET['doesntexist'] == 1){
		echo "<br>"._CONTENT_NOT_FOUND."<br><br>";
	}
	
	$Table->Open();
	$Count_Content = $MySQL->Rows("SELECT content_id FROM ".$pre."content");
	$Count_Div = $Count_Content / 10;
	$Ro_Count = ceil($Count_Div);
	if(!isset($_GET['show_page']) || empty($_GET['show_page'])){
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1");
        	die();
    	}
	
	if($_GET['show_page'] > $Ro_Count){
		if($Count_Content <= 0){
			$nocontent = 1;
		}elseif($Count_Content > 0){
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Content&show_page=1");
            		die();
        	}
	}
	
	$i = 1;
	for($i = 1; $i <= $Ro_Count; $i++){
		if($_GET['show_page'] == $i){
			$second = $i * 10;
			$first = $second - 10;
			$limit_show = "".$first.", 10"; 
		}
	}
	if(isset($nocontent)){
		$limit_show = 1;
	}
	$find_content = $MySQL->Query("SELECT content_id, title, created_on FROM ".$pre."content ORDER BY content_id DESC LIMIT ".$limit_show);	
	echo "<br>
		<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Content&amp;check_content=1\">"._CREATE_CONTENT."</a></div><table width=\"100%\" cellspacing=3 cellpadding=0><tr><td class=table>"._ID."</td><td class=table>"._TITLE."</td><td class=table>"._DATE."</td></tr>";
	while($content = mysql_fetch_array($find_content)){
		echo "<tr><td class=table width=3%>".$content['content_id']."</td><td class=table width=72% align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Content&amp;content_id=".$content['content_id']."&amp;check_content=1\">".$content['title']."</a></td><td class=table width=25%>".$content['created_on']."</td></tr>";
	}
	echo "</table>";
	
	if(isset($nocontent)){
		echo "<br>"._NO_CONTENT."<br><BR>";
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
			echo "<option value=\"index.php?find=Admin_Panel&amp;func=Content&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
		}
		echo "</select></form>";
	}
	$Table->Close();
}
?>
