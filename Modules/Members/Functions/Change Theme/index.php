<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->id() <= 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}
$Table->Open();
if(($Site_Chg_Theme == 1) || $user->lvl() >= 3){
	if(isset($_GET['succ'])){
	$_POST['theme'] = htmlspecialchars(addslashes(trim($_POST['theme'])));
	echo _THEME_CHG_SUCCESS;
	}else{
		if($_POST['Submit']){
			if(ereg('^[a-zA-Z0-9_-]+$', $_POST['theme'])) {
				$MySQL->Query("UPDATE ".$pre."users SET user_theme = '".$_POST['theme']."' WHERE user_id = '".$user->id()."'");
				$_SESSION['user_theme'] = $_POST['theme'];
				$settings = 1;
				require($Current_Directory."/includes/refresh_content.php");
				header("Location: ".$base_url."/index.php?find=Members&func=Change Theme&succ=1&theme=".$_POST['theme']);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Members&func=Change Theme");
				die();
			}
		}else{
			echo "<br>"._CHOOSE_YOUR_THEME;
			echo "<form action=\"index.php?find=Members&amp;file=index&amp;func=Change Theme\" method=\"post\">"
			. "<select name=\"theme\">";
			if ($handle = opendir($Current_Directory.'/Templates/')) { 
				while (false !== ($theme = readdir($handle))) { 
					if($theme === "." || $theme === ".." || $theme === ".htaccess" || $theme === "index.htm"){
						echo "";
					}else{
						echo "<option value=\"$theme\">$theme</option>"; 
					}
				} 
				closedir($handle); 
			} 
			echo "</select><input name=\"Submit\" type=\"submit\" value=\"Change Theme\"></form>";
			echo _YOUR_CHANGES;
		}
	}
}else{
	echo _FEATURE_DISABLED;
}
$Table->Close();
?>
