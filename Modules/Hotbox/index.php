<?php
if (!defined("IN_DB"))
{
	die("Hacking Attempt!");
}
$Table->Open("<strong>"._CHAT_LOG."</strong>");
if ($user->lvl() == 99)
{
	echo "<a href=\"index.php?find=Hotbox&delete_all=1\">"._DELETE_ALL_TAGS."</a><br /><br />";
}
if (is_numeric($_GET['delete']) && is_numeric($_GET['tid']) && $user->lvl() >= 3)
{	
	if (($tag_info = $MySQL->Fetch("SELECT tag_id FROM ".$pre."hotbox WHERE tag_id = ".$_GET['tid'])) !== FALSE)
	{
		$MySQL->Query("DELETE FROM ".$pre."hotbox WHERE tag_id = ".$tag_info['tag_id']." LIMIT 1");
		$_SESSION['tagg_id'] = NULL;
		$_SESSION['tagg_ip'] = NULL;
		$_SESSION['tagg'] = NULL;
	}
}elseif (is_numeric($_GET['delete_all']))
{
	if (is_numeric($_GET['confirm']))
	{
		$MySQL->Query("TRUNCATE TABLE ".$pre."hotbox");
		echo _SUCCESS_PRUNE."<br /><BR />";
	}else
	{
		echo _R_U_SURE."<br /><a href=\"index.php?find=Hotbox&delete_all=1&confirm=1\">"._YES."</a> | <a href=\"index.php?find=Hotbox\">"._NO."</a><br /><br />";
	}
}

$count_tag = $MySQL->Rows("SELECT * FROM ".$pre."hotbox");
if ($count_tag > 0)
{
	if (empty($_SESSION['tagg']['count']))
	{
		$_SESSION['tagg']['count'] = $MySQL->Rows("SELECT tag_id FROM ".$pre."hotbox");
	}
	$Count_Div = $_SESSION['tagg']['count'] / 100;
	$Ro_Count = ceil($Count_Div);
	if (!isset($_GET['show_page']) || empty($_GET['show_page']))
	{
		$_GET['show_page'] = 1;
	}
	
	if ($_GET['show_page'] > $Ro_Count)
	{
		if ($_SESSION['tagg']['count'] <= 0)
		{
			$notags = 1;
		}elseif($_SESSION['tagg']['count'] > 0)
		{
			$_GET['show_page'] = 1;
		}
	}
	
	$i = 1;
	for($i = 1; $i <= $Ro_Count; $i++){
		if ($_GET['show_page'] == $i)
		{
			$second = $i * 100;
			$first = $second - 100;
			$limit_show = "".$first.", 100"; 
		}
	}
	if (isset($notags))
	{
		$limit_show = 1;
	}elseif($_SESSION['tagg']['count'] <= 100)
	{
		$limit_show = $_SESSION['tagg']['count'];
	}
	if (empty($_SESSION['tagg'][$_GET['show_page']]['SQL']))
	{
		$_SESSION['tagg'][$_GET['show_page']]['SQL'] = $MySQL->Query("SELECT * FROM ".$pre."hotbox ORDER BY tag_id DESC LIMIT ".$limit_show);
	}
	$ic_tags = 1;		
	echo "<div style=\"text-align: left;\">";
	while($tag = mysql_fetch_array($_SESSION['tagg'][$_GET['show_page']]['SQL']))
	{
		if (!isset($_SESSION['tagg'][$_GET['show_page']][$ic_tags]))
		{
			$tag_post = $tag['tag'];
			if ($Censor_Words == 1)
			{
				$tag_post = censor($tag_post);
			}
			if ($Emoticon_On == 1)
			{
				$tag_post = emoticon($tag_post);
			}
			if ($BBcode_On == 1)
			{
				$tag_post = bbcode($tag_post);
			}
			$_SESSION['tagg_id'][$_GET['show_page']][$ic_tags] = $tag['tag_id'];
			$_SESSION['tagg_ip'][$_GET['show_page']][$ic_tags] = $tag['ip'];
			$_SESSION['tagg'][$_GET['show_page']][$ic_tags] = "<strong>".$tag['username']."</strong>: ".stripslashes($tag_post)."<br />";
			if ($_GET['show_page'] == $Ro_Count)
			{
				$difference = $_SESSION['tagg']['count'] - $first;
			}else{
				$difference = 100;
			}
			if ($ic_tags == $difference)
			{
				header("location: ".$base_url."/index.php?find=Hotbox&show_page=".$_GET['show_page']);
				die();
			}
		}else
		{
			if ($user->lvl() >= 3)
			{
				$admin_power = "[<a href=\"index.php?find=Hotbox&delete=1&tid=".$_SESSION['tagg_id'][$_GET['show_page']][$ic_tags]."\">"._DELETE."</a>] <em>".$_SESSION['tagg_ip'][$_GET['show_page']][$ic_tags]."</em> ";
			}
			echo $admin_power.$_SESSION['tagg'][$_GET['show_page']][$ic_tags];
		}
		$ic_tags++;
	}
	echo "</div>";
	
	if ($_SESSION['tagg']['count'] > 100)
	{
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
		
		for($ii = 1; $ii <= $Ro_Count; $ii++){
			echo "<option value=\"index.php?find=Hotbox&amp;show_page=".$ii."\""; if($_GET['show_page'] == $ii){ echo "selected"; } echo ">".$ii."</option>";
		}
		echo "</select></form>";
	}
}else
{
	echo _NO_TAG;
}
$Table->Close();
?>