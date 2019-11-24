<?PHP 
/*******************************************************************
 **
 ** File: backend.php
 ** Description: Sites core functions, classes, variables, data
 ** info, specifications, etc are stored and created here.
 **
 *******************************************************************
 **
 ** PHPublisher: A Dynamic Content Publishing System 
 ** ________________________________________________ 
 **                                                 
 ** Copyright (c) 2005 by Timothy Hensley                         
 ** http://phpublisher.net                                    
 **                                                          
 ** This program is free software; you can redistribute it    
 ** and/or modify it under the terms of the GNU General Public
 ** License as published by the Free Software Foundation;     
 ** either version 2 of the License, or (at your option) any  
 ** later version.                                             
 **                                                          
 ** This program is distributed in the hope that it will be   
 ** useful, but WITHOUT ANY WARRANTY; without even the implied
 ** warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR   
 ** PURPOSE.  See the GNU General Public License for more      
 ** details.                                                  
 **
 *******************************************************************
 **
 ** Lets get started!
 **                                                
 *******************************************************************/

if(!session_id()){
	session_start();
} 

ini_set("arg_separator.output", "&amp;"); // This keeps PHPublisher XHTML Valid! DO NOT REMOVE!

define("IN_DB", true);

$mtime = microtime(); 
$mtime = explode(" ", $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$start = $mtime; 

$Current_Directory = dirname(__FILE__);
$date = date("F j, Y, g:i:s a"); 

/*******************************************************************
 **
 ** Class: MySQL
 ** Description: Class that will be used throughout the entire 
 ** program which will allow for easier management of data transfer 
 ** information.
 **                                                  
 *******************************************************************/ 

class MySQL 
{ 

	var $counter = 0;
	var $AR = 0;
	var $AR_Total = 0;
	
	function SQL_Error() 
	{ 
		echo "<br>There appears to be an error, below the error information is provided.<br><br>\n"; 
		die("<textarea rows=\"10\" cols=\"100\">".mysql_error()."</textarea>"); 
	}

	function MySQL($dbhost, $dbuser, $dbpasswd, $dbname) 
	{
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpasswd = $dbpasswd;
		$this->dbname = $dbname;
	}
	
	function Connection($status)
	{
		if($status === "Open"){
			$this->Mysql_Connection = mysql_connect($this->dbhost, $this->dbuser, $this->dbpasswd) or $this->SQL_Error(); 
			$this->Mysql_Select_DB = mysql_select_db($this->dbname, $this->Mysql_Connection) or $this->SQL_Error(); 
			return $this->Mysql_Select_DB;
		}elseif($status === "Close"){
			$this->close = mysql_close();
			return $this->close;
		}
	}
	
	function Query($query) 
	{ 
		$delete = preg_replace('/DELETE/siU', 1, $query);
		$update = preg_replace('/UPDATE/siU', 1, $query);
		$insert = preg_replace('/INSERT/siU', 1, $query);
	
		if($delete == 1 || $update == 1 || $insert == 1) {
			$this->AR = mysql_affected_rows();
			$this->AR_Total += $this->AR;
		} 
		$this->Parse_Query = mysql_query($query) or $this->SQL_Error(); 
		$this->counter++; 
			
		return $this->Parse_Query; 
	} 

	function Fetch($query) 
	{ 
		$this->Parse_Result = mysql_query($query) or $this->SQL_Error(); 
		$this->Parse_Array = mysql_fetch_array($this->Parse_Result); 
		$this->counter++; 
		return $this->Parse_Array; 
	} 

	function Rows($query) 
	{ 
		$this->Parse_Result = mysql_query($query) or $this->SQL_Error(); 
		$this->Parse_Rows = mysql_num_rows($this->Parse_Result); 
		$this->counter++; 
		return $this->Parse_Rows; 
	} 

	function Results($query, $fieldname) 
	{ 
		$this->Parse_Results = mysql_result($query, 0, "$fieldname") or $this->SQL_Error(); 
		$this->counter++; 
		return $this->Parse_Results; 
	} 

	function Count_Queries() 
	{ 
		return $this->counter; 
	}

	function Count_Rows()
	{
		return $this->AR_Total;
	}
}
$MySQL = new MySQL($dbhost, $dbuser, $dbpasswd, $dbname); 
$MySQL->Connection("Open");

/*******************************************************************
 **
 ** Basic site settings
 **                                                  
 *******************************************************************/

$version = "1.1.0"; 

if(empty($_SESSION['Settings']) || isset($_GET['refresh_content'])){
	$_SESSION['Settings'] = $MySQL->Fetch("SELECT 
	site_title, site_slogan, site_description, site_keywords, admin_email, site_theme,
	site_frontpage, site_frontpage_content, guest_allowed, site_lang, censor_words, footer1, 
	footer2, poweredby, secure_login, base_url, emoticon_on, show_new_news, show_old_news, 
	bbcode_on, show_polls, start_date, site_logging, site_gzip, site_max_sig, site_chgtheme,
	site_cookie_domain, site_cookie_path, site_suspect_filter FROM ".$pre."settings");
}
$Site_Title = $_SESSION['Settings']['site_title'];
$Site_Slogan = $_SESSION['Settings']['site_slogan'];
$Site_Description = $_SESSION['Settings']['site_description'];
$Site_Keywords = $_SESSION['Settings']['site_keywords'];
$Admin_Email = $_SESSION['Settings']['admin_email'];
$Site_Theme = $_SESSION['Settings']['site_theme'];
$Site_Frontpage = $_SESSION['Settings']['site_frontpage'];
$Site_Frontpage_Content = $_SESSION['Settings']['site_frontpage_content'];
$Guest_Allowed = $_SESSION['Settings']['guest_allowed'];
$Site_Lang = $_SESSION['Settings']['site_lang'];
$Censor_Words = $_SESSION['Settings']['censor_words'];
$Footer1 = $_SESSION['Settings']['footer1'];
$Footer2 = $_SESSION['Settings']['footer2'];
$PoweredBy = $_SESSION['Settings']['poweredby'];
$Security_Login = $_SESSION['Settings']['secure_login'];
$base_url = $_SESSION['Settings']['base_url'];
$Emoticon_On = $_SESSION['Settings']['emoticon_on'];
$Show_New_News = $_SESSION['Settings']['show_new_news'];
$Show_Old_News = $_SESSION['Settings']['show_old_news'];
$BBcode_On = $_SESSION['Settings']['bbcode_on'];
$Show_Polls = $_SESSION['Settings']['show_polls'];
$Site_Start_Date = $_SESSION['Settings']['start_date'];
$Site_Logging = $_SESSION['Settings']['site_logging'];
$Site_Gzip = $_SESSION['Settings']['site_gzip'];
$Site_Max_Sig_Length = $_SESSION['Settings']['site_max_sig'];
$Site_Chg_Theme = $_SESSION['Settings']['site_chgtheme'];
$Cookie_Domain = $_SESSION['Settings']['site_cookie_domain'];
$Cookie_Path = $_SESSION['Settings']['site_cookie_path'];
$Enable_Suspect_Filter = $_SESSION['Settings']['site_suspect_filter'];

global $Site_Title, $Site_Slogan, $Site_Description, $Site_Keywords, $Admin_Email, $Site_Theme,
$Guest_Allowed, $Site_Lang, $Censor_Words, $Security_Login, $base_url, $Emoticon_On, $Show_New_News,
$Show_Old_News, $BBcode_On, $Show_Polls, $Site_Start_Date, $Site_Logging, $Site_Gzip, $Site_Max_Sig_Length,
$Site_Chg_Theme, $Cookie_Domain, $Cookie_Path, $Enable_Suspect_Filter;

if($Site_Gzip == 1){
	@ob_start ('ob_gzhandler'); 
}

/*******************************************************************
 **
 ** Class: user
 ** Description: Class which will supply all current user data
 ** information without the hassle of multiple sql queries.
 **                                                  
 *******************************************************************/

class user 
{      
	var $username = NULL; 
	var $password = NULL;
	var $userid = NULL;
	var $lvl = NULL;
	
	function user($MySQL, $pre, $Current_Directory, $Site_Theme, $Site_Lang, $base_url)
	{
		$this->MySQL = $MySQL;
		$this->pre = $pre;
		$this->Current_Directory = $Current_Directory;
		$this->Site_Theme = $Site_Theme;
		$this->Site_Lang = $Site_Lang;
		$this->base_url = $base_url;
	}

	function verify() 
	{ 
		if(empty($_COOKIE['Cusername']) || empty($_COOKIE['Cpassword'])){ 
			$this->username = "Guest"; 
			$this->password = "Guest"; 
			$this->userid = 1;
			$this->lvl = 0;
		}else{
			$this->username = $_COOKIE['Cusername'];
			$this->password = $_COOKIE['Cpassword'];
		}
		
		if(empty($_SESSION['Verify_User']) || isset($_GET['refresh_content'])){
			$_SESSION['Verify_SQL'] = "SELECT user_id, username, root_admin, user_email, user_website, user_avatar, user_regdate, user_group, user_icq, user_occ, user_from, user_interests, user_sig, user_viewemail, user_theme, user_aim, user_yim, user_msnm, user_password, newsletter, user_posts, user_level, user_active, user_lang, last_ip, user_security_question, user_security_answer, verify_code FROM ".$this->pre."users WHERE username = '".$this->username."' AND user_password = '".$this->password."'"; 
			$_SESSION['Verify_User'] = $this->MySQL->Rows($_SESSION['Verify_SQL']);
		}
		
		if($_SESSION['Verify_User'] == 0){ 
			setcookie('Cusername','');
			setcookie('Cpassword', '');
			header("location: ".$base_url);
		} 
		if(empty($_SESSION['User']) || isset($_GET['refresh_content'])){
			$_SESSION['User'] = $this->MySQL->Fetch($_SESSION['Verify_SQL']); 
		}
	} 

	function extract($column) //grabs all user info
	{ 
		return $_SESSION['User'][$column]; 
	} 
	
	function name() //username
	{	
		return $this->extract('username');
	}
	
	function id() //user_id
	{
		return $this->extract('user_id');
	}
	
	function lvl() //user access level
	{
		return $this->extract('user_level');
	}

	function group() //user group
	{          
		return $this->extract('user_group');
	}     	
	
	/*******************************************************************
	**
	** Function: theme()
	** Description: The user function that will decide and output the 
	** overall site layout and design
	**                                                  
	*******************************************************************/ 

	function theme($tpl_file = "", $array = "") 
	{ 
		$theme = $this->Current_Directory."/Templates/".$this->extract('user_theme')."/".$tpl_file; 		
		
		if(!empty($tpl_file) && !empty($array)){	
			if(file_exists($theme)){
				$inc_file = file($theme);
				$inc_file = implode("", $inc_file);
				$inc_file = addslashes($inc_file);
				$inc_file = "\$r_file = \"".$inc_file."\";";
				eval($inc_file);
				echo $r_file; 
			}else{
				$this->MySQL->Query("UPDATE ".$this->pre."users SET user_theme='".$this->Site_Theme."' WHERE user_id = '".$this->id()."'");
				$theme = $this->Current_Directory."/Templates/".$this->Site_Theme."/".$tpl_file;
				if(file_exists($theme)){
					$inc_file = file($theme);
					$inc_file = implode("", $inc_file);
					$inc_file = addslashes($inc_file);
					$inc_file = "\$r_file = \"".$inc_file."\";";
					eval($inc_file);
					echo $r_file; 
				}else{
					die("There has been a Fata Error in the user theme system! Contact the administrator at once, and tell him the site default theme does not exist!");
				}
			}
		}else{
			return $this->extract('user_theme');
		}		
	} 
	
	function userip() // users ip returned
	{	
		if (isset($_SERVER)) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				return $_SERVER['REMOTE_ADDR'];
			}
		} else {
			if (isset($GLOBALS['HTTP_SERVER_VARS']['HTTP_X_FORWARDER_FOR'])) {
				return $GLOBALS['HTTP_SERVER_VARS']['HTTP_X_FORWARDED_FOR'];
			} else {
				return $GLOBALS['HTTP_SERVER_VARS']['REMOTE_ADDR'];
			}
		}
	}

	function updateip() // update the users ip in the database with the one returned
	{ 	
		if(empty($_SESSION['grab_user_ip'])){
			$_SESSION['grab_user_ip'] = 1;
			return $this->MySQL->Query("UPDATE ". $this->pre ."users SET last_ip = '". $this->userip() ."' WHERE user_id = '".$this->id()."' "); 
		}
	} 

	function language() //selects the language the user is using
	{ 
		$lang = $this->extract('user_lang'); 
		$lang_check = $this->Current_Directory."/Modules/".$_GET['find']."/Languages/".$lang.".php"; 

		if(file_exists($lang_check)){ 
			$this->user_lang = $lang; 
		}else{
			$this->user_lang = $this->Site_Lang;
		}
		return $this->user_lang;          
	} 
	
	function logout() //logs out the user and sends them home
	{     
		$this->MySQL->Query("UPDATE ".$this->pre."users SET user_active = ".time()." WHERE user_id = '".$this->id()."' "); 
		setcookie('Cusername', '', time()-1, $GLOBALS['Cookie_Path'], $GLOBALS['Cookie_Domain']); 
		setcookie('Cpassword', '', time()-1, $GLOBALS['Cookie_Path'], $GLOBALS['Cookie_Domain']); 
		$this->MySQL->Query("UPDATE ".$this->pre."users_online SET time_stamp=".time().", reg = 0 WHERE ip = '".$_SERVER['REMOTE_ADDR']."'");
		session_destroy();
		session_unset();
		header("Location: ".$this->base_url."/index.php");
		die();
	} 
} 

