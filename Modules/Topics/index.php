<?
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
$Table->Open();
?>
<table width="100%"  border="0" cellspacing="5" cellpadding="0">
<?

	$topic_select = $MySQL->Query("SELECT img, name FROM ".$pre."topics ORDER BY id");
	$i = 1;
	while($topic = mysql_fetch_array($topic_select)){
		if($i == 1){
			echo "<tr>";
			echo "<td align=center>"; if($topic['img'] === "none.gif"){ echo _NONE."<br><a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$topic['name']."\">".$topic['name']."</a></td>"; }elseif(!empty($topic['img'])){ echo "<a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$topic['name']."\"><img src=\"".$base_url."/images/topics/".$topic['img']."\" alt=\"".$topic['description']."\" border=0><br>".$topic['name']."</a></td>"; }
		}elseif($i >= 2 && $i <= 4){
			echo "<td align=center>"; if($topic['img'] === "none.gif"){ echo _NONE."<br><a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$topic['name']."\">".$topic['name']."</a></td>"; }elseif(!empty($topic['img'])){ echo "<a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$topic['name']."\"><img src=\"".$base_url."/images/topics/".$topic['img']."\" alt=\"".$topic['description']."\" border=0><br>".$topic['name']."</a></td>"; }
		}elseif($i == 5){
			echo "<td align=center>"; if($topic['img'] === "none.gif"){ echo _NONE."<br><a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$topic['name']."\">".$topic['name']."</a></td>"; }elseif(!empty($topic['img'])){ echo "<a href=\"index.php?find=News&amp;file=View_Articles&amp;show_page=1&amp;topic=".$topic['name']."\"><img src=\"".$base_url."/images/topics/".$topic['img']."\" alt=\"".$topic['description']."\" border=0><br>".$topic['name']."</a></td>"; }
			echo "</tr>";
			$i = 0;
		}
		$i++;
	}
	if($i == 5){
		echo "</table><br />";
	}else{
		echo "</tr></table><br />";
	}
	?>
<? $Table->Close(); ?>