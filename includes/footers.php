<?php
/*******************************************************************
 **
 ** File: footers.php
 ** Description: important footers displayed on every page
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

ob_start();
	if($PoweredBy == 1){
		echo "<br /><a href=\"http://phpublisher.net\"><img src=\"images/PBPHPublisher.png\" alt=\"Powered by PHPublisher\" border=\"0\" /></a> <img src=\"images/PBApache.png\" alt=\"Powered by Apache\" /> <img src=\"images/PBPHP.png\" alt=\"Powered by PHP\" /> <img src=\"images/PBMySQL.png\" alt=\"Powered by MySQL\" /> ";
	}
	$Footer3 = ob_get_contents();
ob_end_clean();

ob_start();
	$mtime = microtime(); 
	$mtime = explode(" ", $mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$end = $mtime; 
	$Loaded = $end - $start; 
	$Load_Time = round($Loaded, 5);
	$loadavg = explode(" ", @exec("cat /proc/loadavg")); 
	ob_start();
		if($loadavg[2] == FALSE){
			echo "";
		}else{
			echo " | Stress Level: ".$loadavg[2];
		}
		$Stress_Content = ob_get_contents();
	ob_end_clean();
	if($Site_Gzip == 1){
		$gzip = "Enabled";
	}else{
		$gzip = "Disabled";
	}
	echo "[ Backend by: <a href=\"http://phpublisher.net\">Timothy Hensley</a> ".$Stress_Content." | Load Time: ".$Load_Time." ]\n\n"
	."<br />[ Queries: ".$MySQL->Count_Queries()." | Affected Rows: ".$MySQL->Count_Rows()." | Gzip: ".$gzip." ]\n\n";
	if(isset($_GET['debug'])){
		echo "<br/>[ Server OS: ".PHP_OS." | MySQL: ".mysql_get_server_info()." | PHP: ".phpversion()." | Zend: ".zend_version()." ]\n\n";
	}
	echo "<br/>[ <a href=\"index.php\">Home</a> | <a href=\"index.php?find=Members&amp;file=Privacy\">Privacy Policy</a> | <a href=\"rss.php\">RSS Feed</a> | <a href=\"index.php?refresh_content=1\">Refresh Content</a> ]\n\n<br /><br />";
	$MySQL->Connection("Close"); 
	$Footer4 = ob_get_contents();
ob_end_clean();
?>