$user = new user($MySQL, $pre, $Current_Directory, $Site_Theme, $Site_Lang, $base_url); 

$user->verify(); // verify that the user is logged in, and if they are make sure the cookie information they have is correct

/*******************************************************************
 **
 ** Action: Update the users ip when they visit the site
 **                                                  
 *******************************************************************/ 
$user->updateip();
/*******************************************************************
 **
 ** Class: Theme
 ** Description: Grabs the content open and close tables
 **                                                  
 *******************************************************************/ 

class Table
{
	function Table($user){
		$this->user = $user;
	}

	function Open($Table_Header = "&nbsp;"){
		$this->user->theme("OpenTable.tpl", array("url" => $GLOBALS['base_url'], "Table_Header" => $Table_Header));
	}
	
	function Close($Table_Footer = "&nbsp;"){
		$this->user->theme("CloseTable.tpl", array("url" => $GLOBALS['base_url'], "Table_Footer" => $Table_Footer));
	}
}

$Table = new Table($user);

/*******************************************************************
 **
 ** Refresh content feature
 **                                                  
 *******************************************************************/

if(isset($_GET['refresh_content'])){
	require($Current_Directory."/includes/refresh_content.php");
	die();
}

/*******************************************************************
 **
 ** Function: GrabBlocks
 ** Description: Function which will sort through the database
 ** to find all the neccesary blocks to display to the user
 ** currently browsing.
 **                                                  
 *******************************************************************/

