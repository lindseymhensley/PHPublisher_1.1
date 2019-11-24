<?php

/*******************************************************************
 **
 ** Admin File: Articles/index.php
 ** Description: Article adding/editing/deleting is done here
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 1){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}


if(isset($_GET['NewPost']) || isset($_GET['Article_ID'])){
		if(isset($_POST['Submit'])){
			$_POST['Article_Title'] = str_replace("%", "", htmlspecialchars(stripslashes($_POST['Article_Title'])));
			$_POST['Article_Story'] = htmlspecialchars(stripslashes($_POST['Article_Story']));
			$_POST['Article_Full_Story'] = htmlspecialchars(stripslashes($_POST['Article_Full_Story']));
			if(isset($_POST['preview']) && ($_POST['preview'] == 1)){
				$topic = $MySQL->Fetch("SELECT img FROM ".$pre."topics WHERE name = '".$_POST['artopic']."'");
				if($topic['img'] != "none.gif"){
					$T_IMG = "<img src=\"".$base_url."/images/topics/".$topic['img']."\" alt=\"".$_POST['artopic']."\" border=\"0\" />";
				}else{
					$T_IMG = "";
				}
				$story = nl2br($_POST['Article_Story']);
				if($Censor_Words == 1){
					$story = censor($story);
				}
				if($Emoticon_On == 1){
					$story = emoticon($story);
				}
				if($BBcode_On == 1){
					$story = bbcode($story);
				}
				$user->theme("news.tpl", array("url" => $base_url, "Post_Comment" => _POST_COMMENT, "Reads" => "0", "Comment_Count" => "0", "Article_Topic_Img" => $T_IMG, "Article_Topic" => $_POST['artopic'], "Article_Title" => $_POST['Article_Title'], "Article_Story" => $story, "Article_Author" => $user->name(), "Article_Date" => $date, "Read_More" => _SEE_FULL_STORY));
			}else{
				$_POST['Article_Title'] = addslashes($_POST['Article_Title']);
				$_POST['Article_Story'] = addslashes($_POST['Article_Story']);
				$_POST['Article_Full_Story'] = addslashes($_POST['Article_Full_Story']);
				if(isset($_GET['Article_ID'])){
					$MySQL->Query("UPDATE ".$pre."News_Articles SET Article_Title = '".$_POST['Article_Title']."', Article_Story = '".$_POST['Article_Story']."', Article_Full_Story = '".$_POST['Article_Full_Story']."', Article_Topic = '".$_POST['artopic']."'  WHERE Article_ID = '".$_GET['Article_ID']."' ");
					$settings = 1;
					include($Current_Directory."/includes/refresh_content.php");
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Articles&show_page=1&Article_Updated=1&Article_Title=".$_POST['Article_Title']."");
							die();
				}elseif(isset($_GET['NewPost'])){
					$Insert_Article = 'INSERT INTO `'.$pre.'News_Articles` (`Article_ID`, `Article_Title`, `Article_Author`, `Article_Story`, `Article_Time`, `Article_Date`, `Article_Full_Story`, `Article_Topic`) VALUES (\'\', \''.$_POST['Article_Title'].'\', \''.$user->name().'\', \''.$_POST['Article_Story'].'\', \''.time().'\', \''.$date.'\', \''.$_POST['Article_Full_Story'].'\', \''.$_POST['artopic'].'\')'; 
					$MySQL->Query("UPDATE ".$pre."users SET user_posts=user_posts+1 WHERE user_id = '".$user->id()."'");
					$MySQL->Query($Insert_Article);
					$settings = 1;
					include($Current_Directory."/includes/refresh_content.php");
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Articles&show_page=1&NewPostSuccess=1&Article_Title=".$_POST['Article_Title']);
					die();
				}
			}
		}
		if(isset($_GET['Article_ID'])){
			$article_array = $MySQL->Fetch("SELECT Article_Topic, Article_Title, Article_Story, Article_Full_Story, Article_Author FROM ".$pre."News_Articles WHERE Article_ID = '".$_GET['Article_ID']."'");
		}
		if(isset($_GET['Delete'])){
			if($_GET['Yes'] == 1){
				if(isset($_GET['Article_ID'])){
					$Article_Check = $MySQL->Fetch("SELECT Article_ID FROM ".$pre."News_Articles WHERE Article_ID = '".$_GET['Article_ID']."' ");
					if($_GET['Article_ID'] == $Article_Check['Article_ID']){
						$MySQL->Query("DELETE FROM ".$pre."News_Articles WHERE Article_ID = '".$_GET['Article_ID']."' LIMIT 1");
						header("Location: ".$base_url."/index.php?find=Admin_Panel&show_page=1&func=Articles&Deleted=1&Article_Title=".$_GET['Article_Title']."");
                        die();
                   	}else{
						echo _ARTICLE_DOESNT_EXIST;
					}
				}else{
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Articles&show_page=1");
                    			die();
               			}
			}elseif($_GET['No'] == 1){
				echo _ARTICLE_NOT_REMOVED;
			}else{
				echo "<br>"._ARE_YOU_SURE." <br><a href=\"index.php?find=Admin_Panel&amp;show_page=1&amp;func=Articles&amp;Delete=1&amp;Article_ID=".$_GET['Article_ID']."&amp;Yes=1&amp;Article_Title=".$_GET['Article_Title']."\">"._YES."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Articles&amp;show_page=1&amp;Delete=1&amp;No=1&amp;Article_Title=".$_GET['Article_Title']."\">"._NO."</a><br><br />";
			}
		}
        if(isset($_GET['Article_ID'])){
            $get_article = "&amp;Article_ID=".$_GET['Article_ID'];
        }else{
            $get_article = "";
        }
		$Table->Open(_POST_NEW);
        ?>
	<form action="index.php?find=Admin_Panel&amp;func=Articles&amp;NewPost=1&amp;showpage=1<?=$get_article ?>" method="POST" name="Article">
	<a name="Post"></a>
	<table width="100%"  border="0" cellspacing="5" cellpadding="0">
	  <? if(isset($_GET['Article_ID'])){ ?>
	  <tr>
		<td width="25%" align="left"><?=_ARTICLE_ID ?> </td>
		<td width="65%" align="left"><?=$_GET['Article_ID'] ?></td>
		<td width="10%">&nbsp;</td>
	  </tr>
	  <? } ?>
		<tr>
		<td align="left"><?=_TOPIC ?> </td>
		<td  align="left"><select name="artopic">
		<?
		$topic_select = $MySQL->Query("SELECT name FROM ".$pre."topics");
		while($topic = mysql_fetch_array($topic_select)){
			echo "<option value=\"".$topic['name']."\" "; if(($topic['name'] === $article_array['Article_Topic']) || ($_POST['artopic'] === $topic['name'])) echo "selected"; echo ">".$topic['name']."</option>\n";
		}
		?></select></td>
		<td >&nbsp;</td>
	  </tr>
	  
	  <tr>
		<td align="left"><?=_ARTICLE_TITLE ?> </td>
		<td align="left"><input name="Article_Title" type="text" <? if(isset($_GET['Article_ID']) && empty($_POST['preview'])){ ?>value="<?=$article_array['Article_Title'] ?>" <? }elseif(isset($_POST['preview']) && ($_POST['preview'] == 1)){ echo "value=\"".$_POST['Article_Title']."\""; } ?>></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align="center">
	<script language="JavaScript" type="text/javascript">
	function emoticon(text) {
		var txtarea = document.Article.Article_Story;
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
	</script>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<?php
	if($Emoticon_On == 1){
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
	?>
	</table>
	</td>
		<td></td>
	  </tr>
		<tr><td></td><td align="left">
		<?php 
		if($BBcode_On == 1){
			echo toolbar("Article", "Article_Story");
		} 
		?></td><td></td></tr>
	  <tr>
		<td align="left" valign=top><?=_STORY ?></td>
		<td align="left"><textarea name="Article_Story" cols="50" rows="5"><? if(isset($_GET['Article_ID']) && empty($_POST['preview'])){  echo $article_array['Article_Story']; }elseif(isset($_POST['preview']) && ($_POST['preview'] == 1)){ echo $_POST['Article_Story']; } ?></textarea></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td align="left"><?=_FULL_STORY ?> </td>
		<td align="left"><textarea name="Article_Full_Story" cols="50" rows="5"><? if(isset($_GET['Article_ID'])){  echo $article_array['Article_Full_Story']; }elseif(isset($_POST['preview']) && ($_POST['preview'] == 1)){ echo $_POST['Article_Full_Story']; } ?></textarea></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td align="left"><?=_AUTHOR ?></td>
		<td align="left"><? if(isset($_GET['Article_ID'])){ echo $article_array['Article_Author']; }else{ echo $user->name(); } ?></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align="left"><? if(isset($_GET['Article_ID'])){ ?><a href="index.php?find=Admin_Panel&amp;show_page=1&amp;func=Articles&amp;Delete=1&amp;Article_Title=<?=$article_array['Article_Title'] ?>&amp;Article_ID=<?=$_GET['Article_ID'] ?>">"<?=_DELETE_THIS_ARTICLE ?>"</a><? } ?></td>
		<td align="left"></td>
	  </tr>
	</table><table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td width="25%" align="left"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left"><input name="preview" type="radio" value="1" checked="checked" style="margin-bottom: 8px;"><?=_PREVIEW ?></td>
		</tr>
		<tr>
        <td align="left"><input name="preview" type="radio" value="0" style="margin-top: 7px;"><?=_POST ?></td>
      </tr>
    </table><input type="submit" name="Submit" value="Submit" style="margin-top: 15px;"></td>
  </tr>

</table>

	</form>
	<? 
	$Table->Close();
}else{
	$Table->Open();
	
	$Count_Articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles");
	$Count_Div = $Count_Articles / 10;
	$Ro_Count = ceil($Count_Div);
	if(!isset($_GET['show_page']) || empty($_GET['show_page'])){
		header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Articles&show_page=1");
        	die();
	}
	
	if($_GET['show_page'] > $Ro_Count){
		if($Count_Articles <= 0){
			$noarticles = 1;
		}elseif($Count_Articles > 0){
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Articles&show_page=1");
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
	if(isset($noarticles)){
		$limit_show = 1;
	}
	$articles = $MySQL->Query("SELECT Article_Title, Article_Author, Article_ID, Article_Date, Article_Topic FROM ".$pre."News_Articles ORDER BY Article_Time DESC LIMIT ". $limit_show);	
	
	if($_GET['Article_Updated'] == 1){
		echo "<br><strong>"._ARTICLE_UPDATED."</strong><br><br>";
	}elseif($_GET['NewPostSuccess'] == 1){
		echo "<br><strong>"._ARTICLE_POSTED."</strong><br><br>";
	}elseif($_GET['Deleted'] == 1){
		echo "<br><strong>"._ARTICLE_DELETED."</strong><br><br>";
	}
	echo "<div align=left><a href=\"index.php?find=Admin_Panel&amp;show_page=1&amp;func=Articles&amp;NewPost=1#Post\">"._POST_NEW."</a></div>";
	echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"2\" >";
	echo "<tr>
	<td align=left width=\"3%\" class=\"table\">"._ID."</td>
	<td align=left width=\"12%\" class=\"table\">"._TOPIC."</td>
	<td align=left width=\"45%\" class=\"table\">"._TITLE."</td>
	<td align=left width=\"10%\" class=\"table\">"._AUTHOR."</td>
	<td align=left width=\"30%\" class=\"table\">"._DATE."</td>
		</tr>";
	while($article_array = mysql_fetch_array($articles)){
		echo "  
		<tr>
			<td align=left class=\"table\">".$article_array['Article_ID']."</td>
			<td align=left class=\"table\">".$article_array['Article_Topic']."</td>
			<td align=left class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;show_page=1&amp;func=Articles&amp;Article_ID=".$article_array['Article_ID']."\">".strr($article_array['Article_Title'], 25)."</a></td>
			<td align=left class=\"table\">".$article_array['Article_Author']."</td>
			<td align=left class=\"table\">".$article_array['Article_Date']."</td>
		</tr>";
	}
	echo "</table>";
	
	if(isset($noarticles)){
		echo "<br>"._NO_ARTICLES."<br><BR>";
	}else{	
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
			echo "<option value=\"index.php?find=Admin_Panel&amp;func=Articles&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
		}
		echo "</select></form>";
		}
	$Table->Close();
}
?>
