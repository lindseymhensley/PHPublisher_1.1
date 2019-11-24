<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
$search_terms = htmlspecialchars(addslashes($_GET['search']));
if(empty($search_terms)){
	$search_terms = " ";
}
echo "<form action=\"index.php\" method=\"get\">";
$Table->Open("<strong>"._SEARCH_FOR_NEWS."</strong>");
echo "<input type=\"hidden\" name=\"find\" value=\"News\">
<input type=\"hidden\" name=\"file\" value=\"View_Articles\">
<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
  <tr>
    <td width=\"55%\" align=\"right\"><input type=\"text\" name=\"search\" maxlength=\"50\"></td>
    <td width=\"45%\" align=\"left\"><input type=\"submit\" name=\"Submit\" value=\"Search\"></td>
  </tr>
</table>";
$Table->Close();
echo "</form><br />";
if(empty($_GET['show_page'])){
	$_GET['show_page'] = 1;
}
if(isset($_GET['topic'])){
	if($_GET['topic'] != "All"){
		if(!empty($search_terms)){
			$Count_Articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles WHERE (Article_Topic = '".$_GET['topic']."') AND ((Article_Story LIKE '%".$search_terms."%') OR (Article_Full_Story LIKE '%".$search_terms."%'))");
		}else{
			$Count_Articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles WHERE Article_Topic = '".$_GET['topic']."'");
		}
	}elseif($_GET['topic'] === "All"){
		if(!empty($search_terms)){
			$Count_Articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles WHERE (Article_Story LIKE '%".$search_terms."%') OR (Article_Full_Story LIKE '%".$search_terms."%')");
		}else{
			$Count_Articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles");
		}
	}
}else{
	if(!empty($search_terms)){
		$Count_Articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles WHERE (Article_Story LIKE '%".$search_terms."%') OR (Article_Full_Story LIKE '%".$search_terms."%')");
	}else{
		$Count_Articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles");
	}
}
if($Count_Articles > 0){

	$Count_Div = $Count_Articles / $Show_Old_News;
	$Ro_Count = ceil($Count_Div);
	
	if($_GET['show_page'] > $Ro_Count){
		 $_GET['show_page'] = 1;
	}
	
	$i = 1;
	for($i = 1; $i <= $Ro_Count; $i++){
		if($_GET['show_page'] == $i){
			$second = $i * $Show_Old_News;
			$first = $second - $Show_Old_News;
			$limit_show = "".$first.", ".$Show_Old_News.""; 
		}
	}
	if(isset($_GET['topic'])){
		if($_GET['topic'] != "All"){
			if(!empty($search_terms)){
				$Articles = $MySQL->Query("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Author, Article_Date FROM ".$pre."News_Articles WHERE (Article_Topic = '".$_GET['topic']."') AND ((Article_Story LIKE '%".$search_terms."%') OR (Article_Full_Story LIKE '%".$search_terms."%')) ORDER BY Article_Time DESC LIMIT ".$limit_show);
			}else{
				$Articles = $MySQL->Query("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Author, Article_Date FROM ".$pre."News_Articles WHERE Article_Topic = '".$_GET['topic']."' ORDER BY Article_Time DESC LIMIT ".$limit_show);
			}
		}elseif($_GET['topic'] === "All"){
			if(!empty($search_terms)){
				$Articles = $MySQL->Query("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Author, Article_Date FROM ".$pre."News_Articles WHERE (Article_Story LIKE '%".$search_terms."%') OR (Article_Full_Story LIKE '%".$search_terms."%') ORDER BY Article_Time DESC LIMIT ".$limit_show);
			}else{
				$Articles = $MySQL->Query("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Author, Article_Date FROM ".$pre."News_Articles ORDER BY Article_Time DESC LIMIT ".$limit_show);
			}
		}
	}else{
		if(!empty($search_terms)){
			$Articles = $MySQL->Query("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Author, Article_Date FROM ".$pre."News_Articles WHERE (Article_Story LIKE '%".$search_terms."%') OR (Article_Full_Story LIKE '%".$search_terms."%') ORDER BY Article_Time DESC LIMIT ".$limit_show);
		}else{
			$Articles = $MySQL->Query("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Author, Article_Date FROM ".$pre."News_Articles ORDER BY Article_Time DESC LIMIT ".$limit_show);
		}
		$_GET['topic'] = "All";
	}
	if($_GET['show_page'] > 1){
		$i = $_GET['show_page'] * $Show_Old_News;
	}else{
		$i = 1;
	}
	$x = 1;

	while($post = mysql_fetch_array($Articles)){
		if(empty($_SESSION['Arts_Ses'][$i][$_GET['topic']]) && (empty($_SESSION['Arts_Ses']['count'][$_GET['topic']]) || $_SESSION['Arts_Ses']['count'][$_GET['topic']] !== $_GET['show_page'])){
			$session_articles = 1;
		}elseif(!empty($search_terms)){
			if(empty($_GET['loop'])){
				unset($_SESSION['Arts_Ses']['count'][$_GET['topic']]);
				$session_articles = 1;
			}			
		}
		if($session_articles == 1){
			$_SESSION['Articles_ID'][$i][$_GET['topic']] = $post['Article_ID'];
			$_SESSION['Articles_Title'][$i][$_GET['topic']] = $post['Article_Title'];
			$_SESSION['Articles_Topic'][$i][$_GET['topic']] = "<b><a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$post['Article_Topic']."\">".$post['Article_Topic']."</a>:</b>";
			$_SESSION['Articles_Story'][$i][$_GET['topic']] = nl2br($post['Article_Story']);
			$_SESSION['Articles_Full'][$i][$_GET['topic']] = nl2br($post['Article_Full_Story']);
			$_SESSION['Articles_Views'][$i][$_GET['topic']] = $post['Article_Views'];
			$_SESSION['Article_Topic'][$post['Article_ID']] = "<b><a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$post['Article_Topic']."\">".$post['Article_Topic']."</a>:</b>";
			$topic = $MySQL->Fetch("SELECT img FROM ".$pre."topics WHERE name = '".$post['Article_Topic']."'");
			if($topic['img'] != "none.gif"){
				$_SESSION['Articles_Topic_Img'][$i][$_GET['topic']] = "<a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$post['Article_Topic']."\"><img src=\"".$base_url."/images/topics/".$topic['img']."\" alt=\"".$post['Article_Topic']."\" border=0></a>";
			}else{
				$_SESSION['Articles_Topic_Img'][$i][$_GET['topic']] = "";
			}
			if($Censor_Words == 1){
				$_SESSION['Articles_Story'][$i][$_GET['topic']] = censor($_SESSION['Articles_Story'][$i][$_GET['topic']]);
				$_SESSION['Articles_Full'][$i][$_GET['topic']] = censor($_SESSION['Articles_Full'][$i][$_GET['topic']]);
			}
			if($Emoticon_On == 1){
				$_SESSION['Articles_Story'][$i][$_GET['topic']] = emoticon($_SESSION['Articles_Story'][$i][$_GET['topic']]);
				$_SESSION['Articles_Full'][$i][$_GET['topic']] = emoticon($_SESSION['Articles_Full'][$i][$_GET['topic']]);
			}
			if($BBcode_On == 1){
				$_SESSION['Articles_Story'][$i][$_GET['topic']] = bbcode($_SESSION['Articles_Story'][$i][$_GET['topic']]);
				$_SESSION['Articles_Full'][$i][$_GET['topic']] = bbcode($_SESSION['Articles_Full'][$i][$_GET['topic']]);
			}
			
			$_SESSION['Comments_Count'][$i][$_GET['topic']] = $MySQL->Rows("SELECT * FROM ".$pre."Article_Comments WHERE Story_ID = ".$_SESSION['Articles_ID'][$i][$_GET['topic']]."");
			
			$_SESSION['Comments_Count'][$i][$_GET['topic']] = $MySQL->Rows("SELECT * FROM ".$pre."Article_Comments WHERE Story_ID = ".$_SESSION['Articles_ID'][$i][$_GET['topic']]."");
			$_SESSION['Reads_More'][$i][$_GET['topic']] = "<a href=\"index.php?find=News&amp;file=Full_Story&amp;Show_Full=1&amp;ident=".$i."&amp;Article_ID=".$_SESSION['Articles_ID'][$i][$_GET['topic']]."\">"._SEE_FULL_STORY."</a>";
			$_SESSION['Posts_Comment'][$i][$_GET['topic']] = "<a href=\"index.php?find=News&amp;file=Full_Story&amp;Show_Full=1&amp;ident=".$i."&amp;Article_ID=".$_SESSION['Articles_ID'][$i][$_GET['topic']]."#Post_Comment\">"._POST_COMMENT."</a>";
			
			$_SESSION['Articles_Date'][$i][$_GET['topic']] = $post['Article_Date'];
			$profile = $MySQL->Fetch("SELECT user_id FROM ".$pre."users WHERE username = '".$post['Article_Author']."' LIMIT 1");
            $_SESSION['Articles_Author'][$i][$_GET['topic']] = _POSTED_BY ." <a href=\"index.php?find=Profile&amp;user_id=".$profile['user_id']."\">". $post['Article_Author'] ."</a>";
			$_SESSION['Arts_Ses'][$i][$_GET['topic']] = 1;
			if($_GET['show_page'] == $Ro_Count){
				$difference = $Count_Articles - $first;
			}else{
				$difference = $Show_Old_News;
			}
			if((($x == $difference)) && (empty($_SESSION['Arts_Ses']['count'][$_GET['topic']]) || $_SESSION['Arts_Ses']['count'][$_GET['topic']] !== $_GET['show_page'])){		
				if(isset($_GET['topic'])){
					if(empty($_GET['show_page'])){
						$_GET['show_page'] = 1;
					}
					$_SESSION['Arts_Ses']['count'][$_GET['topic']] = $_GET['show_page'];
					if(!empty($search_terms)){
						header("Location: ".$base_url."/index.php?find=News&loop=1&file=View_Articles&show_page=".$_GET['show_page']."&topic=".$_GET['topic']."&search=".$search_terms);
						die();
					}
					header("Location: ".$base_url."/index.php?find=News&file=View_Articles&show_page=".$_GET['show_page']."&topic=".$_GET['topic']);
					die();
				}else{
					$_SESSION['Arts_Ses']['count'][$_GET['topic']] = $_GET['show_page'];
					if(!empty($search_terms)){
						header("Location: ".$base_url."/index.php?find=News&loop=1&file=View_Articles&search=".$search_terms);
						die();
					}
					header("Location: ".$base_url."/index.php?find=News&file=View_Articles");
					die();
				}
			}
		}
		$user->theme("news.tpl", array("url" => $base_url, "Post_Comment" => $_SESSION['Posts_Comment'][$i][$_GET['topic']],"Reads" => $_SESSION['Articles_Views'][$i][$_GET['topic']], "Comment_Count" => $_SESSION['Comments_Count'][$i][$_GET['topic']], "Article_Topic_Img" => $_SESSION['Articles_Topic_Img'][$i][$_GET['topic']], "Article_Topic" => $_SESSION['Articles_Topic'][$i][$_GET['topic']], "Article_Title" => $_SESSION['Articles_Title'][$i][$_GET['topic']], "Article_Story" => $_SESSION['Articles_Story'][$i][$_GET['topic']], "Article_Author" => $_SESSION['Articles_Author'][$i][$_GET['topic']], "Article_Date" => $_SESSION['Articles_Date'][$i][$_GET['topic']], "Read_More" => $_SESSION['Reads_More'][$i][$_GET['topic']]));

		$x++;
		$i++;
	}
	
	echo "<strong>"._ARTICLE_PAGE.":</strong>\n ";
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
		if(!empty($search_terms)){
			$searchi = "&amp;search=".$search_terms;
		}
		echo "<option value=\"index.php?find=News&amp;file=View_Articles&amp;topic=".$_GET['topic']."&amp;show_page=".$i.$searchi."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
	}
	echo "</select></form>";
}elseif($Count_Articles == 0 && !isset($_GET['topic'])){
	$Table->Open();
	echo _CURRENTLY_NO_ARTICLES;
	$Table->Close();
}elseif($Count_Articles == 0 && isset($_GET['topic'])){
	$Table->Open();
	echo _CURRENTLY_NO_ARTICLES_INTOPIC;
	$Table->Close();
}

?>
