<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() != 99){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}

if(isset($_GET['Delete'])){
	if(isset($_GET['group_id'])){
		$group_info = $MySQL->Fetch("SELECT name FROM ".$pre."user_groups WHERE group_id = '".$_GET['group_id']."'");
		if($group_info == false){
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=4");
			die();
		}		
		if(isset($_GET['Confirm'])){
			$MySQL->Query("DELETE FROM ".$pre."user_groups WHERE group_id = '".$_GET['group_id']."' LIMIT 1");
			$MySQL->Query("UPDATE ".$pre."users SET user_group = 0 WHERE user_group = '".$_GET['group_id']."'");
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=7&name=".$_GET['name']);
			die();
		}else{		
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=4&message=6&group_id=".$_GET['group_id']."&name=".$group_info['name']);
			die();
		}
	}else{
		header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups");
		die();
	}
}

if(isset($_POST['Submit'])){
	$g_name = trim($_POST['g_name']);
	$g_desc = trim($_POST['g_desc']);
	if(!empty($g_name) && !empty($g_desc)){
			switch($_POST['action'])
			{
				case "create":
					if($MySQL->Rows("SELECT name FROM ".$pre."user_groups WHERE name = '".$g_name."'") > 0){
						header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=3&name=".$g_name);
						die();
					}
					$MySQL->Query("INSERT INTO ".$pre."user_groups VALUES ('', '".$g_name."', '".$g_desc."')");
					header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=2&name=".$g_name);
					die();
				break;
				
				case "edit":
					if($g_name !== $_POST['group_name']){
						if($MySQL->Rows("SELECT name FROM ".$pre."user_groups WHERE name = '".$g_name."'") > 0){
							header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=3&name=".$g_name);
							die();
						}
					}
					if($MySQL->Rows("SELECT group_id FROM ".$pre."user_groups WHERE group_id = '".$_POST['group_id']."'") == 0){
						header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=4");
						die();
					}
					$MySQL->Query("UPDATE ".$pre."user_groups SET name = '".$g_name."', description = '".$g_desc."' WHERE group_id = '".$_POST['group_id']."'");
					header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=5&name=".$g_name);
					die();
				break;				
			}
	}else{
		header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=1");
		die();
	}
}

switch ($_GET['message'])
{
	case 1:
		echo _GROUP_FIELDS_MISSING."<br /><br />";
	break;
	
	case 2:
		echo _GROUP_CREATED."<br /><br />";
	break;
	
	case 3:
		echo _GROUP_ALREADY_EXISTS."<br /><br />";
	break;
	
	case 4:
		echo _GROUP_DOESNT_EXISTS."<br /><br />";
	break;
	
	case 5:
		echo _GROUP_MODIFIED."<br /><br />";
	break;
	
	case 6:
		echo _ARE_YOU_SURE_DELETE_GROUP."<br /><br /><a href=\"index.php?find=Admin_Panel&amp;func=User Groups&amp;Delete=1&amp;Confirm=1&amp;name=".$_GET['name']."&amp;group_id=".$_GET['group_id']."\">"._YES."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=User Groups\">"._NO."</a><br/><br />";
	break;
	
	case 7:
		echo _GROUP_DELETED."<br /><br />";
	break;
}

echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=User Groups#create_group\">"._CREATE_GROUP."</a></div>";
$Table->Open(_USER_GROUPS);
echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
		<tr>
		<td width=\"20%\"  class=\"table\">"._GROUP_NAME."</td>
		<td width=\"65%\"  class=\"table\">"._GROUP_DESC."</td>
		<td width=\"15%\"  class=\"table\">"._ACTIONS."</td>
		</tr>";
		$group_sql = $MySQL->Query("SELECT name, description, group_id FROM ".$pre."user_groups");
		while($group = mysql_fetch_array($group_sql)){
			echo "<tr>"
			."<td align=\"left\" class=\"table\">".$group['name']."</td>"
			."<td align=\"left\" class=\"table\">".$group['description']."</td>"
			."<td align=\"left\" class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;func=User Groups&amp;group_id=".$group['group_id']."\">"._EDIT."</a> - <a href=\"index.php?find=Admin_Panel&amp;func=User Groups&amp;Delete=1&amp;group_id=".$group['group_id']."\">"._DELETE."</a></td>"
			."</tr>";
		}  
echo "</table>";
$Table->Close();
echo "<br />";
$Table->Open(_CREATE_EDIT_GROUP);
echo "<a name=\"create_group\"></a><form action=\"index.php?find=Admin_Panel&amp;func=User Groups\" method=\"post\">";
if(isset($_GET['group_id'])){
	$group_info = $MySQL->Fetch("SELECT name, description FROM ".$pre."user_groups WHERE group_id = '".$_GET['group_id']."'");
	if($group_info == false){
		header("location: ".$base_url."/index.php?find=Admin_Panel&func=User Groups&message=4");
		die();
	}
	echo "<input name=\"action\" type=\"hidden\" value=\"edit\">";
	echo "<input name=\"group_id\" type=\"hidden\" value=\"".$_GET['group_id']."\">";
	echo "<input name=\"group_name\" type=\"hidden\" value=\"".$group_info['name']."\">";
}else{
	echo "<input name=\"action\" type=\"hidden\" value=\"create\">";
}
echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td width=\"20%\" align=\"left\">"._GROUP_NAME."</td>
<td width=\"80%\" align=\"left\"><input type=\"text\" name=\"g_name\" maxlength=\"25\" value=\"".$group_info['name']."\"></td>
</tr>
<tr>
<td align=\"left\">"._GROUP_DESC."</td>
<td align=\"left\"><input type=\"text\" name=\"g_desc\" maxlength=\"255\" value=\"".$group_info['description']."\"></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align=\"left\"><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>
</tr>
</table></form>";
$Table->Close();
?>