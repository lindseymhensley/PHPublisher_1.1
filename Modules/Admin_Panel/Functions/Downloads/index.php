<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
$Table->Open("<strong>"._SELECT_A_MANAGER."</strong>");
		echo "<a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;manager=Request\">"._REQUEST_MANAGER."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;manager=Category\">"._CATEGORY_MANAGER."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;manager=Download\">"._DOWNLOAD_MANAGER."</a>";
$Table->Close();
echo "<br />";
switch($_GET['message'])
{
	case 1:
		echo _NO_SUCH_DL."<br /><br />";
	break;
	case 2:
		echo _DL_REMOVED."<br /><br />";
	break;
	case 3:
		echo _MISSING_REQUIRED_DL_FIELDS."<br /><br />";
	break;
	case 4:
		echo _DL_CREATED."<br /><br />";
	break;
	case 5:
		echo _DL_UPDATED."<br /><br />";
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
if(isset($_GET['dlid']) && is_numeric($_GET['dlid'])){
	if($_GET['dlid'] != $create_code){
		if(($dl = $MySQL->Fetch("SELECT id, approved, title, cid, username, description, filesize, homepage, url, added_on FROM ".$pre."downloads WHERE id = '".$_GET['dlid']."'")) !== false){
			if(isset($_POST['Submit'])){
				if(!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['url'])){
					if(isset($_POST['request'])){
						if($_POST['approved'] == 0){
							header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Request&func=Downloads&Delete=1&dlid=".$dl['id']);
							die();
						}else{
							$MySQL->Query("UPDATE ".$pre."downloads SET approved=1, title='".htmlspecialchars(addslashes($_POST['title']))."', description='".htmlspecialchars($_POST['description'])."', url='".htmlspecialchars(addslashes($_POST['url']))."', cid='".$_POST['category']."', filesize='".$_POST['filesize']."', homepage='".$_POST['homepage']."'  WHERE id = '".$_GET['dlid']."'");
							header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Download&func=Downloads&message=5");
							die();
						}
					}else{
							$MySQL->Query("UPDATE ".$pre."downloads SET title='".htmlspecialchars(addslashes($_POST['title']))."', description='".htmlspecialchars($_POST['description'])."', url='".htmlspecialchars(addslashes($_POST['url']))."', cid='".$_POST['category']."', filesize='".$_POST['filesize']."', homepage='".$_POST['homepage']."' WHERE id = '".$_GET['dlid']."'");
							header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Download&func=Downloads&message=5");
							die();
					}
				}else{
					header("location: ".$base_url."/index.php?find=Admin_Panel&func=Downloads&manager=Download&message=3");
					die();
				}
			}
			if(isset($_GET['Delete'])){
				if(isset($_GET['Confirm'])){
					$MySQL->Query("DELETE FROM ".$pre."downloads WHERE id = '".$dl['id']."' LIMIT 1");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Download&func=Downloads&message=2&title=".$dl['title']);
					die();
				}else{
					echo _R_U_SURE_DELETE_DL."<br /><br /><a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;Delete=1&amp;dlid=".$dl['id']."&amp;title=".$dl['title']."&amp;Confirm=1\">"._YES."</a> | <a href=\"index.php?file=Admin_Panel&amp;func=Downloads\">"._NO."</a><br /><br />";
				}
			}
		}else{
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=Downloads&message=1");
			die();
		}
	}else{
		if(isset($_POST['Submit'])){
			if(!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['url'])){
					$MySQL->Query("INSERT INTO ".$pre."downloads VALUES ('', '".htmlspecialchars(addslashes($_POST['title']))."', '".htmlspecialchars($_POST['description'])."', '".htmlspecialchars(addslashes($_POST['url']))."', '".$_POST['category']."', '0', '".$added_date."', '1', '".$_POST['filesize']."', '".$_POST['homepage']."', '".$user->name()."')");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Download&func=Downloads&message=4");
					die();
			}else{
				header("location: ".$base_url."/index.php?find=Admin_Panel&func=Downloads&manager=Download&message=3");
				die();
			}
		}
	}
	$Table->Open();
	if(isset($_GET['dlid'])){
		$dl_id = "&amp;dlid=".$_GET['dlid'];
	}
	echo "<form action=\"index.php?find=Admin_Panel&amp;func=Downloads".$dl_id."\" method=\"post\">";
	echo "<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
		  <tr>
			<td width=\"30%\" align=\"right\"><strong>"._AUTHOR.":</strong></td>
			<td width=\"70%\" align=\"left\">"; if($_GET['dlid'] == $create_code){ echo $user->name(); }else{ echo $dl['username']; } echo "</td>
		  </tr>
		  <tr>
			<td align=\"right\"><strong>"._TITLE.":</strong></td>
			<td align=\"left\"><input type=\"text\" name=\"title\" value=\"".$dl['title']."\"></td>
		  </tr>
		  <tr>
			<td align=\"right\" valign=\"top\"><strong>"._DESCRIPTION.":</strong></td>
			<td align=\"left\" ><textarea name=\"description\" cols=\"50\" rows=\"5\">".$dl['description']."</textarea></td>
		  </tr>
		   <tr>
			<td align=\"right\" valign=\"top\"><strong>"._FILESIZE.":</strong></td>
			<td align=\"left\" ><input type=\"text\" name=\"filesize\" value=\"".$dl['filesize']."\"></td>
		  </tr>
		   <tr>
			<td align=\"right\" valign=\"top\"><strong>"._HOMEPAGE.":</strong></td>
			<td align=\"left\" ><input type=\"text\" name=\"homepage\" value=\"".$dl['homepage']."\"></td>
		  </tr>
		  <tr>
			<td align=\"right\" ><strong>"._LINK.":</strong></td>
			<td align=\"left\" ><input type=\"text\" name=\"url\" value=\"".$dl['url']."\">"; if($_GET['dlid'] != $create_code){ echo " <a href=\"".$dl['url']."\" target=\"_blank\">"._DOWNLOAD."</a>"; } echo "</td>
		  </tr>
		  <tr>
			<td align=\"right\" ><strong"._CATEGORY.":</strong></td>
			<td align=\"left\">
			<select name=\"category\"><option value=\"0\""; if(0 == $dl['cid']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
				$cat = $MySQL->Query("SELECT id, sub_category_id, title FROM ".$pre."download_categories");
				while($cati = mysql_fetch_array($cat)){
					if($cati['sub_category_id'] > 0){
						$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."download_categories WHERE id = '".$cati['sub_category_id']."'");
						$name = "["._SUB_OF." ".$subcat['title']."]-> ".$cati['title'];
					}else{
						$name = $cati['title'];
					}
					echo "<option value=\"".$cati['id']."\" "; if($cati['id'] == $dl['cid']){ echo "selected=\"selected\""; } echo ">".$name."</option>";
				}
			echo "</select></td>
		  </tr>
			<tr>
			<td align=\"right\"><strong>"._ADDED_ON.":</strong> </td>
			<td align=\"left\">"; if($_GET['dlid'] == $create_code){ echo $added_date; }else{ echo $dl['added_on']; } echo "</td>
		  </tr>";
		  if(($dl['approved'] == 0) && ($_GET['dlid'] < $create_code)){
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
			<td colspan=\"2\" align=\"center\" ><input type=\"submit\" name=\"Submit\" value=\"Submit\">"; if($dl['approved'] == 1){ echo " [<a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;Delete=1&amp;dlid=".$dl['id']."\">"._DELETE."</a>]"; } echo "</td>
		  </tr>
		</table></form>";
		$Table->Close();
}elseif(isset($_GET['cid']) && is_numeric($_GET['cid'])){
	if($_GET['cid'] != $create_code){
		if(($cat = $MySQL->Fetch("SELECT title, id, sub_category_id FROM ".$pre."download_categories WHERE id = '".$_GET['cid']."'")) !== false){
			if(isset($_POST['Submit'])){
				if(!empty($_POST['title'])){
					$MySQL->Query("UPDATE ".$pre."download_categories SET title='".htmlspecialchars(addslashes($_POST['title']))."', sub_category_id='".$_POST['sub_category_id']."' WHERE id = '".$_GET['cid']."'");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Category&func=Downloads&message=9");
					die();
				}
			}
			if(isset($_GET['Delete'])){
				if(isset($_GET['Confirm'])){
					if(isset($_POST['Move_To'])){
						if($_POST['Move_To'] > 0){
							if($MySQL->Rows("SELECT id FROM ".$pre."download_categories WHERE id = '".$_POST['Move_To']."'") == 0){
								header("location: ".$base_url."/index.php?find=Admin_Panel&func=Downloads&manager=Category&message=6");
								die();
							}
						}
						$MySQL->Query("UPDATE ".$pre."downloads SET cid='".$_POST['Move_To']."' WHERE cid='".$cat['id']."'");
						$MySQL->Query("DELETE FROM ".$pre."download_categories WHERE id = '".$cat['id']."' LIMIT 1");
						header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Category&func=Downloads&message=7&title=".$cat['title']);
						die();
					}else{
						$Table->Open();
							echo _SELECT_CAT_TO_MOVE_TO;
							echo "<form action=\"index.php?find=Admin_Panel&amp;Delete=1&amp;Confirm=1&amp;func=Downloads&amp;cid=".$cat['id']."\" method=\"post\">";
							echo _MOVE_TO.": <select name=\"Move_To\">
							<option value=\"0\""; if(0 == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
							$catr = $MySQL->Query("SELECT sub_category_id, title, id FROM ".$pre."download_categories");
							while($cati = mysql_fetch_array($catr)){
								if($cati['sub_category_id'] > 0){
									$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."download_categories WHERE id = '".$cati['sub_category_id']."'");
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
					echo _R_U_SURE_DELETE_CAT."<br /><br /><a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;Delete=1&amp;cid=".$cat['id']."&amp;title=".$cat['title']."&amp;Confirm=1\">"._YES."</a> | <a href=\"index.php?file=Admin_Panel&amp;func=Downloads\">"._NO."</a><br /><br />";
				}
			}
			$Table->Open();
			echo "<form action=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;cid=".$_GET['cid']."\" method=\"post\">
			<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
			  <tr>
				<td width=\"40%\" align=\"right\">"._TITLE.":</td>
				<td width=\"60%\" align=\"left\"><input type=\"text\" name=\"title\" value=\"".$cat['title']."\"></td>
			  </tr>
			  <tr>
				<td align=\"right\">"._SUB_CAT_OF.": </td>
				<td align=\"left\"><select name=\"sub_category_id\">
				<option value=\"0\""; if(0 == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
				$catr = $MySQL->Query("SELECT id, title, sub_category_id FROM ".$pre."download_categories");
				while($cati = mysql_fetch_array($catr)){
					if($cati['sub_category_id'] > 0){
						$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."download_categories WHERE id = '".$cati['sub_category_id']."'");
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
				<td align=\"left\"><input type=\"submit\" name=\"Submit\" value=\"Submit\">"; if($_GET['cid'] != $create_code){ echo " [<a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;Delete=1&amp;cid=".$cat['id']."\">"._DELETE_CAT."</a>]"; } echo "</td>
			  </tr>
			</table></form>";
			$Table->Close();
		}else{
			header("location: ".$base_url."/index.php?find=Admin_Panel&func=Downloads&manager=Category&message=6");
			die();
		}
	}else{
		if(isset($_POST['Submit'])){
			if(!empty($_POST['title'])){
					$MySQL->Query("INSERT INTO ".$pre."download_categories VALUES ('', '".htmlspecialchars(addslashes($_POST['title']))."', '".$_POST['sub_category_id']."')");
					header("location: ".$base_url."/index.php?find=Admin_Panel&manager=Category&func=Downloads&message=10");
					die();
			}else{
				header("location: ".$base_url."/index.php?find=Admin_Panel&func=Downloads&manager=Download&message=8");
				die();
			}
		}
		$Table->Open();
		echo "<form action=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;cid=".$_GET['cid']."\" method=\"post\">
		<table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">
		  <tr>
			<td width=\"40%\" align=\"right\">"._TITLE.":</td>
			<td width=\"60%\" align=\"left\"><input type=\"text\" name=\"title\" value=\"".$cat['title']."\"></td>
		  </tr>
		  <tr>
			<td align=\"right\">"._SUB_CAT_OF.": </td>
			<td align=\"left\"><select name=\"sub_category_id\">
			<option value=\"0\""; if(0 == $cat['sub_category_id']){ echo "selected=\"selected\""; } echo ">"._MAIN."</option>";
			$catr = $MySQL->Query("SELECT id, title, sub_category_id FROM ".$pre."download_categories");
			while($cati = mysql_fetch_array($catr)){
				if($cati['sub_category_id'] > 0){
					$subcat = $MySQL->Fetch("SELECT title FROM ".$pre."download_categories WHERE id = '".$cati['sub_category_id']."'");
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
				$sql = $MySQL->Rows("SELECT approved FROM ".$pre."downloads WHERE approved = 0");
			break;
			case "Category":
				$sql = $MySQL->Rows("SELECT id FROM ".$pre."download_categories");
			break;
			case "Download":
				$sql = $MySQL->Rows("SELECT approved FROM ".$pre."downloads WHERE approved = 1");
			break;
		}
		switch($_GET['manager'])
		{
			case "Request":
			case "Category":
			case "Download":
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
			$rdl_sql = $MySQL->Query("SELECT cid, username, id, title, filesize FROM ".$pre."downloads WHERE approved = 0 ORDER BY id DESC LIMIT ".$limit_show);
			$Table->Open("<strong>"._REQUEST_MANAGER."</strong>");
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr><td class=\"table\" width=\"25%\">"._AUTHOR."</td><td width=\"25%\" class=\"table\">"._TITLE."</td><td width=\"25%\" class=\"table\">"._FILESIZE."</td><td  class=\"table\" width=\"25%\">"._CATEGORY."</td></tr>";
				while($rdl = mysql_fetch_array($rdl_sql)){
					if($rdl['cid'] > 0){
						$cat = $MySQL->Fetch("SELECT title FROM ".$pre."download_categories WHERE id = '".$rdl['cid']."'");
						$cat = $cat['title'];
					}else{
						$cat = _MAIN;
					}
					echo "<tr><td align=\"left\" class=\"table\">".$rdl['username']."</td><td align=\"left\" class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;dlid=".$rdl['id']."\">".$rdl['title']."</a></td><td align=\"left\" class=\"table\">".$rdl['filesize']."</td><td align=\"left\" class=\"table\">".$cat."</td></tr>";
				}
				echo "</tr></table>";
			$Table->Close();
		break;
		
		case "Category";	
			$cat_sql = $MySQL->Query("SELECT sub_category_id, id, title FROM ".$pre."download_categories ORDER BY id DESC LIMIT ".$limit_show);
			echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;cid=".$create_code."\">"._CREATE_A_CATEGORY."</a></div>";
			$Table->Open("<strong>"._CATEGORY_MANAGER."</strong>");
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr><td width=\"50%\" class=\"table\">"._TITLE."</td><td  class=\"table\" width=\"50%\">"._SUB_CAT_OF."</td></tr>";
				while($cat = mysql_fetch_array($cat_sql)){
					if($cat['sub_category_id'] > 0){
						$scat = $MySQL->Fetch("SELECT title FROM ".$pre."download_categories WHERE id = '".$cat['sub_category_id']."'");
						$sub_cat = $scat['title'];
					}else{
						$sub_cat = _MAIN;
					}
					echo "<tr><td align=\"left\" class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;cid=".$cat['id']."\">".$cat['title']."</a></td><td align=\"left\" class=\"table\">".$sub_cat."</td></tr>";
				}
				echo "</tr></table>";
			$Table->Close();
		break;
		
		case "Download":
			$download_sql = $MySQL->Query("SELECT cid, id, username, title, filesize FROM ".$pre."downloads WHERE approved = 1 ORDER BY id DESC LIMIT ".$limit_show);
			echo "<div align=\"left\"><a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;dlid=".$create_code."\">"._ADD_DL."</a></div>";
			$Table->Open("<strong>"._DOWNLOAD_MANAGER."</strong>");
				echo "<table width=\"100%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
				<tr><td width=\"25%\" class=\"table\">"._TITLE."</td><td  class=\"table\" width=\"25%\">"._AUTHOR."</td><td  class=\"table\" width=\"25%\">"._FILESIZE."</td><td class=\"table\" width=\"25%\">"._CATEGORY."</td></tr>";
				while($dl = mysql_fetch_array($download_sql)){
					if($dl['cid'] > 0){
						$cat = $MySQL->Fetch("SELECT title FROM ".$pre."download_categories WHERE id = '".$dl['cid']."'");
						$cat = $cat['title'];
					}else{
						$cat = _MAIN;
					}
					echo "<tr><td align=\"left\" class=\"table\"><a href=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;dlid=".$dl['id']."\">".$dl['title']."</a></td><td align=\"left\" class=\"table\">".$dl['username']."</td><td align=\"left\" class=\"table\">".$dl['filesize']."</td><td align=\"left\" class=\"table\">".$cat."</td></tr>";
				}
				echo "</tr></table>";
			$Table->Close();
		break;
	}
	
	switch($_GET['manager'])
	{
		case "Request":
		case "Category":
		case "Download":
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
					echo "<option value=\"index.php?find=Admin_Panel&amp;func=Downloads&amp;manager=".$_GET['manager']."&amp;show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
				}
				echo "</select></form>";
			}
		break;
	}
}
?>