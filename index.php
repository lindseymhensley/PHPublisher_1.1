<?PHP
 /*******************************************************************
 **
 ** File: index.php
 ** Description: Sites main page
 **
 *******************************************************************/
if(!file_exists("config_info.php")){
	die("<h3>Either you have deleted your config_info.php file or you have yet to install PHPublisher.<br /><br /><a href=\"install/index.php\">Click here to install PHPublisher.</a></h3>");
}else{
	if(file_exists("install/index.php")){
		die("<h3>If you have already installed the correct tables and rows, for PHPublisher, please delete the /install directory.</h3>");
	}
}
define("IN_DB", true);
require("config_info.php");
require("backend.php");

$Welcome_User = "Welcome ".$user->name()."!";
echo $Header;
$user->theme("style.tpl", array("url" => $base_url, "Site_Title" => $Site_Title, "Site_Slogan" => $Site_Slogan, "Welcome" => $Welcome_User, "LeftNav" => $LeftBlocks, "PageContent" => $PageContent, "RightNav" => $RightBlocks, "Footer1" => $Footer1, "Footer2" => $Footer2, "Footer3" => $Footer3, "Footer4" => $Footer4));

?>