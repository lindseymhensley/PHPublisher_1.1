<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if(isset($_GET['search'])){
	$_POST['search'] = $_GET['search'];
}
if(isset($_GET['wlid']) && is_numeric($_GET['wlid'])){
	if(($grab_wl = $MySQL->Fetch("SELECT url FROM ".$pre."link WHERE id = '".$_GET['wlid']."'")) !== false){
		$weblink = $grab_wl['url'];
		$MySQL->Query("UPDATE ".$pre."link SET views=views+1 WHERE id = '".$_GET['wlid']."'");
		header("location: ".$weblink);
	}else{
		header("location: ".$base_url."/index.php?find=Web Links");
		die();
	}
}
$Table->Open();
echo "<form action=\"index.php?find=Web Links\" method=\"post\">
<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
  <tr>
    <td colspan=\"2\" align=\"center\"><img src=\"".$base_url."/images/Weblinks.png\" alt=\"Web Links\"><br /><br /></td>
  </tr>
  <tr>
    <td width=\"55%\" align=\"right\"><input type=\"text\" name=\"search\" maxlength=\"50\"></td>
    <td width=\"45%\" align=\"left\"><input type=\"submit\" name=\"Submit\" value=\"Search\"></td>
  </tr>
</table></form>[ <a href=\"index.php?find=Web Links&amp;add_link=1\">"._ADD_WEBLINK."</a> ]";
$Table->Close();
echo "<br />";
switch($_GET['message']){
	case 1:
		echo "<br />"._LINK_SUBMITTED."<br /><br />";
	break;
}
if(isset($_GET['add_link'])){
	if(isset($_POST['Submit'])){
		if(!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['link'])){
			$added_date = date("m").".".date("d").".".date("y");
			$link_sql = "INSERT INTO `".$pre."link`	( `id` , `title` , `description` , `url` , `cid` , `views` , `added_on` , `approved` , `username` ) VALUES ('', '".$_POST['title']."', '".$_POST['description']."', '".$_POST['link']."', '0', '0', '".$added_date."', '0', '".$user->name()."')";
			$MySQL->Query($link_sql);
			header("location: ".$base_url."/index.php?find=Web Links&message=1");
			die();
		}else{
			echo _MISSING_ADDLINK_FIELDS;
		}
	}else{
		if($user->id() > 1){
			$Table->Open("<strong>"._ADD_WEBLINK."</strong>");
			echo "<form method=\"post\" action=\"index.php?find=Web Links&amp;add_link=1\">
					Please fill out the form below.<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
					<tr>
					<td width=\"20%\" align=\"right\">"._TITLE.":</td>
					<td width=\"80%\" align=\"left\"><input type=\"text\" name=\"title\"></td>
					</tr>
					<tr>
					<td align=\"right\">"._LINK.":</td>
					<td align=\"left\"><input type=\"text\" name=\"link\"></td>
					</tr>
					<tr>
					<td align=\"right\" valign=\"top\">"._DESC.":</td>
					<td align=\"left\"><textarea name=\"description\" cols=\"35\" rows=\"5\"></textarea></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>
					</tr>
					</table>
					</form>";
			$Table->Close();
		}else{
			echo "<br />"._ONLY_REGISTERED_USERS_MAY_SEND_LINKS."<br /><br />";
		}
	}
}else{
	if((isset($_GET['cid']) && is_numeric($_GET['cid'])) && $_GET['cid'] > 0){
		if($MySQL->Rows("SELECT id FROM ".$pre."link_categories WHERE id = '".$_GET['cid']."'") > 0){
			$cat_sql = $MySQL->Query("SELECT id, title FROM ".$pre."link_categories WHERE sub_category_id = '".$_GET['cid']."'");
		}else{
			header("location: ".$base_url."/index.php?find=Web Links");
			die();
		}
	}
		
	if(empty($_GET['cid'])){
		$_GET['cid'] = 0;
	}
	$cat_sql = "SELECT id, title FROM ".$pre."link_categories WHERE sub_category_id = '".$_GET['cid']."'";
	if($MySQL->Rows($cat_sql) > 0){
		$Table->Open(_BROWSE_BY_CATEGORY);
		$query_categories = $MySQL->Query($cat_sql);
		$i = 1;
		echo "<table width=\"65%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\" style=\"margin-top: 20px\" align=\"center\">";
		while($cat = mysql_fetch_array($query_categories)){
			if($i == 1){
				echo "<tr><td width=\"40%\" valign=\"top\"><div style=\"text-align: left; font-weight: bold; margin-bottom: 5px;\">&middot; <a href=\"index.php?find=Web Links&amp;cid=".$cat['id']."\">".$cat['title']."</a></div></td><td width=\"10%\"></td><td width=\"10%\">";
				unset($g);
			}else{
				echo "&nbsp;</td><td width=\"40%\" valign=\"top\"><div style=\"text-align: left; font-weight: bold; margin-bottom: 5px;\">&middot; <a href=\"index.php?find=Web Links&amp;cid=".$cat['id']."\">".$cat['title']."</a></div></td></tr>";
				unset($i);
				$g = 1;
			}
			$i++;
		}
		if(isset($g)){
			echo "</table><br />";
		}else{
			echo "&nbsp;</td></tr></table><br />";
		}
		$Table->Close();
		echo "<br />";
	}
	if(isset($_GET['cid']) && $_GET['cid'] != 0){
			echo "<a href=\"javascript:history.go(-1)\">"._GO_BACK."</a><br /><br />";
	}

	$Table->Open("<strong>"._AVAILABLE_WEBLINKS."</strong>");
	if(empty($_GET['cid'])){
		$_GET['cid'] = 0;
	}
		
	$Count_Stuff = $MySQL->Rows("SELECT approved FROM ".$pre."link WHERE approved = '1'");
	$Count_Div = $Count_Stuff / 10;
	$Ro_Count = ceil($Count_Div);
	if(!isset($_GET['show_page']) || empty($_GET['show_page'])){
		$_GET['show_page'] = 1;
	}
	
	if($_GET['show_page'] > $Ro_Count){
			$_GET['show_page'] = 1;
	}
	
	$ii = 1;
	for($ii = 1; $ii <= $Ro_Count; $ii++){
		if($_GET['show_page'] == $ii){
			$second = $ii * 10;
			$first = $second - 10;
			$limit_show = "".$first.", 10"; 
		}
	}
	if($Count_Stuff == 0){
		$limit_show = 1;
	}
	if(!empty($_POST['search'])){
		$wl_sql = "SELECT views, added_on, description, id, title, username FROM ".$pre."link  WHERE (approved = '1') AND ((title LIKE '%".htmlspecialchars(addslashes($_POST['search']))."%') OR (description LIKE '%".htmlspecialchars(addslashes($_POST['search']))."%')) ORDER BY id DESC LIMIT ".$limit_show;
	}else{
		$wl_sql = "SELECT views, added_on, description, id, title, username FROM ".$pre."link WHERE (approved = '1') AND (cid = '".$_GET['cid']."') ORDER BY id DESC LIMIT ".$limit_show;
	}
	if($MySQL->Rows($wl_sql) > 0){
		echo "<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\" style=\"margin-top: 20px\" align=\"center\">";
	
		$i = 1;
		$wl_sql = $MySQL->Query($wl_sql);
		while($dl = mysql_fetch_array($wl_sql)){
			if($i == 1){
				echo "<tr><td align=\"right\" width=\"50%\" valign=\"top\">
				<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td align=\"left\">
				<div>
				<div style=\"margin:2px\"><img src=\"".$base_url."/images/lwin.gif\" alt=\"\" style=\"margin-right: 2px;\" /><strong>"._TITLE.":</strong> &nbsp; <a href=\"index.php?find=Web Links&wlid=".$dl['id']."\" target=\"_BLANK\">".stripslashes($dl['title'])."</a></div>
				<div style=\"margin:2px; margin-left: 11px;\"><strong>"._AUTHOR.":</strong>&nbsp; ".$dl['username']."</div>
				<div style=\"margin:2px; margin-left: 11px; text-align: justify;\"><strong>"._DESCRIPTION.":</strong>&nbsp; ".$dl['description']."</div>
				<div style=\"margin:2px; margin-left: 11px;\"><strong>"._VIEWS.":</strong>&nbsp; ".$dl['views']."</div>
				<div style=\"margin:2px; margin-left: 11px;\"><strong>"._ADDED_ON.":</strong>&nbsp; ".$dl['added_on']."</div>
				</div></td></tr></table>
				</td>";
				unset($g);
			}else{
				echo "<td align=\"right\" width=\"50%\" valign=\"top\">
				<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td align=\"left\">
				<div>
				<div style=\"margin:2px\"><img src=\"".$base_url."/images/lwin.gif\" alt=\"\" style=\"margin-right: 2px;\" /><strong>"._TITLE.":</strong> &nbsp; <a href=\"index.php?find=Web Links&wlid=".$dl['id']."\" target=\"_BLANK\">".stripslashes($dl['title'])."</a></div>
				<div style=\"margin:2px; margin-left: 11px;\"><strong>"._AUTHOR.":</strong>&nbsp; ".$dl['username']."</div>
				<div style=\"margin:2px; margin-left: 11px; text-align: justify;\"><strong>"._DESCRIPTION.":</strong>&nbsp; ".$dl['description']."</div>
				<div style=\"margin:2px; margin-left: 11px;\"><strong>"._VIEWS.":</strong>&nbsp; ".$dl['views']."</div>
				<div style=\"margin:2px; margin-left: 11px;\"><strong>"._ADDED_ON.":</strong>&nbsp; ".$dl['added_on']."</div>
				</div></td></tr></table>
				</td></tr>";
				unset($i);
				$g = 1;
			}
			$i++;
		}
		if(isset($g)){
			echo "</table>";
		}else{
			echo "</tr></table>";
		}
		
		echo "<strong>"._WEBLINK_PAGE.":</strong> ";
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
		if(isset($_POST['search'])){
			$search = "&amp;search=".$_POST['search'];
		}
		for($i = 1; $i <= $Ro_Count; $i++){
			echo "<option value=\"index.php?find=Web Links&amp;cid=".$_GET['cid'].$search."&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
		}
		echo "</select></form>";	
		
	}else{
		if(!empty($_POST['search'])){
			echo _NO_RESULTS;
		}else{
			echo _NO_WEBLINKS;
		}
	}
	$Table->Close();
}
?>
