<?php

/*******************************************************************
 **
 ** File: pageconten t.php
 ** Description: this is the file used to display all the content
 ** on the site it will check for illegal characters and if certain
 ** modules exist or not, and wither a user can access them.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

ob_start(); 
	if($_GET['access'] == "no"){
		$Table->Open("<strong>Error!</strong>");
			echo "<strong>You are not authorized to view this page.</strong>";
		$Table->Close();
	}else{
		if((strpos($_GET['find'], "/") !== false) || (strpos($_GET['file'], "/") !== false)) {
			header("Location: ".$base_url."/index.php"); 
		} 
		if(empty($_GET['file']) && isset($_GET['find'])){
			$GrabDir = $Current_Directory ."/Modules/".$_GET['find']."/index.php";
		}elseif(isset($_GET['file']) && isset($_GET['find'])){
			$GrabDir = $Current_Directory ."/Modules/".$_GET['find']."/".$_GET['file'].".php";
		} 
		if (empty($_GET['find'])){
			include_once($Current_Directory."/Modules/News/language/".$user->language().".php");
			include($Current_Directory."/Modules/News/index.php");
		} elseif (file_exists($GrabDir)) { 
			if(!isset($_SESSION['Module_Access'][$_GET['find']])){
				$_SESSION['Module_Info'][$_GET['find']] = $MySQL->Fetch("SELECT * FROM ".$pre."Modules WHERE name = '".$_GET['find']."'");
				$_SESSION['Module_Access'][$_GET['find']] = 1;
			}
			ob_start();
				include_once($Current_Directory."/Modules/".$_GET['find']."/language/".$user->language().".php");
				include($GrabDir);
				$include_module = ob_get_contents();
			ob_end_clean();
			if($_SESSION['Module_Info'][$_GET['find']] == TRUE && $_SESSION['Module_Info'][$_GET['find']]['status'] == 1){
				switch ($_SESSION['Module_Info'][$_GET['find']]['access'])
				{
					case 1:
						if($user->id() > 1){
							echo $include_module;
						}else{
							$Table->Open();
								echo "The module you requested can only be viewed by Members.";
							$Table->Close();
						}
					break;
					
					case 2:
						if($user->lvl() >= 2){
							echo $include_module;
						}else{
							$Table->Open();
								echo "The module you requested can only be viewed by Administration.";
							$Table->Close();
						}
					break;
					
					default:
						echo $include_module;
					break;
				}
			}else{
				if($user->lvl() == 99){
					echo $include_module;
				}else{
					$Table->Open();
						echo "The module you requested is currently deactivated.";
					$Table->Close();
				}
			}
		} else { 
			$Directory = $Current_Directory."/Modules/".$_GET['find']."/index.php";
			if(isset($_GET['file']) && !file_exists($Directory) || empty($_GET['file']) && !file_exists($Directory)){
				$Table->Open(); 
					echo "The Module \"".htmlspecialchars(addslashes(trim($_GET['find'])))."\" does not exist!\n\n";
				$Table->Close();
			}elseif(isset($_GET['file']) && file_exists($Directory)){
				$Table->Open();
					echo "The File \"".htmlspecialchars(addslashes(trim($_GET['file'])))."\" in Module \"".htmlspecialchars(addslashes(trim($_GET['find'])))."\" does not exist!\n\n";
				$Table->Close();
			} 
		}
	}
	$PageContent = ob_get_contents(); 
ob_end_clean(); 
?>
