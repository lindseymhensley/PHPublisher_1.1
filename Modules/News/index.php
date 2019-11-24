<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($Site_Frontpage == 1){
	$Table->Open();
		echo $Site_Frontpage_Content;
	$Table->Close();
	echo "<br />";
}

echo "<b>"._NEWS_UPDATES."</b><br /><br />";

if(is_numeric($_SESSION['Art_Ses']['Sessioned'])){
	$count_em = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles");
	if(($_SESSION['Art_Ses']['Total'] > $count_em) || ($_SESSION['Art_Ses']['Total'] < $count_em)){
		unset($_SESSION['Art_Ses']);
		unset($_SESSION['Arts_Ses']);
	}
}
$Articles = $MySQL->Query("SELECT Article_ID, Article_Title, Article_Topic, Article_Story, Article_Full_Story, Article_Views, Article_Author, Article_Date FROM ".$pre."News_Articles ORDER BY Article_Time DESC LIMIT ".$Show_New_News."");
$i = 1;

while($post = mysql_fetch_array($Articles)){
	if(empty($_SESSION['Art_Ses'][$post['Article_ID']]) && !is_numeric($_SESSION['Art_Ses']['Sessioned'])){
		$_SESSION['Article_ID'][$post['Article_ID']] = $post['Article_ID'];
		$_SESSION['Article_Title'][$post['Article_ID']] = $post['Article_Title'];
		$_SESSION['Article_Topic'][$post['Article_ID']] = "<b><a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$post['Article_Topic']."\">".$post['Article_Topic']."</a>:</b>";
		$topic = $MySQL->Fetch("SELECT img FROM ".$pre."topics WHERE name = '".$post['Article_Topic']."'");
		if($topic['img'] != "none.gif"){
			$_SESSION['Article_Topic_Img'][$post['Article_ID']] = "<a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$post['Article_Topic']."\"><img src=\"".$base_url."/images/topics/".$topic['img']."\" alt=\"".$post['Article_Topic']."\" border=\"0\" /></a>";
		}else{
			$_SESSION['Article_Topic_Img'][$post['Article_ID']] = "";
		}
		$_SESSION['Article_Story'][$post['Article_ID']] = nl2br($post['Article_Story']);
		$_SESSION['Article_Full'][$post['Article_ID']] = nl2br($post['Article_Full_Story']);
		$_SESSION['Article_Views'][$post['Article_ID']] = $post['Article_Views'];
		if($Censor_Words == 1){
			$_SESSION['Article_Full'][$post['Article_ID']] = censor($_SESSION['Article_Full'][$post['Article_ID']]);
			$_SESSION['Article_Story'][$post['Article_ID']] = censor($_SESSION['Article_Story'][$post['Article_ID']]);
		}
		if($Emoticon_On == 1){
			$_SESSION['Article_Full'][$post['Article_ID']] = emoticon($_SESSION['Article_Full'][$post['Article_ID']]);
			$_SESSION['Article_Story'][$post['Article_ID']] = emoticon($_SESSION['Article_Story'][$post['Article_ID']]);
		}
		if($BBcode_On == 1){
			$_SESSION['Article_Full'][$post['Article_ID']] = bbcode($_SESSION['Article_Full'][$post['Article_ID']]);
			$_SESSION['Article_Story'][$post['Article_ID']] = bbcode($_SESSION['Article_Story'][$post['Article_ID']]);
		}
		
		$_SESSION['Comment_Count'][$post['Article_ID']] = $MySQL->Rows("SELECT * FROM ".$pre."Article_Comments WHERE Story_ID = ".$_SESSION['Article_ID'][$post['Article_ID']]."");
		$_SESSION['Read_More'][$post['Article_ID']] = "<a href=\"index.php?find=News&amp;file=Full_Story&amp;Show_Full=1&amp;Article_ID=".$_SESSION['Article_ID'][$post['Article_ID']]."\">"._SEE_FULL_STORY."</a>";
		$_SESSION['Post_Comment'][$post['Article_ID']] = "<a href=\"index.php?find=News&amp;file=Full_Story&amp;Show_Full=1&amp;Article_ID=".$_SESSION['Article_ID'][$post['Article_ID']]."#Post_Comment\">"._POST_COMMENT."</a>";
		$profile = $MySQL->Fetch("SELECT user_id FROM ".$pre."users WHERE username = '".$post['Article_Author']."' LIMIT 1");
		$_SESSION['Article_Date'][$post['Article_ID']] = $post['Article_Date'];
		$_SESSION['Article_Author'][$post['Article_ID']] = _POSTED_BY ." <a href=\"index.php?find=Profile&amp;user_id=".$profile['user_id']."\">". $post['Article_Author'] ."</a>";
		$_SESSION['Art_Ses']['Total'] = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles");
		$_SESSION['Art_Ses'][$post['Article_ID']] = $post['Article_ID'];
		$count_articles = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles");
		if(($i == $count_articles) || ($i == $Show_New_News)){
			$_SESSION['Art_Ses']['Sessioned'] = 1;
			if(empty($_GET['no_session'])){
				header("Location: ".$base_url."/index.php");
				die();
			}
		}
	}
	$user->theme("news.tpl", array("url" => $base_url, "Post_Comment" => $_SESSION['Post_Comment'][$post['Article_ID']],"Reads" => $_SESSION['Article_Views'][$post['Article_ID']], "Comment_Count" => $_SESSION['Comment_Count'][$post['Article_ID']], "Article_Topic_Img" => $_SESSION['Article_Topic_Img'][$post['Article_ID']], "Article_Topic" => $_SESSION['Article_Topic'][$post['Article_ID']], "Article_Title" => $_SESSION['Article_Title'][$post['Article_ID']], "Article_Story" => $_SESSION['Article_Story'][$post['Article_ID']], "Article_Author" => $_SESSION['Article_Author'][$post['Article_ID']], "Article_Date" => $_SESSION['Article_Date'][$post['Article_ID']], "Read_More" => $_SESSION['Read_More'][$post['Article_ID']]));
	$i++;
}
echo "<a href=\"index.php?find=News&amp;file=View_Articles\">"._OLD_ARTICLES."</a>";

?>