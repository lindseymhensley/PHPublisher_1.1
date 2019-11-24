<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
$Table->Open("<strong>"._SELECT_A_MANAGER."</strong>");
		echo "<a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;manager=Request\">"._REQUEST_MANAGER."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;manager=Category\">"._CATEGORY_MANAGER."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;manager=Link\">"._LINK_MANAGER."</a>";
$Table->Close();
echo "<br />";
switch($_GET['message'])
{
	case 1:
		echo _NO_SUCH_LINK."<br /><br />";
	break;
	case 2:
		echo _LINK_REMOVED."<br /><br />";
	break;
	case 3:
		echo _MISSING_REQUIRED_LINK_FIELDS."<br /><br />";
	break;
	case 4:
		echo _LINK_CREATED."<br /><br />";
	break;
	case 5:
		echo _LINK_UPDATED."<br /><br />";
	break;
	case 6:
		echo _NO_SUCH_CATEGORY."<br /><br />";
	break;
	case 7:
		echo _CATEGORY_REMOVED."<br /><br />";
	break;
	case 8:
		echo _MISSING_REQUIRED_CATEGORY_FIELDS."<br /><br />";
	break;
	case 9:
		echo _CATEGORY_UPDATED."<br /><br />";
	break;
	case 10:
		echo _CATEGORY_CREATED."<br /><br />";
	break;
}
$create_code = 9999999999;
$added_date = date("m").".".date("d").".".date("y");
if(isset($_GET['lid']) && is_numeric($_GET['lid'])){
	if($_GET['lid'] != $create_code){
		if(($link = $MySQL->Fetch("SELECT id, title, username,  description, url, cid, added_on, approved FROM ".$pre."link WHERE id = '".$_GET['lid']."'")) !== false){
			if(isset($_POST['Submit'])){
				if(!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['url'])){
					if(isset($_POST['request'])){
						if($_POST['approved'] == 0){
							header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Request&func=Web Links&Delete=1&lid=".$link['id']);
							die();
						}else{
							$MySQL->Query("UPDATE ".$pre."link SET approved=1, title='".htmlspecialchars(addslashes($_POST['title']))."', description='".htmlspecialchars($_POST['description'])."', url='".htmlspecialchars(addslashes($_POST['url']))."', cid='".$_POST['category']."' WHERE id = '".$_GET['lid']."'");
							header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Link&func=Web Links&message=5");
							die();
						}
					}else{
							$MySQL->Query("UPDATE ".$pre."link SET title='".htmlspecialchars(addslashes($_POST['title']))."', description='".htmlspecialchars($_POST['description'])."', url='".htmlspecialchars(addslashes($_POST['url']))."', cid='".$_POST['category']."' WHERE id = '".$_GET['lid']."'");
							header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Link&func=Web Links&message=5");
							die();
					}
				}else{
					header("location: ".$base_url."/index.php?find=Admin_Panel&func=Web Links&manager=Link&message=3");
					die();
				}
			}
			if(isset($_GET['Delete'])){
				if(isset($_GET['Confirm'])){
					$MySQL->Query("DELETE FROM ".$pre."link WHERE id = '".$link['id']."' LIMIT 1");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Link&func=Web Links&message=2&title=".$link['title']);
					die();
				}else{
					echo _R_U_SURE_DELETE_LINK."<br /><br /><a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;Delete=1&amp;lid=".$link['id']."&amp;title=".$link['title']."&amp;Confirm=1\">"._YES."</a> | <a href=\"index.php?file=Admin_Panel&amp;func=Web Links\">"._NO."</a><br /><br />";
				}
			}
		}else{
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=Web Links&message=1");
			die();
		}
	}else{
		if(isset($_POST['Submit'])){
			if(!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['url'])){
					$MySQL->Query("INSERT INTO ".$pre."link VALUES ('', '".htmlspecialchars(addslashes($_POST['title']))."', '".htmlspecialchars($_POST['description'])."', '".htmlspecialchars(addslashes($_POST['url']))."', '".$_POST['category']."', '0', '".$added_date."', '1', '".$user->name()."')");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Link&func=Web Links&message=4");
					die();
			}else{
				header("location: ".$base_url."/index.php?find=Admin_Panel&func=Web Links&manager=Link&message=3");
				die();
			}
		}
	}
	$Table->Open();
	if(isset($_GET['lid'])){
		$link_id = "&amp;lid=".$_GET['lid'];
	}
	echo "<form action=\"index.php?find=Admin_Panel&amp;func=Web Links".$link_id."\" method=\"post\">";
	echo "<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
		  <tr>
			<td width=\"30%\" align=\"right\"><strong>"._AUTHOR.":</strong></td>
			<td width=\"70%\" align=\"left\">"; if($_GET['lid'] == $create_code){ echo $user->name(); }else{ echo $link['username']; } echo "</td>
		  </tr>
		  <tr>
			<td align=\"right\"><strong>"._TITLE.":</strong></td>
			<td align=\"left\"><input type=\"text\" name=\"title\" value=\"".$link['title']."\"></td>
		  </tr>
		  <tr>
			<td align=\"right\" valign=\"top\"><strong>"._DESCRIPTION.":</strong></td>
			<td align=\"left\" ><textarea name=\"description\" cols=\"50\" rows=\"5\">".$link['description']."</textarea></td>
		  </tr>
		  <tr>
			<td align=\"right\" ><strong>"._LINK.":</strong></td>
			<td align=\"left\" ><input type=\"text\" name=\"url\" value=\"".$link['url']."\">"; if($_GET['lid'] != $create_code){ echo "<a href=\"".$link['url']."\" target=\"_blank\">"._VISIT."</a>"; } echo "</td>
		  </tr>
		  <tr>
			<td align=\"right\" ><strong"._CATEGORY.":</strong></td>
			<td align=\"left\">
			<select name=\"category\"><option value=\"0\""; if(0 == $link['cid']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
				$cat = $MySQL->Query("SELECT sub_category_id, title, id FROM ".$pre."link_categories");
				while($cati = mysql_fetch_array($cat)){
					if($cati['sub_category_id'] > 0){
						$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."link_categories WHERE id = '".$cati['sub_category_id']."'");
						$name = "["._SUB_OF." ".$subcat['title']."]-> ".$cati['title'];
					}else{
						$name = $cati['title'];
					}
					echo "<option value=\"".$cati['id']."\" "; if($cati['id'] == $link['cid']){ echo "selected=\"selected\""; } echo ">".$name."</option>";
				}
			echo "</select></td>
		  </tr>
			<tr>
			<td align=\"right\"><strong>"._ADDED_ON.":</strong> </td>
			<td align=\"left\">"; if($_GET['lid'] == $create_code){ echo $added_date; }else{ echo $link['added_on']; } echo "</td>
		  </tr>";
		  if(($link['approved'] == 0) && ($_GET['lid'] < $create_code)){
			 echo" <tr>
				<td align=\"right\" ><strong>"._APPROVE.":</strong> </td>
				<td align=\"left\" >
				<input name=\"request\" type=\"hidden\" value=\"1\">
				<select name=\"approved\">
				<option value=\"1\">"._YES."</option>
				<option value=\"0\">"._NO.", "._DELETE."</option>
				</select>
				</td>
		  </tr>";
		  }
			echo "<tr>
			<td colspan=\"2\" align=\"center\" ><input type=\"submit\" name=\"Submit\" value=\"Submit\">"; if($link['approved'] == 1){ echo " [<a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;Delete=1&amp;lid=".$link['id']."\">"._DELETE_LINK."</a>]"; } echo "</td>
		  </tr>
		</table></form>";
		$Table->Close();
}elseif(isset($_GET['cid']) && is_numeric($_GET['cid'])){
	if($_GET['cid'] != $create_code){
		if(($cat = $MySQL->Fetch("SELECT id, title, sub_category_id FROM ".$pre."link_categories WHERE id = '".$_GET['cid']."'")) !== false){
			if(isset($_POST['Submit'])){
				if(!empty($_POST['title'])){
					$MySQL->Query("UPDATE ".$pre."link_categories SET title='".htmlspecialchars(addslashes($_POST['title']))."', sub_category_id='".$_POST['sub_category_id']."' WHERE id = '".$_GET['cid']."'");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Category&func=Web Links&message=9");
					die();
				}
			}
			if(isset($_GET['Delete'])){
				if(isset($_GET['Confirm'])){
					if(isset($_POST['Move_To'])){
						if($_POST['Move_To'] > 0){
							if($MySQL->Rows("SELECT id FROM ".$pre."link_categories WHERE id = '".$_POST['Move_To']."'") == 0){
								header("location: ".$base_url."/index.php?find=Admin_Panel&func=Web Links&manager=Category&message=6");
								die();
							}
						}
						$MySQL->Query("UPDATE ".$pre."link SET cid='".$_POST['Move_To']."' WHERE cid='".$cat['id']."'");
						$MySQL->Query("DELETE FROM ".$pre."link_categories WHERE id = '".$cat['id']."' LIMIT 1");
						header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Category&func=Web Links&message=7&title=".$cat['title']);
						die();
					}else{
						$Table->Open();
							echo _SELECT_CAT_TO_MOVE_TO;
							echo "<form action=\"index.php?find=Admin_Panel&amp;Delete=1&amp;Confirm=1&amp;func=Web Links&amp;cid=".$cat['id']."\" method=\"post\">";
							echo _MOVE_TO.": <select name=\"Move_To\">
							<option value=\"0\""; if(0 == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
							$catr = $MySQL->Query("SELECT sub_category_id, title, id FROM ".$pre."link_categories");
							while($cati = mysql_fetch_array($catr)){
								if($cati['sub_category_id'] > 0){
									$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."link_categories WHERE id = '".$cati['sub_category_id']."'");
									$name = "["._SUB_OF." ".$subcat['title']."]-> ".$cati['title'];
								}else{
									$name = $cati['title'];
								}
								if($cati['id'] == $cat['id']){
									continue;
								}else{
									echo "<option value=\"".$cati['id']."\" "; if($cati['id'] == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">".$name."</option>";
								}
							}
							echo "</select><br /><input name=\"Submit\" type=\"submit\" value=\"Submit\"></form>";
						$Table->Close();
						echo "<br/>";
					}
				}else{
					echo _R_U_SURE_DELETE_CAT."<br /><br /><a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;Delete=1&amp;cid=".$cat['id']."&amp;title=".$cat['title']."&amp;Confirm=1\">"._YES."</a> | <a href=\"index.php?file=Admin_Panel&amp;func=Web Links\">"._NO."</a><br /><br />";
				}
			}
			$Table->Open();
			echo "<form action=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;cid=".$_GET['cid']."\" method=\"post\">
			<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
			  <tr>
				<td width=\"40%\" align=\"right\">"._TITLE.":</td>
				<td width=\"60%\" align=\"left\"><input type=\"text\" name=\"title\" value=\"".$cat['title']."\"></td>
			  </tr>
			  <tr>
				<td align=\"right\">"._SUB_CAT_OF.": </td>
				<td align=\"left\"><select name=\"sub_category_id\">
				<option value=\"0\""; if(0 == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
				$catr = $MySQL->Query("SELECT id, title, sub_category_id FROM ".$pre."link_categories");
				while($cati = mysql_fetch_array($catr)){
					if($cati['sub_category_id'] > 0){
						$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."link_categories WHERE id = '".$cati['sub_category_id']."'");
						$name = "["._SUB_OF." ".$subcat['title']."]-> ".$cati['title'];
					}else{
						$name = $cati['title'];
					}
					if($cati['id'] == $cat['id']){
						continue;
					}else{
						echo "<option value=\"".$cati['id']."\" "; if($cati['id'] == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">".$name."</option>";
					}
				}
			echo "</select></td>
			  </tr>
			  <tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"submit\" name=\"Submit\" value=\"Submit\">"; if($_GET['cid'] != $create_code){ echo " [<a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;Delete=1&amp;cid=".$cat['id']."\">"._DELETE_CAT."</a>]"; } echo "</td>
			  </tr>
			</table></form>";
			$Table->Close();
		}else{
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=Web Links&manager=Category&message=6");
			die();
		}
	}else{
		if(isset($_POST['Submit'])){
			if(!empty($_POST['title'])){
					$MySQL->Query("INSERT INTO ".$pre."link_categories VALUES ('', '".htmlspecialchars(addslashes($_POST['title']))."', '".$_POST['sub_category_id']."')");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Category&func=Web Links&message=10");
					die();
			}else{
				header("location: ".$base_url."/index.php?find=Admin_Panel&func=Web Links&manager=Link&message=8");
				die();
			}
		}
		$Table->Open();
		echo "<form action=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;cid=".$_GET['cid']."\" method=\"post\">
		<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
		  <tr>
			<td width=\"40%\" align=\"right\">"._TITLE.":</td>
			<td width=\"60%\" align=\"left\"><input type=\"text\" name=\"title\" value=\"".$cat['title']."\"></td>
		  </tr>
		  <tr>
			<td align=\"right\">"._SUB_CAT_OF.": </td>
			<td align=\"left\"><select name=\"sub_category_id\">
			<option value=\"0\""; if(0 == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
			$catr = $MySQL->Query("SELECT id, sub_category_id, title FROM ".$pre."link_categories");
			while($cati = mysql_fetch_array($catr)){
				if($cati['sub_category_id'] > 0){
					$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."link_categories WHERE id = '".$cati['sub_category_id']."'");
					$name = "["._SUB_OF." ".$subcat['title']."]-> ".$cati['title'];
				}else{
					$name = $cati['title'];
				}
				if($cati['id'] == $cat['id']){
					continue;
				}else{
					echo "<option value=\"".$cati['id']."\" "; if($cati['id'] == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">".$name."</option>";
				}
			}
		echo "</select></td>
		  </tr>
		  <tr>
			<td align=\"left\">&nbsp;</td>
			<td align=\"left\"><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>
		  </tr>
		</table></form>";
		$Table->Close();
	}
}else{
	if(isset($_GET['manager'])){
		switch($_GET['manager'])
		{
			case "Request":
				$sql = $MySQL->Rows("SELECT approved FROM ".$pre."link WHERE approved = 0");
			break;
			case "Category":
				$sql = $MySQL->Rows("SELECT id FROM ".$pre."link_categories");
			break;
			case "Link":
				$sql = $MySQL->Rows("SELECT approved FROM ".$pre."link WHERE approved = 1");
			break;
		}
		switch($_GET['manager'])
		{
			case "Request":
			case "Category":
			case "Link":
				$Count_Div = $sql / 25;
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
						$second = $ii * 25;
						$first = $second - 25;
						$limit_show = "".$first.", 25"; 
					}
				}
				if($sql == 0){
					$limit_show = 1;
				}
			break;
		}
	}
	switch($_GET['manager'])
	{
		case "Request":
			$rlink_sql = $MySQL->Query("SELECT added_on, title, id, username, cid  FROM ".$pre."link WHERE approved = 0 ORDER BY id DESC LIMIT ".$limit_show);
			$Table->Open("<strong>"._REQUEST_MANAGER."</strong>");
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr><td class=\"table\" width=\"25%\">"._AUTHOR."</td><td width=\"25%\" class=\"table\">"._TITLE."</td><td width=\"25%\" class=\"table\">"._ADDED_ON."</td><td  class=\"table\" width=\"25%\">"._CATEGORY."</td></tr>";
				while($rlink = mysql_fetch_array($rlink_sql)){
					if($rlink['cid'] > 0){
						$cat = $MySQL->Fetch("SELECT title FROM ".$pre."link_categories WHERE id = '".$rlink['cid']."'");
						$cat = $cat['title'];
					}else{
						$cat = _MAIN;
					}
					echo "<tr><td align=\"left\" class=\"table\">".$rlink['username']."</td><td align=\"left\" class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;lid=".$rlink['id']."\">".$rlink['title']."</a></td><td align=\"left\" class=\"table\">".$rlink['added_on']."</td><td align=\"left\" class=\"table\">".$cat."</td></tr>";
				}
				echo "</tr></table>";
			$Table->Close();
		break;
		
		case "Category";	
			$cat_sql = $MySQL->Query("SELECT title, id, sub_category_id FROM ".$pre."link_categories ORDER BY id DESC LIMIT ".$limit_show);
			echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;cid=".$create_code."\">"._CREATE_A_CATEGORY."</a></div>";
			$Table->Open("<strong>"._CATEGORY_MANAGER."</strong>");
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr><td width=\"50%\" class=\"table\">"._TITLE."</td><td  class=\"table\" width=\"50%\">"._SUB_CAT_OF."</td></tr>";
				while($cat = mysql_fetch_array($cat_sql)){
					if($cat['sub_category_id'] > 0){
						$scat = $MySQL->Fetch("SELECT title FROM ".$pre."link_categories WHERE id = '".$cat['sub_category_id']."'");
						$sub_cat = $scat['title'];
					}else{
						$sub_cat = _MAIN;
					}
					echo "<tr><td align=\"left\" class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;cid=".$cat['id']."\">".$cat['title']."</a></td><td align=\"left\" class=\"table\">".$sub_cat."</td></tr>";
				}
				echo "</tr></table>";
			$Table->Close();
		break;
		
		case "Link":
			$link_sql = $MySQL->Query("SELECT title, cid, url, id FROM ".$pre."link WHERE approved = 1 ORDER BY id DESC LIMIT ".$limit_show);
			echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;lid=".$create_code."\">"._ADD_LINK."</a></div>";
			$Table->Open("<strong>"._LINK_MANAGER."</strong>");
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr><td width=\"34%\" class=\"table\">"._TITLE."</td><td  class=\"table\" width=\"33%\">"._CATEGORY."</td><td  class=\"table\" width=\"33%\">"._ACTIONS."</td></tr>";
				while($link = mysql_fetch_array($link_sql)){
					if($link['cid'] > 0){
						$cat = $MySQL->Fetch("SELECT title FROM ".$pre."link_categories WHERE id = '".$link['cid']."'");
						$cat = $cat['title'];
					}else{
						$cat = _MAIN;
					}
					echo "<tr><td align=\"left\" class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;lid=".$link['id']."\">".$link['title']."</a></td><td align=\"left\" class=\"table\">".$cat."</td><td align=\"left\" class=\"table\"><a href=\"".$link['url']."\" target=\"_BLANK\">"._VISIT."</a></td></tr>";
				}
				echo "</tr></table>";
			$Table->Close();
		break;
	}
	
	switch($_GET['manager'])
	{
		case "Request":
		case "Category":
		case "Link":
			if($sql > 0){
				echo "<br /><strong>"._PAGE.":</strong> ";
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
					echo "<option value=\"index.php?find=Admin_Panel&amp;func=Web Links&amp;manager=".$_GET['manager']."&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
				}
				echo "</select></form>";
			}
		break;
	}
}
?>