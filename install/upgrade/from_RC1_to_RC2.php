<?php
/*
** Upgrade from RC1 to RC2
*/
define("IN_DB", true);
require("../../config_info.php");
$Mysql_Connection = mysql_connect($dbhost, $dbuser, $dbpasswd) or die(mysql_error()); 
mysql_select_db($dbname, $Mysql_Connection) or die(mysql_error()); 
if(mysql_query("ALTER TABLE `".$pre."settings` ADD `site_suspect_filter` INT( 5 ) DEFAULT '1' NOT NULL") !== FALSE){
	die("PHPublisher has been successfully upgraded!");
}else{
	die("Failed Upgrade!");
}
?>