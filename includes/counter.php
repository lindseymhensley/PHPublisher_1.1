<?php
/*******************************************************************
 **
 ** File: counter.php
 ** Description: A file directly included into the backend.
 ** It records all traffic, users browsers, and operating systems.
 **
 *******************************************************************/

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

/*******************************************************************
 **
 ** Grab users browser information
 **
 *******************************************************************/

if (
(ereg("Nav", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Gold", $_SERVER["HTTP_USER_AGENT"])) || (ereg("X11", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Mozilla", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Netscape", $_SERVER["HTTP_USER_AGENT"])) AND (!ereg("MSIE", $_SERVER["HTTP_USER_AGENT"]))AND (!ereg("Opera", $_SERVER["HTTP_USER_AGENT"])) AND (!ereg("Firefox", $_SERVER["HTTP_USER_AGENT"]))){
	$browser = "Netscape";
}elseif(ereg("Firefox", $_SERVER["HTTP_USER_AGENT"])) {
	$browser = "FireFox";
}elseif(ereg("Opera", $_SERVER["HTTP_USER_AGENT"])){
	$browser = "Opera";
}elseif(ereg("MSIE", $_SERVER["HTTP_USER_AGENT"])){
	$browser = "MSIE";
}elseif((eregi("bot", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Google", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Slurp", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Scooter", $_SERVER["HTTP_USER_AGENT"])) || (eregi("Spider", $_SERVER["HTTP_USER_AGENT"])) || (eregi("Infoseek", $_SERVER["HTTP_USER_AGENT"]))){ 
	$browser = "Bot";
}else{ 
	$browser = "Other";
}
/*******************************************************************
 **
 ** Grab users operating system information
 **
 *******************************************************************/

if(ereg("Win", $_SERVER["HTTP_USER_AGENT"])){
	$os = "Windows";
}elseif((ereg("Mac", $_SERVER["HTTP_USER_AGENT"])) || (ereg("PPC", $_SERVER["HTTP_USER_AGENT"]))){
	$os = "Mac";
}elseif(ereg("Linux", $_SERVER["HTTP_USER_AGENT"])){
	$os = "Linux";
}else{
	$os = "Other";
}

$dot = date("d-m-Y-H");
$now = explode ("-",$dot);
$Hour = $now[3];
$Year = $now[2];
$Month = $now[1];
$Day = $now[0];

if(!isset($_SESSION['Checked_Stats'])){
	$find_todays_stats = $MySQL->Rows("SELECT year FROM ".$pre."stats_today WHERE (year = '".$Year."') AND (month = '".$Month."') AND (date = '".$Day."')");
	if($find_todays_stats <= 0){	
		for ($k = 0; $k <= 23; $k++) {
			$out_date = $Day - 2;
			$MySQL->Query("DELETE FROM ".$pre."stats_today WHERE date <= '".$out_date."'");
			$MySQL->Query("INSERT INTO ".$pre."stats_today VALUES ('".$Year."', '".$Month."', '".$Day."','".$k."','0')");
		}
	}
	$_SESSION['Checked_Stats'] = 1;
}

/*******************************************************************
 **
 ** Update Database
 **
 *******************************************************************/

$MySQL->Query("UPDATE ".$pre."stats_today SET hits=hits+1 WHERE (hour = '".$Hour."') AND (year = '".$Year."') AND (date = '".$Day."')");
$MySQL->Query("UPDATE ".$pre."counter SET count=count+1 WHERE (type='total' AND var='hits') OR (var='".$browser."' AND type='browser') OR (var='".$os."' AND type='os')");
?>