function GrabBlocks($side)
{	
	global $MySQL, $pre, $Current_Directory, $user, $function;
	if(!isset($_SESSION['Block'][$side]['Sessioned'])){
		$block_sql = "SELECT Block_ID, Block_Title, Block_Content, Block_File, Block_File_Name, Block_Access FROM ".$pre."Blocks WHERE (Block_Side = '".$side."') AND (Block_Status = 1) ORDER BY Block_lvl ASC";
		$block_count = $MySQL->Rows($block_sql);
		$block = $MySQL->Query($block_sql);
		$block_number = 1;
		
		while($blocks = mysql_fetch_array($block)){
			ob_start();		
				$_SESSION['Block'][$block_number][$side]['Title'] = $blocks['Block_Title'];						
				ob_start();
					if($blocks['Block_File'] == 1){
						include($Current_Directory."/Blocks/".$blocks['Block_File_Name'].".php");
					}else{
						echo eval("?>".$blocks['Block_Content']."<?");
					}
					$_SESSION['Block'][$block_number][$side]['Content'] = ob_get_contents();
				ob_end_clean();	
				$_SESSION['Block'][$block_number][$side]['Access'] = $blocks['Block_Access'];
				$user->theme("block.tpl", array("url" => $GLOBALS['base_url'], "Block_Title" => $_SESSION['Block'][$block_number][$side]['Title'], "Block_Content" => $_SESSION['Block'][$block_number][$side]['Content']));
				$_SESSION['Block'][$block_number][$side]['Display'] = ob_get_contents();
			ob_end_clean();
			if($block_number == $block_count){
				$_SESSION['Block'][$side]['Sessioned'] = $block_number;
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				die();
			}
			$block_number++;
		}
	}
	
	for($tt = 1; $tt <= $_SESSION['Block'][$side]['Sessioned']; $tt++){			
		if($_SESSION['Block'][$tt][$side]['Access'] == 0){
			echo $_SESSION['Block'][$tt][$side]['Display'];
		} 		
		if($_SESSION['Block'][$tt][$side]['Access'] == 1 && $user->lvl() >= 1){
			echo $_SESSION['Block'][$tt][$side]['Display'];
		}		
		if($_SESSION['Block'][$tt][$side]['Access'] >= 2 && $user->lvl() >= 2){
			echo $_SESSION['Block'][$tt][$side]['Display'];
		}
	}
} 

