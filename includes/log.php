<?php
/*******************************************************************
 **
 ** File: log.php
 ** Description: Records every visitor click, and records detailed 
 ** information such as the day & hour they visited, their IP Addr,
 ** their username (if logged in), their agent, and actions
 **                                                  
 **
 *******************************************************************
 **
 **
 ** Below are a  list of variables used in createing the directories
 ** and the actual log file. 
 **                                                  
 *******************************************************************/ 

$Day_Logged = date("d");
$Month_Logged = date("F");
$Year_Logged = date("Y");
$filename = $Current_Directory."/log/".$Year_Logged."/".$Month_Logged."/".$Day_Logged.".php"; 
$year_Dir = $Current_Directory."/log/".$Year_Logged; 
$month_Dir = $Current_Directory."/log/".$Year_Logged."/".$Month_Logged;

/*******************************************************************
 **
 ** Below the script is going to check if the log file exists, if it
 ** doesnt the script will then search for each directory that it 
 ** should be in if at any time it can not find any of them it will
 ** will check to see if the /log/ directory is correctly CHMODED.
 ** If the results come back false it will attempt to chmod it as 
 ** 0777. If things work out from there it will then attempt to 
 ** create the proper directories until it reaches the point where it
 ** can finally create the log file itself and then begin logging.
 **                                                  
 *******************************************************************/ 

if(!file_exists($filename)){
	if(@opendir($year_Dir)){
		if(@opendir($month_Dir)){
			$file = 
			"<?php
			if(!defined(\"IN_DB\")){
				die(\"Hacking Attempt!\");
			}
			?>
			";
			touch($filename);				
			chmod($filename, 0777);
			$error_msg = "<strong>The visitor log file must be given writting permissions! Please content the webmaster at ".$Admin_Email." about this issue ASAP!</strong>";
			if (!$handle = @fopen($filename, "a")) {
				die($error_msg);
			}else{
				if(is_writable($filename)){
					if (fwrite($handle, $file) === FALSE) {
						die($error_msg);
					}
					fclose($handle);
				}else{
					die($error_msg);
				}
			}
			if(empty($_GET['no_session'])){
				header("location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				die();
			}
		}else{
			if(@mkdir($month_Dir, 0777) === FALSE){
				die("<strong>You must CHMOD the /log/ directory to 777!</strong>");
			}
			if(empty($_GET['no_session'])){
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				die();
			}
		}
	}else{
		if(@mkdir($year_Dir, 0777) === FALSE){
			if(@chmod($Current_Directory."/log", 0777) === FALSE){
				die("<strong>You must CHMOD the /log/ directory to 777!</strong>");
			}
			if(empty($_GET['no_session'])){
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				die();
			}
		}
		if(empty($_GET['no_session'])){
			header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			die();
		}
	}
}
if($user->id() != 1){
	$user_name = "<a href=\"".$base_url."/index.php?find=Profile&user_id=".$user->id()."\">".$user->name()."</a>";
}else{
	$user_name = "Guest";
}
$file_content = "
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td><strong>&lt;Visit&gt;</strong></td>
  </tr>
  <tr>
    <td><strong>&lt;Date&gt;</strong>". $date ." <strong>&lt;/Date&gt;</strong></td>
  </tr>
  <tr>
    <td><strong>&lt;Session id=\"</strong>".session_id()."\"<strong> /&gt;</strong></td>
  </tr>
  <tr>
    <td><strong>&lt;Visitor id=\"</strong>".$user_name."<strong>\" address=\"</strong>".$_SERVER['REMOTE_ADDR']."<strong>\" /&gt;</strong></td>
  </tr>
  <tr>
    <td><strong>&lt;Agents&gt;</strong> ".$_SERVER['HTTP_USER_AGENT']." <strong>&lt;/Agents&gt;</strong></td>
  </tr>
  <tr>
    <td><strong>&lt;Action&gt;</strong> http://".$_SERVER['HTTP_HOST'].str_replace("&", "&amp;amp;", $_SERVER['REQUEST_URI'])."<strong>&lt;/Action&gt;</strong></td>
  </tr>
  <tr>
    <td><strong>&lt;/Visit&gt;</strong></td>
  </tr>
</table><br />";
/*******************************************************************
 **
 ** If the script can not open the log file for writting it spits
 ** an error out that requests the administrators attention
 **                                                  
 *******************************************************************/ 
$error_msg = "<strong>The /log/ directory must be given writting permissions! Please content the webmaster at ".$Admin_Email." about this issue ASAP!</strong>";
if (!$handle = @fopen($filename, "a")) {
	die($error_msg);
}else{
	if(is_writable($filename)){
		if (fwrite($handle, $file_content) === FALSE) {
			die($error_msg);
		}
		fclose($handle);
	}else{
		die($error_msg);
	}
}
?>