<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if(isset($_GET['delete_id'])){
	$_GET['delete_id'] = htmlentities(trim($_GET['delete_id']));
	if($user->lvl() >= 3){
		if(($entry = $MySQL->Fetch("SELECT * FROM ".$pre."guestbook WHERE id = ".$_GET['delete_id'])) != FALSE){
			if(isset($_GET['confirm'])){
				$MySQL->Query("DELETE FROM ".$pre."guestbook WHERE id = ".$entry['id']." LIMIT 1");
				$_SESSION['gbe'] = NULL;
				$_SESSION['gb_entry'] = NULL;
				$_SESSION['gb_sessioned'] = NULL;
				header("location: ".$base_url."/index.php?find=Guestbook&show_entries=1");
				die();
			}else{
				echo _ARE_YOU_SURE;
				echo "<br /><a href=\"index.php?find=Guestbook&delete_id=".$_GET['delete_id']."&confirm=1\">"._YES."</a> | <a href=\"index.php?find=Guestbook&show_entries=1\">"._NO."</a><br /><br />";
				if($entry['user_id'] > 1){
					$user_post = "<a href=\"".$base_url."/index.php?find=Profile&user_id=".$entry['user_id']."\">".$entry['username']."</a>";
				}else{
					$user_post = "<a href=\"mailto:".$entry['email']."\">".$entry['username']."</a>";
				}
				$entr = $entry['entry'];
				if($Censor_Words == 1){
					$entr = censor($entr);
				}
				if($Emoticon_On == 1){
					$entr = emoticon($entr);
				}
				if($BBcode_On == 1){
					$entr = bbcode($entr);
				}		
				$Table->Open("<strong>".$user_post." "._SIGNED." ".$entry['date']."</strong>");
					echo "<div align=\"left\">".nl2br($entr)."</div>";
				$Table->Close();
				echo "<br />";
			}
		}else{
			header("location: ".$base_url."/index.php?find=Guestbook");
			die();
		}
	}else{
		header("location: ".$base_url."/index.php?find=Guestbook");
		die();
	}
}else{
	if(isset($_GET['show_entries'])){
		if(empty($_SESSION['gbe']['count'])){
			$_SESSION['gbe']['count'] = $MySQL->Rows("SELECT id FROM ".$pre."guestbook");
		}
		if($_SESSION['gbe']['count'] > 0){
			$Count_Div = $_SESSION['gbe']['count'] / 5;
			$Ro_Count = ceil($Count_Div);
			if(!isset($_GET['show_page']) || empty($_GET['show_page'])){
				$_GET['show_page'] = 1;
			}
			if(isset($_SESSION['gb_sessioned'][$_GET['show_page']])){
				if(!isset($_GET['show_page'])){
					$_GET['show_page'] = 1;
				}
				$gb_array = $_SESSION['gb_entry'][$_GET['show_page']];
				while(list($key, $val) = each($gb_array)){
					if($user->lvl() >= 3){ echo "<div style=\"text-align: right;\"><a href=\"index.php?find=Guestbook&delete_id=".$key."\">"._DELETE_ENTRY."</a></div>"; }
					echo $val;
				}
			}else{
				if($_GET['show_page'] > $Ro_Count){
					if($_SESSION['gbe']['count'] <= 0){
						$noentries = 1;
					}elseif($_SESSION['gbe']['count'] > 0){
						$_GET['show_page'] = 1;
					}
				}
				$i = 1;
				for($i = 1; $i <= $Ro_Count; $i++){
					if($_GET['show_page'] == $i){
						$second = $i * 5;
						$first = $second - 5;
						$limit_show = "".$first.", 5"; 
					}
				}
				if(isset($noentries)){
					$limit_show = 1;
				}elseif($_SESSION['gbe']['count'] <= 5){
					$limit_show = $_SESSION['gbe']['count'];
				}
				if(empty($_SESSION['gbe']['SQL'])){
					$_SESSION['gbe']['SQL'] = $MySQL->Query("SELECT * FROM ".$pre."guestbook ORDER BY id DESC LIMIT ".$limit_show);
				} 
				$gb_count = 0;
				while($entry = mysql_fetch_array($_SESSION['gbe']['SQL'])){
					if($entry['user_id'] > 1){
						$user_post = "<a href=\"".$base_url."/index.php?find=Profile&user_id=".$entry['user_id']."\">".$entry['username']."</a>";
					}else{
						$user_post = "<a href=\"mailto:".$entry['email']."\">".$entry['username']."</a>";
					}
					$entr = $entry['entry'];
					if($Censor_Words == 1){
						$entr = censor($entr);
					}
					if($Emoticon_On == 1){
						$entr = emoticon($entr);
					}
					if($BBcode_On == 1){
						$entr = bbcode($entr);
					}
					ob_start();
						$Table->Open("<strong>".$user_post." "._SIGNED." ".$entry['date']."</strong>");
							echo "<div align=\"left\">".nl2br($entr)."</div>";
						$Table->Close();
						echo "<br />";
						if(!$_GET['show_page']){
							$_GET['show_page'] = 1;
						}
						$_SESSION['gb_entry'][$_GET['show_page']][$entry['id']] = ob_get_contents();
					ob_end_clean();
					$gb_count++;
					if(($gb_count == $MySQL->Rows("SELECT * FROM ".$pre."guestbook LIMIT ".$limit_show)) || $gb_count == 5){
						$_SESSION['gb_sessioned'][$_GET['show_page']] = 1;
						header("location: ".$base_url."/index.php?find=Guestbook&show_entries=1&show_page=".$_GET['show_page']);
						die();
					}		
				}
			}
			
			if($_SESSION['gbe']['count'] > 5){
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
					echo "<option value=\"index.php?find=Guestbook&show_entries=1&show_page=".$i."\""; if($_GET['show_page'] == $i){ echo "selected"; } echo ">".$i."</option>";
				}
				echo "</select></form>";
			}
		}else{
			echo _NO_ENTRIES."<br /><br />";
		}
		echo "<br /><a href=\"index.php?find=Guestbook\">"._SIGN_MY_GUESTBOOK."</a>";
	}else{
		$Table->Open("<strong>"._SIGN_MY_GUESTBOOK."</strong>");
		$success = false;
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$_POST['entry'] = htmlentities(trim($_POST['entry']));
			if($user->id() != 1){
				if(empty($_POST['entry'])){
					echo "<br />"._ENTRY_EMPTY;
				}else{
					$MySQL->Query("INSERT INTO ".$pre."guestbook VALUES('', '".$user->name()."', '".$user->id()."', '', '".$_POST['entry']."', '".$date."')");
					$_SESSION['gbe'] = NULL;
					$_SESSION['gb_entry'] = NULL;
					$_SESSION['gb_sessioned'] = NULL;
					echo "<br />"._SUCCESSFUL_ENTRY;
					$success = true;
				}
			}else{
		
				$_POST['username'] = htmlentities(trim($_POST['username']));
				$_POST['email'] = htmlentities(trim($_POST['email']));
				if(empty($_POST['username'])|| !ereg('^[a-zA-Z0-9_-]+$', $_POST['username'])){
					echo "<br />"._USERNAME_EMPTY;
				}elseif(empty($_POST['email'])|| !strpos($_POST['email'], '@')){
					echo "<br />"._EMAIL_EMPTY;
				}elseif(empty($_POST['entry'])){
					echo "<br />"._ENTRY_EMPTY;
				}else{
					if($MySQL->Rows("SELECT user_id FROM ".$pre."users WHERE username = '".$_POST['username']."'") == 0){
						$MySQL->Query("INSERT INTO ".$pre."guestbook VALUES('', '".$_POST['username']."', '', '".$_POST['email']."', '".$_POST['entry']."', '".$date."')");
						echo "<br />"._SUCCESSFUL_ENTRY;
						$_SESSION['gbe'] = NULL;
						$_SESSION['gb_entry'] = NULL;
						$_SESSION['gb_sessioned'] = NULL;
						$success = true;
					}else{
						echo "<br />"._USERNAME_IN_USE;
					}
				}
			}	
		}
		?>
		<p align="center"><a href="<?php echo $base_url ?>/index.php?find=Guestbook&show_entries=1"><?php echo _VIEW_ENRTIES; ?></a></p>
		<?php if($success != true){ ?>
		<form action="index.php?find=Guestbook" method="Post" name="gb">
		<table width="70%"  border="0" cellspacing="0" cellpadding="5" align="center">
		  <tr>
			<td width="20%" align="left"><?php echo _USERNAME ?>:</td>
			<td width="80%" align="left"><?php if($user->id() == 1){ ?><input type="text" name="username" value="<?php echo $_POST['username']; ?>"><? }else{ echo $user->name(); } ?></td>
		  </tr>
		  <tr>
			<td align="left"><?php echo _EMAIL ?>:</td>
			<td align="left"><?php if($user->id() == 1){ ?><input type="text" name="email" value="<?php echo $_POST['email']; ?>"><?php }else{ echo $user->extract('user_email'); }?></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">
			<?php 
			echo "<script language=\"JavaScript\" type=\"text/javascript\">
				function emoticon(text) {
					var txtarea = document.gb.entry;
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
			</script><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td align=center>
									<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=center>";
	
			if($Emoticon_On == 1){
				$find_smilies = $MySQL->Query("SELECT smilie_code, smilie_img FROM ".$pre."smilies ORDER BY smilie_id ASC");
				$i = 1;
	
				while($emo = mysql_fetch_array($find_smilies)){
					if($i == 1){
						echo "<tr>"
						."<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$GLOBALS['base_url']."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
					}elseif($i >=2 && $i < 5){
						echo "<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$GLOBALS['base_url']."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
					}elseif($i == 5){
						echo "<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$GLOBALS['base_url']."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>"
						."</tr>";
						unset($i);
						$i = 0;
					}
					$i++;
				}
			}
			echo "<tr><td align=center colspan=7>";
			if($BBcode_On == 1){
				echo toolbar("gb", "entry");
			}
			echo "</td></tr></table></td></tr></table>";
			?>
			</td>
		  </tr>
		  <tr>
			<td width="20%">&nbsp;</td>
			<td width="80%" align="left"><textarea name="entry" cols="50" rows="10"><?php echo $_POST['entry']; ?></textarea></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td align="left"><input name="Sign" type="submit" value="Sign my guestbook!"></td>
		  </tr>
		</table>
		</form>
		<?php
		}
		$Table->Close();
	}
}
?>