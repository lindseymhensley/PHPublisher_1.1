<?php
/*******************************************************************
 **
 ** Admin File: Topics/index.php
 ** Description: Add / edit / remove topics here.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() != 99){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Topics#topic\">"._CREATE_TOPIC."</a></div>";
$Table->Open();
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align=center><?=_CURRENT_TOPICS ?><br><table width="100%"  border="0" cellspacing="5" cellpadding="0">
	<tr>
		<td width="15%" align=center><strong><?=_IMG ?></strong></td>
		<td width="15%" align=center><strong><?=_NAME ?></strong></td>
		<td width="60%" align=center><strong><?=_DESC ?></strong></td>
		<td width="10%" align=center><?=_EDIT ?>?</td>
	</tr><?

	$topic_select = $MySQL->Query("SELECT img, name, description, id FROM ".$pre."topics ORDER BY id");

	while($topic = mysql_fetch_array($topic_select)){
			echo "<tr>";
			echo "<td align=center>"; if($topic['img'] === "none.gif"){ echo _NONE; }elseif(!empty($topic['img'])){ echo "<img src=\"".$base_url."/images/topics/".$topic['img']."\" alt=\"".$topic['description']."\"><br>"; } echo "</td>";
			echo "<td align=center>".$topic['name']."</td>";
			echo "<td align=left>".$topic['description']."</td>";
			echo "<td align=center><strong><a href=\"index.php?find=Admin_Panel&amp;func=Topics&amp;topic_id=".$topic['id']."#topic\">"._EDIT."</a></td>";
			echo "</tr>";
	}
	?></table></td>
  </tr>
</table>
<? $Table->Close(); 
echo "<br>";

if(isset($_POST['submitted'])){
    $_POST['name'] = trim($_POST['name']);
    $_POST['description'] = trim($_POST['description']);
	if(!empty($_POST['name'])){
		if(!empty($_POST['description'])){
			if(isset($_POST['update'])){
				$MySQL->Query("UPDATE ".$pre."topics SET name = '".$_POST['name']."', description = '".$_POST['description']."', img = '".$_POST['topic_img']."' WHERE id = '".$_GET['topic_id']."'");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Topics&Updated=1");
                		die();
            		}else{
				$Insert_Topic = 'INSERT INTO `'.$pre.'topics` (`id`, `name`, `img`, `description`) VALUES (\'\', \''.$name.'\', \''.$topic_img.'\', \''.$description.'\')';
				$MySQL->Query($Insert_Topic);
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Topics&Succ=1");
				die();
			}
		}else{
			echo _MISSING_TOPIC_DESC."<br><br>";
		}
	}else{
		echo _MISSING_TOPIC_NAME."<br><br>";
	}
}elseif(isset($_GET['Succ'])){
	echo _TOPIC_ADDED."<br><br>";
}elseif(isset($_GET['Updated'])){
	echo _TOPIC_UPDATED."<br><br>";
}elseif(isset($_GET['delete'])){
	if(isset($_GET['topic_id'])){
		$check_topic = $MySQL->Rows("SELECT id FROM ".$pre."topics WHERE id = '".$_GET['topic_id']."'");
		if($check_topic == 0){
			echo _MISSING_TOPIC_ID."<br><br>";
		}else{
			$MySQL->Query("DELETE FROM ".$pre."topics WHERE id = '".$_GET['topic_id']."' LIMIT 1");
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Topics&deleted=1");
			die();
		}
	}
}elseif(isset($_GET['deleted'])){
	echo _TOPIC_REMOVED."<br><br>";
}
if(isset($_GET['topic_id'])){
    $tID = "&amp;topic_id=". $_GET['topic_id'];
}else{
    $tID = "";
}
$Table->Open("<a href=\"index.php?find=Admin_Panel&amp;func=Topics\"><b>"._ADD_A_TOPIC."</b></a>");
echo "<a name=\"topic\"><form action=\"index.php?find=Admin_Panel&amp;func=Topics".$tID."\" method=\"post\" onload=\"checkTheBox\"  enctype=\"multipart/form-data\" name=\"creator\">"; ?>
<input name="submitted" type="hidden" value="1">
<? if(isset($_GET['topic_id'])) $topic = $MySQL->Fetch("SELECT name, description, img FROM ".$pre."topics WHERE id = '".$_GET['topic_id']."'");
if(isset($_GET['topic_id'])){ ?>
<input name="name" type="hidden" value="<?=$topic['name'] ?>">
<input name="update" type="hidden" value="1">
<? } ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="30%" align=left><?=_NAME ?>:</td>
    <td width="70%" align=left><? if(isset($_GET['topic_id'])){ echo $topic['name']; }else{ ?><input type="text" name="name" value=""> <? } ?></td>
  </tr>
  <tr>
    <td align=left><?=_DESC ?>:</td>
    <td align=left><input type="text" name="description" value="<?=$topic['description'] ?>"></td>
  </tr>
  <tr>
    <td align=left><?=_IMG ?>:</td>
    <td align=left><? 
		echo "<script langauge='javascript'>               
                  function showavatar(theURL) {                   
                    document.images.show_topic.src=theURL+document.creator.topic_img.options[document.creator.topic_img.selectedIndex].value;
                  }                
          </script>";
	
	if ($handle = opendir($Current_Directory.'/images/topics')) {
		echo "<img src=\"".$base_url."/images/topics/none.gif\" name=\"show_topic\" border=\"0\" hspace=\"15\"><br>";
		echo "<select name=\"topic_img\" size=\"0\" onchange=\"showavatar('images/topics/')\">";

			while (false !== ($img = readdir($handle))) { 
				if ($img == "." || $img == ".." || $img == "index.htm"){					
				} else{
					echo "<option value=\"".$img."\" "; if($topic['img'] === $img) echo " selected"; echo ">".$img."</option>\n";
				}
			}
		echo " </select><br>";
		closedir($handle); 
	}
	?></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
	<td align=left><input type="submit" name="Submit" value="Submit"> <? if(isset($_GET['topic_id'])){ echo "[ <a href=\"index.php?find=Admin_Panel&amp;func=Topics&amp;topic_id=".$_GET['topic_id']."&amp;delete=1\">"._DELETE_TOPIC."</a> ]";  } ?></td>
  </tr>
</table>
</form>
<? $Table->Close(); ?>
