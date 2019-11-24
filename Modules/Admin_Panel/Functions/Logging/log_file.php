<?php
/*******************************************************************
 **
 ** Admin File: Logging/log_file.php
 ** Description: View the site log file here
 **                                                  
 *******************************************************************/ 
define("IN_DB", true);
require("../../../../config_info.php");
require("../../../../backend.php");
require("../../language/".$user->language().".php");

if($user->lvl() != 99){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}
echo $Header."\n<body>";
if(isset($_GET['y']) && isset($_GET['m']) && isset($_GET['d'])){
	echo "<a name=\"start\" /></a><h1>".$Site_Title." "._SITE_LOG_FOR." ".$_GET['m']." ".$_GET['d'].", ".$_GET['y']."</h1>";
	$Table->Open();
	echo "<div align=\"left\"><a href=\"".$base_url."/index.php?find=Admin_Panel&amp;func=Logging\">"._BACK_TO_SITE."</a></div>";
	echo "<div align=\"left\"><a href=\"".$base_url."/Modules/Admin_Panel/Functions/Logging/log_file.php?y=".$_GET['y']."&amp;m=".$_GET['m']."&amp;d=".$_GET['d']."#end\">"._END_OF_LOG."</a></div>";
	$Table->Close();
	echo "<br />";
	$Table->Open(_XML_FORMAT);
	echo "<div align=\"left\"><strong>&lt;?xml version=\"</strong>1.0<strong>\" ?&gt;</strong><br /><strong>&lt;Log date=\"</strong>".$_GET['m']." ".$_GET['d'].", ".$_GET['y']."<strong>\"&gt;</strong><br /><br />";
	include($Current_Directory."/log/".$_GET['y']."/".$_GET['m']."/".$_GET['d'].".php");
	echo "<strong>&lt;/Log&gt;</strong></div>";
	$Table->Close();
	echo "<br />";
	$Table->Open();
	echo "<div align=\"left\"><a name=\"end\" /></a><a href=\"".$base_url."/Modules/Admin_Panel/Functions/Logging/log_file.php?y=".$_GET['y']."&amp;m=".$_GET['m']."&amp;d=".$_GET['d']."#start\">"._START_OF_LOG."</a>";
	$Dl = date("d");
	$Ml = date("F");
	$Yl = date("Y");
	if(($Dl == $_GET['d']) && ($Ml === $_GET['m']) && ($Yl == $_GET['y'])){
		echo "";
	}else{
		echo "<br /><br /><a href=\"".$base_url."/index.php?find=Admin_Panel&amp;func=Logging&amp;y=".$_GET['y']."&amp;m=".$_GET['m']."&amp;d=".$_GET['d']."&amp;delete=1\">"._DELETE_LOG."</a>";
	}
	echo "</div>";
	$Table->Close();
}else{
	header("location: ".$base_url."/index.php?find=Admin_Panel&func=Logging");
}
echo "</body></html>";
?>