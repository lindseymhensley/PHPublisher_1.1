<?php
/*******************************************************************
 **
 ** Admin File: Logging/index.php
 ** Description: Select a log file from whatever date you wish to 
 ** view
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() != 99){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
if(isset($_GET['delete'])){
	if(isset($_GET['confirm'])){
		$log_file = $Current_Directory."/log/".$_GET['y']."/".$_GET['m']."/".$_GET['d'].".php";
		if(file_exists($log_file)){
			$Dl = date("d");
			$Ml = date("F");
			$Yl = date("Y");
			if(($Dl == $_GET['d']) && ($Ml === $_GET['m']) && ($Yl == $_GET['y'])){
				header("location: index.php?find=Admin_Panel&func=Logging&message=3");
			}else{
				unlink($log_file);
				header("location: index.php?find=Admin_Panel&func=Logging&message=2&y=".$_GET['y']."&m=".$_GET['m']."&d=".$_GET['d']);
			}
		}else{
			header("location: index.php?find=Admin_Panel&func=Logging&message=1");
		}
	}else{
		$Table->Open(_SURE_YOU_WISH_TO_DELETE_LOG);
			echo _LOG_NOTICE;
			echo "<br /><br /><a href=\"index.php?find=Admin_Panel&amp;func=Logging&amp;y=".$_GET['y']."&amp;m=".$_GET['m']."&amp;d=".$_GET['d']."&amp;delete=1&amp;confirm=1\">"._YES."</a> | <a href=\"index.php?find=Admin_Panel&amp;func=Logging\">"._NO."</a>";
		$Table->Close();
	}
}else{
	switch ($_GET['message'])
	{
		case 1:
			echo _NO_SUCH_LOG."<br /><br />";
		break;
		
		case 2:
			echo _LOG_DELETED."<br /><br />";
		break;
		
		case 3:
			echo _CAN_NOT_DELETE."<br /><br />";
		break;
	}
	echo "<form action=\"index.php?find=Admin_Panel&amp;func=Logging\" method=\"post\">";
	if(isset($_POST['Submit'])){
		$further = $_POST['log']."/";	
		if(isset($_POST['logged_year'])){
			echo "<input name=\"logged_year\" type=\"hidden\" value=\"".$_POST['logged_year']."\">";
			$further = $_POST['logged_year']."/".$_POST['log']."/";	
			if(isset($_POST['logged_month'])){
				echo "<input name=\"logged_month\" type=\"hidden\" value=\"".$_POST['logged_month']."\">";
				$further = $_POST['logged_year']."/".$_POST['logged_month']."/";
				if(isset($_POST['log'])){
					header("location: ".$base_url."/Modules/Admin_Panel/Functions/Logging/log_file.php?y=".$_POST['logged_year']."&m=".$_POST['logged_month']."&d=".$_POST['log']);
				}
			}else{
				$Table->Open(_CHOOSE_DATE);
				echo "<input name=\"logged_month\" type=\"hidden\" value=\"".$_POST['log']."\">";
			}
		}else{
			$Table->Open(_CHOOSE_MONTH);
			echo "<input name=\"logged_year\" type=\"hidden\" value=\"".$_POST['log']."\">";
		}
	}else{
		$Table->Open(_CHOOSE_YEAR);
	}
	echo "<select name=\"log\">";
	
	if ($handle = opendir($Current_Directory."/log/".$further)){ 
		while (false !== ($log = readdir($handle))) { 
			if($log === "." || $log === ".." || $log === ".htaccess" || $log === "index.htm" || $log === "security_log.php"){
				echo "";
			}else{
				if(isset($_POST['logged_year'])){
					$log = explode(".", $log);
					echo "<option value=\"".$log[0]."\">".$log[0]."</option>"; 
				}else{
					echo "<option value=\"".$log."\">".$log."</option>";
				}
			}
		} 
		closedir($handle); 
	} 
	echo "</select><br /><input name=\"Submit\" type=\"submit\" value=\"Submit\"></form>";
	$Table->Close();
}
?>