ob_start();
	GrabBlocks("Left");
	$LeftBlocks = ob_get_contents();
ob_end_clean();

ob_start();
	GrabBlocks("Right");
	$RightBlocks = ob_get_contents();
ob_end_clean();

/*******************************************************************
 **
 ** Lets make sure no 'undesirable' users return ;-).
 **                                                  
 *******************************************************************/
if((empty($_SESSION['Banned']['IP']) || empty($_SESSION['Banned']['IP_SQL'])) || isset($_GET['refresh_content'])){
	$_SESSION['Banned']['IP_SQL'] = "SELECT ip_id, ip, reason FROM ".$pre."banned_ip WHERE ip = '".$user->userip()."'";
	$_SESSION['Banned']['IP'] = $MySQL->Rows($_SESSION['Banned']['IP_SQL']) ;
}
if($_SESSION['Banned']['IP'] >= 1){ 
	if(empty($_SESSION['Banned']['IP_Fetch']) || isset($_GET['refresh_content'])){
		$_SESSION['Banned']['IP_Fetch'] = $MySQL->Fetch($_SESSION['Banned']['IP_SQL']); 
	}
	echo "<center><b>Your computer has been permanently banned from this website.</b><br><br><b>Why? ".$_SESSION['Banned']['IP_Fetch']['reason']."</b></center><br>\n"; 
	die(); 
}

