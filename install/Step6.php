<?
if(!session_id()){
	session_start();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Install PHPublisher! - Lets do this!</title>
<link href="style-css.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
include(dirname(__FILE__)."/language/".$_SESSION['Install']['Language'].".php");
$Mysql_Connection = @mysql_connect($_SESSION['Install']['dbhost'], $_SESSION['Install']['dbuser'], $_SESSION['Install']['dbpass']); 
if(@mysql_select_db($_SESSION['Install']['dbname'], $Mysql_Connection) !== true){
	echo "
	<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td><div style=\"text-align: center; background-color: rgb(232, 232, 240);\"><span style=\"margin: 50px;\"><strong>"._DATABASE_INFO_ERROR."</strong></span></div></td>
	</tr>
	</table>
	</body>
	</html>
	";
	die();
} 

if(empty($_SESSION['Install']['pre']) || !isset($_SESSION['Install']['pre'])){
	$_SESSION['Install']['pre'] = "php_";
}

if(!file_exists("../config_info.php")){
$config_content = "<?PHP
 /*******************************************************************
 **
 ** File: config_info.php
 ** Description: Database access information
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
 ******************************************************************
 **
 ** $dbhost   :: Host of your machine (usually localhost)
 ** $dbname   :: name of your MySQL Database
 ** $dbuser   :: Username to your MySQL Database
 ** $dbpasswd :: Password to your MySQL Database
 ** $pre      :: Prefix to your database Tables
 **
 ******************************************************************/
 
if(!defined(\"IN_DB\")){
	die(\"Hacking Attempt!\");
}

\$dbhost = \"".$_SESSION['Install']['dbhost']."\";
\$dbname = \"".$_SESSION['Install']['dbname']."\";
\$dbuser = \"".$_SESSION['Install']['dbuser']."\";
\$dbpasswd = \"".$_SESSION['Install']['dbpass']."\";
\$pre = \"".$_SESSION['Install']['pre']."\";

?>";
	@chmod("../", 0777);
	if(!@file_exists("../config_info.php")){
		@touch("../config_info.php");
	}
	$handle = @fopen("../config_info.php", "a");
	if(@fwrite($handle, $config_content) == false){
		$stop_install = 1;
	}else{
		$status['config_create'] = _SUCCESSFULLY_CREATE_CONFIG;
	}
	@fclose($handle);
	
}else{
	$status['config_create'] = _SKIPPED;
}
@chmod("../", 0755);
if($stop_install !== 1){
	$sql = array();
	$errors = 0;
	$sql['Article_Comments'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."Article_Comments` (
	  `Comment_ID` bigint(10) NOT NULL auto_increment,
	  `Story_ID` tinyint(10) NOT NULL default '0',
	  `Comment_Author` varchar(255) NOT NULL default '',
	  `Comment_Date` varchar(255) NOT NULL default '',
	  `Comment_Time` int(50) NOT NULL default '0',
	  `Comment_Content` longtext NOT NULL,
	  PRIMARY KEY  (`Comment_ID`),
	  FULLTEXT KEY `Comment` (`Comment_Content`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Article_Comments']) !== false){
		$status['MySQL']['ARTICLE_CT'] = _SUCCESS;
	}else{
		$status['MySQL']['ARTICLE_CT'] = _FAILED;
		$errors++;
	}
	
	$sql['Blocks'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."Blocks` (
	  `Block_ID` bigint(2) NOT NULL auto_increment,
	  `Block_Title` varchar(50) NOT NULL default '',
	  `Block_Content` longtext NOT NULL,
	  `Block_Side` varchar(5) NOT NULL default '',
	  `Block_File` varchar(255) NOT NULL default '',
	  `Block_File_Name` varchar(255) NOT NULL default '',
	  `Block_lvl` tinyint(5) NOT NULL default '0',
	  `Block_Access` tinyint(5) NOT NULL default '0',
	  `Block_Status` tinyint(5) NOT NULL default '1',
	  PRIMARY KEY  (`Block_ID`)
	) ENGINE=MyISAM AUTO_INCREMENT=9;";
	
	if(mysql_query($sql['Blocks']) !== false){
		$status['MySQL']['BLOCK_T'] = _SUCCESS;
	}else{
		$status['MySQL']['BLOCK_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Insert_Blocks'][1] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (5, 'Site Statistics', '', 'Left', '1', 'site-stats', 3, 0, 1)";
	$sql['Insert_Blocks'][2] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (2, 'Main Menu', '<a href=\"index.php\">Home</a><br />\r\n<a href=\"index.php?find=Members\">Members</a><br />\r\n<a href=\"index.php?find=Topics\">Topics</a><br />\r\n<a href=\"index.php?find=Polls\">Polls</a><br />\r\n<a href=\"index.php?find=Profile\">Members List</a><br />\r\n<a href=\"index.php?find=Downloads\">Downloads</a><br />\r\n<a href=\"index.php?find=Web Links\">Web Links</a><br />\r\n<a href=\"index.php?find=Private Messages\">Private Messages</a><br />\r\n<a href=\"index.php?find=Statistics\">Site Statistics</a><br />\r\n<a href=\"index.php?find=Contact Us\">Contact Us</a><br />', 'Left', '0', '', 1, 0, 1)";
	$sql['Insert_Blocks'][3] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (3, 'Members Block', '', 'Right', '1', 'Members_Block', 3, 0, 1)";
	$sql['Insert_Blocks'][4] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (1, 'Admin Panel', '- <a href=\"?find=Admin_Panel\">Admin Panel</a>', 'Left', '1', 'Admin_Block', 0, 2, 1)";
	$sql['Insert_Blocks'][5] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (4, 'Site Survey', '', 'Right', '1', 'Polls', 0, 0, 1)";
	$sql['Insert_Blocks'][6] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (6, 'Private Messages', '', 'Right', '1', 'Private-Messages', 2, 1, 1)";
	$sql['Insert_Blocks'][7] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (7, 'Top 10 Downloads', '', 'Right', '1', 'Top-10-DL', 4, 0, 1)";
	$sql['Insert_Blocks'][8] = "INSERT INTO `".$_SESSION['Install']['pre']."Blocks` VALUES (8, 'Top 10 Web Links', '', 'Left', '1', 'Top-10-WL', 2, 0, 1)";
	
	for($i = 1; $i <= 8; $i++){
		if(mysql_query($sql['Insert_Blocks'][$i]) !== false){
			$BIE=0;
		}else{
			$BIE++;
		}
	}
	if($BIE == 0){
		$status['MySQL']['BLOCK_R'] = _SUCCESS;
	}else{
		$status['MySQL']['BLOCK_R'] = _FAILED;
		$errors++;
	}
	
	$sql['Messages'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."Messages` (
	  `MsgID` bigint(5) NOT NULL auto_increment,
	  `Username` varchar(50) NOT NULL default '',
	  `From` varchar(50) NOT NULL default '',
	  `Subject` varchar(255) NOT NULL default '',
	  `Sent` varchar(50) NOT NULL default '',
	  `Message` longtext NOT NULL,
	  `New` tinyint(5) NOT NULL default '1',
	  `Old` tinyint(5) NOT NULL default '0',
	  PRIMARY KEY  (`MsgID`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Messages']) !== false){
		$status['MySQL']['MESSAGE_T'] = _SUCCESS;
	}else{
		$status['MySQL']['MESSAGE_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Modules'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."Modules` (
	  `id` int(10) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `access` tinyint(10) NOT NULL default '0',
	  `status` tinyint(10) NOT NULL default '0',
	  PRIMARY KEY  (`id`),
	  UNIQUE KEY `name` (`name`)
	) ENGINE=MyISAM AUTO_INCREMENT=15;";
	
	if(mysql_query($sql['Modules']) !== false){
		$status['MySQL']['MODULE_T'] = _SUCCESS;
	}else{
		$status['MySQL']['MODULE_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Insert_Modules'][1] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (1, 'News', 0, 1)";
	$sql['Insert_Modules'][2] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (2, 'Members', 0, 1)";
	$sql['Insert_Modules'][3] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (3, 'Topics', 0, 1)";
	$sql['Insert_Modules'][4] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (4, 'Statistics', 0, 1)";
	$sql['Insert_Modules'][5] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (5, 'Content', 0, 1)";
	$sql['Insert_Modules'][6] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (6, 'Contact Us', 0, 1)";
	$sql['Insert_Modules'][7] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (7, 'Private Messages', 1, 1)";
	$sql['Insert_Modules'][8] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (8, 'Admin_Panel', 2, 1)";
	$sql['Insert_Modules'][9] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (9, 'Polls', 0, 1)";
	$sql['Insert_Modules'][10] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (10, 'Profile', 0, 1)";
	$sql['Insert_Modules'][11] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (11, 'Downloads', 0, 1)";
	$sql['Insert_Modules'][12] = "INSERT INTO `".$_SESSION['Install']['pre']."Modules` VALUES (12, 'Web Links', 0, 1)";
	
	for($i = 1; $i <= 12; $i++){
		if(mysql_query($sql['Insert_Modules'][$i]) !== false){
			$MIE=0;
		}else{
			$MIE++;
		}
	}
	if($MIE == 0){
		$status['MySQL']['MODULE_R'] = _SUCCESS;
	}else{
		$status['MySQL']['MODULE_R'] = _FAILED;
		$errors++;
	}
	
	$sql['News_Articles'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."News_Articles` (
	  `Article_ID` bigint(10) NOT NULL auto_increment,
	  `Article_Title` varchar(150) NOT NULL default '',
	  `Article_Author` varchar(50) NOT NULL default '',
	  `Article_Story` mediumtext NOT NULL,
	  `Article_Time` int(50) NOT NULL default '0',
	  `Article_Date` varchar(50) NOT NULL default '',
	  `Article_Full_Story` longtext NOT NULL,
	  `Article_Views` int(10) NOT NULL default '0',
	  `Article_Topic` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`Article_ID`),
	  FULLTEXT KEY `Article_Story` (`Article_Story`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['News_Articles']) !== false){
		$status['MySQL']['ARTICLE_T'] = _SUCCESS;
	}else{
		$status['MySQL']['ARTICLE_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Poll_Comments'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."Poll_Comments` (
	  `Comment_ID` bigint(5) NOT NULL auto_increment,
	  `Poll_ID` tinyint(5) NOT NULL default '0',
	  `Comment_Author` varchar(255) NOT NULL default '',
	  `Comment_Date` varchar(255) NOT NULL default '',
	  `Comment_Time` int(255) NOT NULL default '0',
	  `Comment_Content` longtext NOT NULL,
	  PRIMARY KEY  (`Comment_ID`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Poll_Comments']) !== false){
		$status['MySQL']['POLL_CT'] = _SUCCESS;
	}else{
		$status['MySQL']['POLL_CT'] = _FAILED;
		$errors++;
	}
	
	$sql['Polls'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."Polls` (
	  `poll_id` bigint(5) NOT NULL auto_increment,
	  `poll_title` varchar(255) NOT NULL default '',
	  `Choice_1` varchar(50) NOT NULL default '',
	  `Choice_2` varchar(50) NOT NULL default '',
	  `Choice_3` varchar(50) NOT NULL default '',
	  `Choice_4` varchar(50) NOT NULL default '',
	  `Choice_5` varchar(50) NOT NULL default '',
	  `Choice_6` varchar(50) NOT NULL default '',
	  `Choice_7` varchar(50) NOT NULL default '',
	  `Choice_8` varchar(50) NOT NULL default '',
	  `Answer_1` int(5) NOT NULL default '0',
	  `Answer_2` int(5) NOT NULL default '0',
	  `Answer_3` int(5) NOT NULL default '0',
	  `Answer_4` int(5) NOT NULL default '0',
	  `Answer_5` int(5) NOT NULL default '0',
	  `Answer_6` int(5) NOT NULL default '0',
	  `Answer_7` int(5) NOT NULL default '0',
	  `Answer_8` int(5) NOT NULL default '0',
	  `Total_Votes` int(5) NOT NULL default '0',
	  `show_results` tinyint(5) NOT NULL default '0',
	  `check_box` tinyint(5) NOT NULL default '0',
	  `status` varchar(25) NOT NULL default 'active',
	  PRIMARY KEY  (`poll_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=2;";
	
	if(mysql_query($sql['Polls']) !== false){
		$status['MySQL']['POLL_T'] = _SUCCESS;
	}else{
		$status['MySQL']['POLL_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Banned'][1] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."banned_ip` (
	  `ip_id` bigint(10) NOT NULL auto_increment,
	  `ip` varchar(25) NOT NULL default '0',
	  `reason` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`ip_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1";
	
	$sql['Banned'][2] = "CREATE TABLE `".$_SESSION['Install']['pre']."banned_users` (
	  `user_id` int(15) NOT NULL default '0',
	  `username` varchar(255) NOT NULL default '',
	  `reason` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`user_id`)
	) ENGINE=MyISAM;";
	
	if(mysql_query($sql['Banned'][1]) !== false && mysql_query($sql['Banned'][2]) !== false){
		$status['MySQL']['BANNED_T'] = _SUCCESS;
	}else{
		$status['MySQL']['BANNED_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Censors'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."censor_words` (
	  `word_id` bigint(10) NOT NULL auto_increment,
	  `bad_word` varchar(50) NOT NULL default '',
	  `replacement` varchar(50) NOT NULL default '',
	  PRIMARY KEY  (`word_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=10;";
	
	if(mysql_query($sql['Censors']) !== false){
		$status['MySQL']['CENSOR_T'] = _SUCCESS;
	}else{
		$status['MySQL']['CENSOR_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Insert_Censors'][1] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (1, 'fuck', '****')";
	$sql['Insert_Censors'][2] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (2, 'damn', '****')";
	$sql['Insert_Censors'][3] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (3, 'fuk', '****')";
	$sql['Insert_Censors'][4] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (4, 'dam', '****')";
	$sql['Insert_Censors'][5] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (5, 'bastard', '****')";
	$sql['Insert_Censors'][6] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (6, 'pussy', '****')";
	$sql['Insert_Censors'][7] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (7, 'asshole', '****')";
	$sql['Insert_Censors'][8] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (8, 'cum', '****')";
	$sql['Insert_Censors'][9] = "INSERT INTO `".$_SESSION['Install']['pre']."censor_words` VALUES (9, 'fucker', '****')";
	
	for($i = 1; $i <= 9; $i++){
		if(mysql_query($sql['Insert_Censors'][$i]) !== false){
			$CIE=0;
		}else{
			$CIE++;
		}
	}
	if($CIE == 0){
		$status['MySQL']['CENSOR_R'] = _SUCCESS;
	}else{
		$status['MySQL']['CENSOR_R'] = _FAILED;
		$errors++;
	}
	
	$sql['Content'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."content` (
	  `content_id` bigint(5) NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `description` mediumtext NOT NULL,
	  `content` longtext NOT NULL,
	  `views` int(50) NOT NULL default '0',
	  `created_on` varchar(50) NOT NULL default '',
	  `headers` tinyint(5) NOT NULL default '1',
	  `access` varchar(255) NOT NULL default '0',
	  PRIMARY KEY  (`content_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Content']) !== false){
		$status['MySQL']['CONTENT_T'] = _SUCCESS;
	}else{
		$status['MySQL']['CONTENT_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Counter'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."counter` (
	  `type` varchar(255) NOT NULL default '',
	  `var` varchar(255) NOT NULL default '',
	  `count` int(25) NOT NULL default '0'
	) ENGINE=MyISAM";
	
	if(mysql_query($sql['Counter']) !== false){
		$status['MySQL']['COUNTER_T'] = _SUCCESS;
	}else{
		$status['MySQL']['COUNTER_T'] = _FAILED;
		$errors++;
	}
	if(mysql_num_rows(mysql_query("SELECT * FROM ".$_SESSION['Install']['pre']."counter")) == 0){
		$sql['counter'][1] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('browser', 'MSIE', 0)";
		$sql['counter'][2] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('browser', 'FireFox', 0)";
		$sql['counter'][3] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('browser', 'Netscape', 0)";
		$sql['counter'][4] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('browser', 'Opera', 0)";
		$sql['counter'][5] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('browser', 'Bot', 0)";
		$sql['counter'][6] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('browser', 'Other', 0)";
		$sql['counter'][7] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('os', 'Windows', 0)";
		$sql['counter'][8] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('os', 'Linux', 0)";
		$sql['counter'][9] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('os', 'Mac/PPC', 0)";
		$sql['counter'][10] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('os', 'Other', 0)";
		$sql['counter'][11] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('total', 'hits', 0)";
		$sql['counter'][12] = "INSERT INTO `".$_SESSION['Install']['pre']."counter` VALUES ('record', 'hits', 0)";
		
		for($i = 1; $i <= 12; $i++){
			if(mysql_query($sql['counter'][$i]) !== false){
				$CCIE=0;
			}else{
				$CCIE++;
			}
		}
		if($CCIE == 0){
			$status['MySQL']['COUNTER_R'] = _SUCCESS;
		}else{
			$status['MySQL']['COUNTER_R'] = _FAILED;
			$errors++;
		}
	}else{
			$status['MySQL']['COUNTER_R'] = _FAILED;
			$errors++;
	}
	$sql['Downloads'][1] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."download_categories` (
	  `id` int(11) NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `sub_category_id` bigint(10) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	$sql['Downloads'][2] = "CREATE TABLE `".$_SESSION['Install']['pre']."downloads` (
	  `id` int(11) NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `description` mediumtext NOT NULL,
	  `url` varchar(255) NOT NULL default '',
	  `cid` int(11) NOT NULL default '0',
	  `downloads` bigint(10) NOT NULL default '0',
	  `added_on` varchar(255) NOT NULL default '',
	  `approved` int(10) NOT NULL default '0',
	  `filesize` varchar(255) NOT NULL default '',
	  `homepage` varchar(255) NOT NULL default '',
	  `username` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Downloads'][1]) !== false && mysql_query($sql['Downloads'][2]) !== false){
		$status['MySQL']['DOWNLOAD_T'] = _SUCCESS;
	}else{
		$status['MySQL']['DOWNLOAD_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Links'][1] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."link` (
	  `id` int(11) NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `description` varchar(255) NOT NULL default '',
	  `url` varchar(255) NOT NULL default '',
	  `cid` int(11) NOT NULL default '0',
	  `views` bigint(10) NOT NULL default '0',
	  `added_on` varchar(255) NOT NULL default '',
	  `approved` int(5) NOT NULL default '0',
	  `username` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	$sql['Links'][2] = "CREATE TABLE `".$_SESSION['Install']['pre']."link_categories` (
	  `id` bigint(20) NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `sub_category_id` bigint(10) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1";
	
	if(mysql_query($sql['Links'][1]) !== false && mysql_query($sql['Links'][2]) !== false){
		$status['MySQL']['LINK_T'] = _SUCCESS;
	}else{
		$status['MySQL']['LINK_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Settings'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."settings` (
	  `setting_id` tinyint(1) NOT NULL default '1',
	  `site_title` varchar(255) NOT NULL default '',
	  `site_slogan` varchar(150) NOT NULL default '',
	  `site_description` varchar(255) NOT NULL default '',
	  `site_keywords` varchar(255) NOT NULL default '',
	  `admin_email` varchar(50) NOT NULL default '',
	  `site_frontpage` tinyint(5) NOT NULL default '0',
	  `site_frontpage_content` longtext NOT NULL,
	  `site_theme` varchar(255) NOT NULL default 'PHPublisher',
	  `guest_allowed` tinyint(10) NOT NULL default '0',
	  `site_lang` varchar(255) NOT NULL default 'english',
	  `censor_words` tinyint(10) NOT NULL default '1',
	  `footer1` longtext NOT NULL,
	  `footer2` longtext NOT NULL,
	  `poweredby` tinyint(10) NOT NULL default '1',
	  `base_url` varchar(255) NOT NULL default 'http://yoursite.com',
	  `secure_login` tinyint(10) NOT NULL default '0',
	  `emoticon_on` tinyint(10) NOT NULL default '0',
	  `show_new_news` int(50) NOT NULL default '5',
	  `show_old_news` int(50) NOT NULL default '5',
	  `bbcode_on` tinyint(5) NOT NULL default '0',
	  `show_polls` tinyint(5) NOT NULL default '25',
	  `start_date` varchar(255) NOT NULL default '',
	  `site_logging` tinyint(5) NOT NULL default '1',
	  `site_gzip` int(5) NOT NULL default '0',
	  `site_max_sig` int(50) NOT NULL default '500',
	  `site_chgtheme` int(5) NOT NULL default '1',
	  `site_cookie_domain` varchar(255) NOT NULL default '',
	  `site_cookie_path` varchar(255) NOT NULL default '/',
	  `site_suspect_filter` int(5) NOT NULL default '1',
	  UNIQUE KEY `setting_id` (`setting_id`)
	) ENGINE=MyISAM;";
	
	if(mysql_query($sql['Settings']) !== false){
		$status['MySQL']['SETTING_T'] = _SUCCESS;
	}else{
		$status['MySQL']['SETTING_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Insert_Settings'] = "INSERT INTO `".$_SESSION['Install']['pre']."settings` 
	VALUES ('1', 'Welcome to ".$_SESSION['Install']['URL']."', 'My site, I own it! Not you!', 'description', 'keywords', 'webmaster@mysite.com', 1, '<div style=\"text-align: center\">PHPublisher has been successfully installed!</div>', 'PHPublisher',0, '".$_SESSION['Install']['Language']."', 1, '<b>© 2005 by me. All Rights Reserved.</b> ".$_SESSION['Install']['URL']."  is © copyright by me.', 'All logos and trademarks in this site are property of their respective owners. The comments are property of their posters, all the rest © 2005 by me. ', 1, '".$_SESSION['Install']['URL']."', 0, 1, 5, 10, 1, 50, '".date("F j, Y")."', 0, 0, 500, 1, '', '/', '1')";
	
	if(mysql_query($sql['Insert_Settings']) !== false){
		$status['MySQL']['SETTING_R'] = _SUCCESS;
	}else{
		$status['MySQL']['SETTING_R'] = _FAILED;
		$errors++;
	}
	
	$sql['Emoticons'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."smilies` (
	  `smilie_id` bigint(10) NOT NULL auto_increment,
	  `smilie_code` varchar(50) NOT NULL default '',
	  `smilie_img` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`smilie_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=16;";
	
	if(mysql_query($sql['Emoticons']) !== false){
		$status['MySQL']['EMOT_T'] = _SUCCESS;
	}else{
		$status['MySQL']['EMOT_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Emoticons_Insert'][1] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (15, ':-)', 'smile.gif')";
	$sql['Emoticons_Insert'][2] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (14, '(pft)', 'dry.gif')";
	$sql['Emoticons_Insert'][3] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (13, ':-(', 'frown.gif')";
	$sql['Emoticons_Insert'][4] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (12, '8-)', 'cool.gif')";
	$sql['Emoticons_Insert'][5] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (11, 'o_o', 'awe.gif')";
	$sql['Emoticons_Insert'][6] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (10, ';-)', 'wink.gif')";
	$sql['Emoticons_Insert'][7] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (9, ':-D', 'bigsmile.gif')";
	$sql['Emoticons_Insert'][8] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (8, ':-P', 'tongue.gif')";
	$sql['Emoticons_Insert'][9] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (7, ':-|', 'stumped.gif')";
	$sql['Emoticons_Insert'][10] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (6, '^_^', 'smirk.gif')";
	$sql['Emoticons_Insert'][11] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (5, ':-o', 'shocked.gif')";
	$sql['Emoticons_Insert'][12] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (4, '(?)', 'question.gif')";
	$sql['Emoticons_Insert'][13] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (3, '(idea)', 'lightbulb.gif')";
	$sql['Emoticons_Insert'][14] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (2, '(!)', 'exclamation.gif')";
	$sql['Emoticons_Insert'][15] = "INSERT INTO `".$_SESSION['Install']['pre']."smilies` VALUES (1, ':-S', 'confused.gif')";
	
	for($i = 1; $i <= 15; $i++){
		if(mysql_query($sql['Emoticons_Insert'][$i]) !== false){
			$EIE=0;
		}else{
			$EIE++;
		}
	}
	if($EIE == 0){
		$status['MySQL']['EMOT_R'] = _SUCCESS;
	}else{
		$status['MySQL']['EMOT_R'] = _FAILED;
		$errors++;
	}
	
	$sql['Stats'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."stats_today` (
	  `year` int(50) NOT NULL default '0',
	  `month` int(50) NOT NULL default '0',
	  `date` int(50) NOT NULL default '0',
	  `hour` int(50) NOT NULL default '0',
	  `hits` int(50) NOT NULL default '0'
	) ENGINE=MyISAM;";
	
	if(mysql_query($sql['Stats']) !== false){
		$status['MySQL']['STAT_T'] = _SUCCESS;
	}else{
		$status['MySQL']['STAT_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Topics'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."topics` (
	  `id` bigint(5) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `img` varchar(255) NOT NULL default '',
	  `description` mediumtext NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Topics']) !== false){
		$status['MySQL']['TOPIC_T'] = _SUCCESS;
	}else{
		$status['MySQL']['TOPIC_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Insert_Topic'] = "INSERT INTO `".$_SESSION['Install']['pre']."topics` ( `id` , `name` , `img` , `description` ) VALUES ('', 'General', 'General.png', 'General Stuff')";
	mysql_query($sql['Insert_Topic']);
	$sql['Groups'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."user_groups` (
	  `group_id` bigint(10) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `description` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`group_id`),
	  UNIQUE KEY `name` (`name`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Groups']) !== false){
		$status['MySQL']['GROUP_T'] = _SUCCESS;
	}else{
		$status['MySQL']['GROUP_T'] = _FAILED;
		$errors++;
	}
	
	$sql['Users'] = "
	CREATE TABLE `".$_SESSION['Install']['pre']."users` (
	  `user_id` bigint(11) NOT NULL auto_increment,
	  `username` varchar(25) NOT NULL default '',
	  `root_admin` tinyint(5) NOT NULL default '0',
	  `user_email` varchar(255) NOT NULL default '',
	  `user_website` varchar(255) NOT NULL default '',
	  `user_avatar` varchar(255) NOT NULL default '',
	  `user_regdate` varchar(255) NOT NULL default '',
	  `user_group` int(10) NOT NULL default '0',
	  `user_icq` varchar(15) default NULL,
	  `user_occ` varchar(100) default NULL,
	  `user_from` varchar(255) default NULL,
	  `user_interests` mediumtext NOT NULL,
	  `user_sig` mediumtext,
	  `user_viewemail` tinyint(2) default NULL,
	  `user_theme` varchar(255) NOT NULL default 'BlueBaby',
	  `user_aim` varchar(18) default NULL,
	  `user_yim` varchar(25) default NULL,
	  `user_msnm` varchar(25) default NULL,
	  `user_password` varchar(40) NOT NULL default '',
	  `newsletter` int(1) NOT NULL default '0',
	  `user_posts` int(10) NOT NULL default '0',
	  `user_level` int(10) NOT NULL default '1',
	  `user_active` int(50) NOT NULL default '0',
	  `user_lang` varchar(255) NOT NULL default 'english',
	  `last_ip` varchar(15) NOT NULL default '0',
	  `user_security_question` varchar(255) NOT NULL default '',
	  `user_security_answer` varchar(255) NOT NULL default '',
	  `verify_code` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`user_id`),
	  UNIQUE KEY `username` (`username`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
	
	if(mysql_query($sql['Users']) !== false){
		$status['MySQL']['USER_T'] = _SUCCESS;
	}else{
		$status['MySQL']['USER_T'] = _FAILED;
		$errors++;
	}	
	
	$sql['create_users_online'] = "CREATE TABLE `".$_SESSION['Install']['pre']."users_online` (
	`ip` VARCHAR( 15 ) NOT NULL ,
	`reg` INT( 1 ) NOT NULL,
	`time_stamp` INT( 15 ) NOT NULL ,
	INDEX ( `ip` ) 
	)";
	
	if(mysql_query($sql['create_users_online']) == false){
		$errors++;
	}
	
	$sql['create_guestbook'] = "CREATE TABLE `".$_SESSION['Install']['pre']."guestbook` (
	`id` BIGINT( 10 ) NOT NULL AUTO_INCREMENT ,
	`username` VARCHAR( 50 ) NOT NULL ,
	`user_id` INT( 10 ) NOT NULL ,
	`email` VARCHAR( 50 ) NOT NULL ,
	`entry` MEDIUMTEXT NOT NULL ,
	`date` VARCHAR( 25 ) NOT NULL ,
	PRIMARY KEY ( `id` ) 
	)";	
	
	if(mysql_query($sql['create_guestbook']) == false){
		$errors++;
	}
	
	$sql['create_hotbox'] = "CREATE TABLE `".$_SESSION['Install']['pre']."hotbox` (
	`tag_id` BIGINT( 10 ) NOT NULL AUTO_INCREMENT ,
	`username` VARCHAR( 50 ) NOT NULL ,
	`ip` VARCHAR( 15 ) NOT NULL ,
	`tag` VARCHAR( 255 ) NOT NULL ,
	PRIMARY KEY ( `tag_id` ) 
	)";
	
	if(mysql_query($sql['create_hotbox']) == false){
		$errors++;
	}
	
	$sql['Insert_Guest'] = "INSERT INTO `".$_SESSION['Install']['pre']."users` VALUES ('', 'Guest', 0, 'Guest@mysite.com', '', '', '', 0, NULL, NULL, NULL, '', NULL, NULL, 'PHPublisher', NULL, NULL, NULL, 'Guest', 0, 0, 0, 0, '".$_SESSION['Install']['Language']."', '', '', '', '')";
	
	if(mysql_query($sql['Insert_Guest']) !== false){
		$status['MySQL']['USER_R'] = _SUCCESS;
	}else{
		$status['MySQL']['USER_R'] = _FAILED;
		$errors++;
	}
	
	$sql['Insert_Poll'] = "INSERT INTO `".$_SESSION['Install']['pre']."Polls` VALUES (1, 'How do you like the site?', 'Great', 'Good', 'Ok', 'Bad', 'Horrible', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'active')";
	
	if(mysql_query($sql['Insert_Poll']) !== false){
		$status['MySQL']['POLL_R'] = _SUCCESS;
	}else{
		$status['MySQL']['POLL_R'] = _FAILED;
		$errors++;
	}
	
	if(mysql_query($_SESSION['Install']['Account']) !== false){
		$status['MySQL']['ADMIN_R'] = _SUCCESS;
	}else{
		$status['MySQL']['ADMIN_R'] = _FAILED;
		$errors++;
	}
	$sql['Insert_Article'] = "INSERT INTO `".$_SESSION['Install']['pre']."News_Articles` ( `Article_ID` , `Article_Title` , `Article_Author` , `Article_Story` , `Article_Time` , `Article_Date` , `Article_Full_Story` , `Article_Views` , `Article_Topic`) VALUES ('', 'PHPublisher has been successfully installed!' , 'Timothy Hensley', 'PHPublisher has been successfully installed! You may now start using your new CMS! :-)', '".time()."', 'Today', '', '0', 'General')"; 
	mysql_query($sql['Insert_Article']);
}
if($stop_install == 1){
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background-color: rgb(232, 232, 240);">
	<div style="margin:10px;"><strong><?=_CONFIG_FILE_UNWRITABLE ?></strong></div>
	<div style="margin:10px;"><?=_CONFIG_CREATE_INSTRUCTIONS ?></div>
	<div style="margin:10px"><textarea name="config_source" cols="150" rows="25"><?=$config_content ?></textarea></div>
	<br />
	<div style="margin:10px;"><a href="Step5.php"><?=_TRY_AGAIN ?></a></div>
	</td> 	
</tr>
</table></body></html>
<?
die();
}else{
?>
<div style="text-align: center; font-weight: bold; font-size:25px; margin:10px;">PHPublisher is being Installed..</div>
<div style="text-align: center; margin:10px;">| -&gt; First Step -&gt; Language -&gt; MySQL Information -&gt; Admin Account -&gt; Ready? -&gt; <strong>Lets do this!</strong> &lt;- | </div>
<div style="background-color: rgb(232, 232, 240);">&nbsp;</div><br>

  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background-color: rgb(232, 232, 240);">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><div style="margin:10px; text-align: right;"><?=_PROCESS1 ?></div></td>
    <td width="50%"><div style="margin:10px; text-align: left;"><?=$status['config_create'] ?></div></td>
  </tr>  
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_ARTICLE_CT ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['ARTICLE_CT'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_BLOCK_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['BLOCK_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_BLOCK_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['BLOCK_R'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_MESSAGE_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['MESSAGE_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_MODULE_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['MODULE_T'] ?></div></td>
  </tr> 
    <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_MODULE_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['MODULE_R'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_ARTICLE_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['ARTICLE_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_POLL_CT ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['POLL_CT'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_POLL_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['POLL_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_POLL_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['POLL_R'] ?></div></td>
  </tr> 
    <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_BANNED_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['BANNED_T'] ?></div></td>
  </tr>
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_CENSOR_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['CENSOR_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_CENSOR_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['CENSOR_R'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_COUNTER_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['COUNTER_T'] ?></div></td>
  </tr> 
    <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_COUNTER_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['COUNTER_R'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_CONTENT_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['CONTENT_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_DOWNLOAD_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['DOWNLOAD_T'] ?></div></td>
  </tr> 
   <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_LINK_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['LINK_T'] ?></div></td>
  </tr>  
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_SETTING_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['SETTING_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_SETTING_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['SETTING_R'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_EMOT_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['EMOT_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_EMOT_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['EMOT_R'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_STAT_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['STAT_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_TOPIC_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['TOPIC_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_GROUP_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['GROUP_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_USER_T ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['USER_T'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_USER_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['USER_R'] ?></div></td>
  </tr> 
  <tr>
    <td><div style="margin:10px; text-align: right;"><?=_PROCESS_ADMIN_R ?></div></td>
    <td><div style="margin:10px; text-align: left;"><?=$status['MySQL']['ADMIN_R'] ?></div></td>
  </tr> 
</table> 	
</tr>
</table>
<?
}
if($errors > 0){
$drop = "
DROP TABLE `".$_SESSION['Install']['pre']."Article_Comments` ,
`".$_SESSION['Install']['pre']."Blocks` ,
`".$_SESSION['Install']['pre']."Messages` ,
`".$_SESSION['Install']['pre']."Modules` ,
`".$_SESSION['Install']['pre']."News_Articles` ,
`".$_SESSION['Install']['pre']."Poll_Comments` ,
`".$_SESSION['Install']['pre']."Polls` ,
`".$_SESSION['Install']['pre']."banned_ip` ,
`".$_SESSION['Install']['pre']."banned_users` ,
`".$_SESSION['Install']['pre']."censor_words` ,
`".$_SESSION['Install']['pre']."content` ,
`".$_SESSION['Install']['pre']."counter` ,
`".$_SESSION['Install']['pre']."download_categories` ,
`".$_SESSION['Install']['pre']."downloads` ,
`".$_SESSION['Install']['pre']."link` ,
`".$_SESSION['Install']['pre']."link_categories` ,
`".$_SESSION['Install']['pre']."settings` ,
`".$_SESSION['Install']['pre']."smilies` ,
`".$_SESSION['Install']['pre']."stats_today` ,
`".$_SESSION['Install']['pre']."topics` ,
`".$_SESSION['Install']['pre']."user_groups` ,
`".$_SESSION['Install']['pre']."users` ";
@mysql_query($drop);
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background-color: rgb(232, 232, 240);">
	<div style="margin:10px;"><strong><?=_THERE_ARE_ERRORS ?></strong></div>
	<div style="margin:10px"><textarea name="sql_source" cols="150" rows="25"><? include(dirname(__FILE__)."/Manual_Install.sql"); ?></textarea></div>
	<br /><?=_AFTER_MANUAL_INSTALL ?>
	<? $sql_settings = "INSERT INTO `php_settings` VALUES (1, 'Welcome to PHPublisher', 'My site, I own it! Not you!', 'description', 'keywords', 'webmaster@mysite.com', 1, '<div style=\"text-align: center\">PHPublisher has been successfully installed!</div>', 'PHPublisher', 0, 'english', 1, '<b>© 2005 by me. All Rights Reserved.</b> ".$_SESSION['Install']['URL']."  is © copyright by me.', 'All logos and trademarks in this site are property of their respective owners. The comments are property of their posters, all the rest © 2005 by me. ', 1, '".$_SESSION['Install']['URL']."', 0, 1, 5, 10, 1, 50, '".date("F j, Y")."', 0, 0, 500, 1, '', '/', 1);"; ?>
	<br>
<div style="margin:10px;"><textarea name="sql_setting_source" cols="150" rows="25"><?=$sql_settings ?>


<?=$_SESSION['Install']['Account'] ?>;</textarea></div>
	</td> 	
</tr>
</table><br>
<br>
<br>

<?
}else{
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background-color: rgb(232, 232, 240);">
	<div style="margin:10px;"><strong><?=_SUCCESSFUL_INSTALL ?></strong><a href="<?=$_SESSION['Install']['URL'] ?>"><br /><br /><?=_CLICK_HERE_NOW ?></a></div>
	</td> 	
</tr>
</table>
<?
}
?><br>
<br>
<br>

</body>
</html>
