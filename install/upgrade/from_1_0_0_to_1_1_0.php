<?php
/*
** Upgrade from 1.0.0 to 1.1.0
*/
define("IN_DB", true);
require("../../config_info.php");
$Mysql_Connection = mysql_connect($dbhost, $dbuser, $dbpasswd) or die(mysql_error()); 
mysql_select_db($dbname, $Mysql_Connection) or die(mysql_error()); 

$sql = array();

$sql[1] = "CREATE TABLE `".$pre."users_online` (
`ip` VARCHAR( 15 ) NOT NULL ,
`reg` INT( 1 ) NOT NULL,
`time_stamp` INT( 15 ) NOT NULL ,
INDEX ( `ip` ) 
)";

$sql[2] = "CREATE TABLE `".$pre."guestbook` (
`id` BIGINT( 10 ) NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`user_id` INT( 10 ) NOT NULL ,
`email` VARCHAR( 50 ) NOT NULL ,
`entry` MEDIUMTEXT NOT NULL ,
`date` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `id` ) 
)";

$sql[3] = "CREATE TABLE `".$pre."hotbox` (
`tag_id` BIGINT( 10 ) NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`ip` VARCHAR( 15 ) NOT NULL ,
`tag` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `tag_id` ) 
)";

$errors = 0;
foreach($sql as $value){
	if(mysql_query($value) == FALSE){
		$errors++;
	}else{
		continue;
	}
}
if($errors == 0){
	die("PHPublisher has been successfully upgraded!");
}else{
	die("Failed Upgrade!");
}

?>