if((empty($_SESSION['Banned']['User']) || empty($_SESSION['Banned']['User_SQL'])) || isset($_GET['refresh_content'])){
	$_SESSION['Banned']['User_SQL'] = "SELECT user_id, username, reason FROM ".$pre."banned_users WHERE username = '".$user->name()."'";
	$_SESSION['Banned']['User'] = $MySQL->Rows($_SESSION['Banned']['User_SQL']) ;
}
if($_SESSION['Banned']['User'] >= 1){ 
	if(empty($_SESSION['Banned']['User_Fetch']) || isset($_GET['refresh_content'])){
		$_SESSION['Banned']['User_Fetch'] = $MySQL->Fetch($_SESSION['Banned']['User_SQL']); 
	}
	echo "<center><b>Your account has been permanently banned from this website.</b><br><br><b>Why? ".$_SESSION['Banned']['User_Fetch']['reason']."</b></center><br>\n"; 
	die(); 
}

/*******************************************************************
 **
 ** Update visitors time stamp
 **                                                  
 *******************************************************************/

if($MySQL->Rows("SELECT ip FROM ".$pre."users_online WHERE ip = '".$_SERVER['REMOTE_ADDR']."'") == 0){
	$MySQL->Query("INSERT INTO ".$pre."users_online (`ip`, `time_stamp`) VALUES ('".$_SERVER['REMOTE_ADDR']."', ".time().")");
}else{
	if($user->id() !== 1){
		$MySQL->Query("UPDATE ".$pre."users_online SET time_stamp=".time().", reg = 1 WHERE ip = '".$_SERVER['REMOTE_ADDR']."'");
	}else{
		$MySQL->Query("UPDATE ".$pre."users_online SET time_stamp=".time().", reg = 0 WHERE ip = '".$_SERVER['REMOTE_ADDR']."'");
	}
}

/*******************************************************************
 **
 ** Required Files (What makes it work).
 **                                                  
 *******************************************************************/
if($only_spec == FALSE){
	require($Current_Directory."/includes/suspect_filter.php");
	require($Current_Directory."/includes/functions.php");
	require($Current_Directory."/includes/censors.php");
	require($Current_Directory."/includes/emoticons.php");
	require($Current_Directory."/includes/bbcode.php");
	require($Current_Directory."/includes/comments.php");
	require($Current_Directory."/includes/pagecontent.php");
	if($Site_Logging == 1){
		require($Current_Directory."/includes/log.php");
	}
	require($Current_Directory."/includes/counter.php");
	require($Current_Directory."/includes/headers.php");
	require($Current_Directory."/includes/footers.php");
}else{
	require($Current_Directory."/includes/suspect_filter.php");
	require($Current_Directory."/includes/censors.php");
	require($Current_Directory."/includes/emoticons.php");
	require($Current_Directory."/includes/bbcode.php");
}

?>