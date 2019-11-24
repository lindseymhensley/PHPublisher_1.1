<?php
/*******************************************************************
 **
 ** Admin File: Modules/index.php
 ** Description: Here you can install/uninstall/ activate/deactivate
 ** change access levels, and other stuff with Modules.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() != 99){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}

switch($_GET['action'])
{
	case "Install":		
		if(isset($_GET['name'])){
			if($_GET['name'] === "Admin_Panel"){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
				die();
			}
			$Module_Name = htmlspecialchars(addslashes($_GET['name']));
			if($MySQL->Rows("SELECT name FROM ".$pre."Modules WHERE name = '".$Module_Name."'") == 0){
				$MySQL->Query("INSERT INTO `".$pre."Modules` VALUES ('', '".$Module_Name."', '0', '0')"); 
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=2&name=".$Module_Name);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=1&name=".$Module_Name);
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
			die();
		}
	break;
	
	case "UnInstall":		
		if(isset($_GET['name'])){
			if($_GET['name'] === "Admin_Panel"){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
				die();
			}
			$Module_Name = htmlspecialchars(addslashes($_GET['name']));
			if($MySQL->Rows("SELECT name FROM ".$pre."Modules WHERE name = '".$Module_Name."'") >= 1){
				$MySQL->Query("DELETE FROM ".$pre."Modules WHERE name = '".$Module_Name."' LIMIT 1"); 
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=4&name=".$Module_Name);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=3&name=".$Module_Name);
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
			die();
		}
	break;
	
	case "Activate":
		if(isset($_GET['name'])){
			if($_GET['name'] === "Admin_Panel"){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
				die();
			}
			$Module_Name = htmlspecialchars(addslashes($_GET['name']));
			if($MySQL->Rows("SELECT name FROM ".$pre."Modules WHERE name = '".$Module_Name."'") >= 1){
				$MySQL->Query("UPDATE ".$pre."Modules SET status=1 WHERE name = '".$Module_Name."'");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=5&name=".$Module_Name);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=3&name=".$Module_Name);
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
			die();
		}
	break;
	
	case "DeActivate":
		if(isset($_GET['name'])){
			if($_GET['name'] === "Admin_Panel"){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
				die();
			}
			$Module_Name = htmlspecialchars(addslashes($_GET['name']));
			if($MySQL->Rows("SELECT name FROM ".$pre."Modules WHERE name = '".$Module_Name."'") >= 1){
				$MySQL->Query("UPDATE ".$pre."Modules SET status=0 WHERE name = '".$Module_Name."'");
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=6&name=".$Module_Name);
				die();
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=3&name=".$Module_Name);
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
			die();
		}
	break;
	
	case "ChgAccess":
		if(isset($_GET['name'])){
			if($_GET['name'] === "Admin_Panel"){
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
				die();
			}
			$Module_Name = htmlspecialchars(addslashes($_GET['name']));
			if($MySQL->Rows("SELECT name FROM ".$pre."Modules WHERE name = '".$Module_Name."'") >= 1){
				if(isset($_GET['lvl']) && is_numeric($_GET['lvl'])){
					if($_GET['lvl'] > 2 || $_GET['lvl'] < 0){
						$_GET['lvl'] = 2;
					}
					$MySQL->Query("UPDATE ".$pre."Modules SET access=".$_GET['lvl']." WHERE name = '".$Module_Name."'");
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=7&name=".$Module_Name);
					die();
				}else{
					header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
					die();
				}
			}else{
				header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules&message=3&name=".$Module_Name);
				die();
			}
		}else{
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Modules");
			die();
		}
	break;
}

switch($_GET['message'])
{
	case 1:
		echo _MOD_ALREADY_INSTALLED."<br/><br/>";
	break;
	
	case 2:
		echo _MOD_SUCCESSFULLY_INSTALLED."<br/><br/>";
	break;
	
	case 3:
		echo _MOD_NOT_INSTALLED."<br/><br/>";
	break;
	
	case 4:
		echo _MOD_SUCCESSFULLY_UNINSTALLED."<br/><br/>";
	break;
	
	case 5:
		echo _MOD_SUCCESSFULLY_ACTIVATED."<br/><br/>";
	break;
	
	case 6:
		echo _MOD_SUCCESSFULLY_DEACTIVATED."<br/><br/>";
	break;
	
	case 7:
		echo _MOD_ACCESS_CHG."<br/><br/>";
	break;
}

$Table->Open();
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"1\" cellspacing=\"3\">
	  <tr>
		<td width=\"20%\" align=left class=\"table\">"._NAME."</td>
		<td width=\"30%\" align=left class=\"table\">"._STATUS."</td>
		<td width=\"30%\" align=left class=\"table\">"._ACCESS."</td>
		<td width=\"20%\" align=left class=\"table\">"._ACTIONS."</td>
	  </tr>";
if ($handle = opendir($Current_Directory.'/Modules/')) { 
	while (false !== ($module = readdir($handle))) { 
		if($module === "." || $module === ".." || $module === "Admin_Panel" ||$module === ".htaccess" || $module === "index.htm"){
			echo "";
		}else{
			$Modular_Info = $MySQL->Fetch("SELECT name, access, status FROM ".$pre."Modules WHERE name = '".$module."'");
			if($Modular_Info == TRUE){
				$Name = $Modular_Info['name'];
				switch($Modular_Info['access'])
				{
					
					case 1:
						$Access = "[<a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=ChgAccess&amp;name=".$Name."&amp;lvl=0\">"._ALL_ABB."</a> - <strong>"._MEMBERS_ABB."</strong> - <a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=ChgAccess&amp;name=".$Name."&amp;lvl=2\">"._ADMINS_ABB."</a>]";
					break;
					
					case 2:
						$Access = "[<a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=ChgAccess&amp;name=".$Name."&amp;lvl=0\">"._ALL_ABB."</a> - <a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=ChgAccess&amp;name=".$Name."&amp;lvl=1\">"._MEMBERS_ABB."</a> - <strong>"._ADMINS_ABB."</strong>]";
					break;
					
					default:
						$Access = "[<strong>"._ALL_ABB."</strong> - <a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=ChgAccess&amp;name=".$Name."&amp;lvl=1\">"._MEMBERS_ABB."</a> - <a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=ChgAccess&amp;name=".$Name."&amp;lvl=2\">"._ADMINS_ABB."</a>]";
					break;
				}

				switch($Modular_Info['status'])
				{
					case 1:
						$Status = "[<strong>Activated</strong> - <a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=DeActivate&amp;name=".$Name."\">Deactivate</a>]";
					break;
					
					default:
						$Status = "[<a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=Activate&amp;name=".$Name."\">Activate</a> - <strong>Deactivated</strong>]";
					break;
				}
				$Action = "<a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=UnInstall&amp;name=".$Name."\">Uninstall</a>";
			}else{
				$Name = $module;
				$Status = "[<strong>Not Installed</strong>]";
				$Access = "["._ALL_ABB." - "._MEMBERS_ABB." - <strong>"._ADMINS_ABB."</strong>]";
				$Action = "<a href=\"index.php?find=Admin_Panel&amp;func=Modules&amp;action=Install&amp;name=".$Name."\">Install</a>";
			}
			echo "<tr>
					<td align=left class=\"table\">".$Name."</td>
					<td align=left class=\"table\">".$Status."</td>
					<td align=left class=\"table\">".$Access."</td>
					<td align=left class=\"table\">".$Action."</td>
				  </tr>";
		}
	} 
	closedir($handle); 
} 
	echo "</table>";
$Table->Close();
?>