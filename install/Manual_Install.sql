CREATE TABLE `php_Article_Comments` (
  `Comment_ID` bigint(10) NOT NULL auto_increment,
  `Story_ID` tinyint(10) NOT NULL default '0',
  `Comment_Author` varchar(255) NOT NULL default '',
  `Comment_Date` varchar(255) NOT NULL default '',
  `Comment_Time` int(50) NOT NULL default '0',
  `Comment_Content` longtext NOT NULL,
  PRIMARY KEY  (`Comment_ID`),
  FULLTEXT KEY `Comment` (`Comment_Content`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_Blocks` (
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
) ENGINE=MyISAM AUTO_INCREMENT=9;

INSERT INTO `php_Blocks` VALUES (5, 'Site Statistics', '', 'Left', '1', 'site-stats', 3, 0, 1);
INSERT INTO `php_Blocks` VALUES (2, 'Main Menu', '
<a href=\"index.php\">Home</a><br />\r\n
<a href=\"index.php?find=Members\">Members</a><br />\r\n
<a href=\"index.php?find=Topics\">Topics</a><br />\r\n
<a href=\"index.php?find=Polls\">Polls</a><br />\r\n
<a href=\"index.php?find=Profile\">Members List</a><br />\r\n
<a href=\"index.php?find=Downloads\">Downloads</a><br />\r\n
<a href=\"index.php?find=Web Links\">Web Links</a><br />\r\n
<a href=\"index.php?find=Private Messages\">Private Messages</a><br />\r\n
<a href=\"index.php?find=Statistics\">Site Statistics</a><br />\r\n
<a href=\"index.php?find=Contact Us\">Contact Us</a><br />', 'Left', '0', '', 1, 0, 1);
INSERT INTO `php_Blocks` VALUES (3, 'Members Block', '', 'Right', '1', 'Members_Block', 3, 0, 1);
INSERT INTO `php_Blocks` VALUES (1, 'Admin Panel', '- <a href=\"?find=Admin_Panel\">Admin Panel</a>', 'Left', '1', 'Admin_Block', 0, 2, 1);
INSERT INTO `php_Blocks` VALUES (4, 'Site Survey', '', 'Right', '1', 'Polls', 0, 0, 1);
INSERT INTO `php_Blocks` VALUES (6, 'Private Messages', '', 'Right', '1', 'Private-Messages', 2, 1, 1);
INSERT INTO `php_Blocks` VALUES (7, 'Top 10 Downloads', '', 'Right', '1', 'Top-10-DL', 4, 0, 1);
INSERT INTO `php_Blocks` VALUES (8, 'Top 10 Web Links', '', 'Left', '1', 'Top-10-WL', 2, 0, 1);

CREATE TABLE `php_Messages` (
  `MsgID` bigint(5) NOT NULL auto_increment,
  `Username` varchar(50) NOT NULL default '',
  `From` varchar(50) NOT NULL default '',
  `Subject` varchar(255) NOT NULL default '',
  `Sent` varchar(50) NOT NULL default '',
  `Message` longtext NOT NULL,
  `New` tinyint(5) NOT NULL default '1',
  `Old` tinyint(5) NOT NULL default '0',
  PRIMARY KEY  (`MsgID`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_Modules` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `access` tinyint(10) NOT NULL default '0',
  `status` tinyint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=15;

INSERT INTO `php_Modules` VALUES (1, 'News', 0, 1);
INSERT INTO `php_Modules` VALUES (2, 'Members', 0, 1);
INSERT INTO `php_Modules` VALUES (3, 'Topics', 0, 1);
INSERT INTO `php_Modules` VALUES (4, 'Statistics', 0, 1);
INSERT INTO `php_Modules` VALUES (5, 'Content', 0, 1);
INSERT INTO `php_Modules` VALUES (6, 'Contact Us', 0, 1);
INSERT INTO `php_Modules` VALUES (7, 'Private Messages', 1, 1);
INSERT INTO `php_Modules` VALUES (8, 'Admin_Panel', 2, 1);
INSERT INTO `php_Modules` VALUES (9, 'Polls', 0, 1);
INSERT INTO `php_Modules` VALUES (10, 'Profile', 0, 1);
INSERT INTO `php_Modules` VALUES (11, 'Downloads', 0, 1);
INSERT INTO `php_Modules` VALUES (12, 'Web Links', 0, 1);

CREATE TABLE `php_News_Articles` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_Poll_Comments` (
  `Comment_ID` bigint(5) NOT NULL auto_increment,
  `Poll_ID` tinyint(5) NOT NULL default '0',
  `Comment_Author` varchar(255) NOT NULL default '',
  `Comment_Date` varchar(255) NOT NULL default '',
  `Comment_Time` int(255) NOT NULL default '0',
  `Comment_Content` longtext NOT NULL,
  PRIMARY KEY  (`Comment_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_Polls` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2;

INSERT INTO `php_Polls` VALUES (1, 'How do you like the site?', 'Great', 'Good', 'Ok', 'Bad', 'Horrible', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'active');

CREATE TABLE `php_banned_ip` (
  `ip_id` bigint(10) NOT NULL auto_increment,
  `ip` varchar(25) NOT NULL default '0',
  `reason` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ip_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_banned_users` (
  `user_id` int(15) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  `reason` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM;

CREATE TABLE `php_censor_words` (
  `word_id` bigint(10) NOT NULL auto_increment,
  `bad_word` varchar(50) NOT NULL default '',
  `replacement` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`word_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10;

INSERT INTO `php_censor_words` VALUES (1, 'fuck', '****');
INSERT INTO `php_censor_words` VALUES (2, 'damn', '****');
INSERT INTO `php_censor_words` VALUES (3, 'fuk', '****');
INSERT INTO `php_censor_words` VALUES (4, 'dam', '****');
INSERT INTO `php_censor_words` VALUES (5, 'bastard', '****');
INSERT INTO `php_censor_words` VALUES (6, 'pussy', '****');
INSERT INTO `php_censor_words` VALUES (7, 'asshole', '****');
INSERT INTO `php_censor_words` VALUES (8, 'cum', '****');
INSERT INTO `php_censor_words` VALUES (9, 'fucker', '****');

CREATE TABLE `php_content` (
  `content_id` bigint(5) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` mediumtext NOT NULL,
  `content` longtext NOT NULL,
  `views` int(50) NOT NULL default '0',
  `created_on` varchar(50) NOT NULL default '',
  `headers` tinyint(5) NOT NULL default '1',
  `access` varchar(255) NOT NULL default '0',
  PRIMARY KEY  (`content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_counter` (
  `type` varchar(255) NOT NULL default '',
  `var` varchar(255) NOT NULL default '',
  `count` int(25) NOT NULL default '0'
) ENGINE=MyISAM;

INSERT INTO `php_counter` VALUES ('browser', 'MSIE', 0);
INSERT INTO `php_counter` VALUES ('browser', 'FireFox', 0);
INSERT INTO `php_counter` VALUES ('browser', 'Netscape', 0);
INSERT INTO `php_counter` VALUES ('browser', 'Opera', 0);
INSERT INTO `php_counter` VALUES ('browser', 'Bot', 0);
INSERT INTO `php_counter` VALUES ('browser', 'Other', 0);
INSERT INTO `php_counter` VALUES ('os', 'Windows', 0);
INSERT INTO `php_counter` VALUES ('os', 'Linux', 0);
INSERT INTO `php_counter` VALUES ('os', 'Mac/PPC', 0);
INSERT INTO `php_counter` VALUES ('os', 'Other', 0);
INSERT INTO `php_counter` VALUES ('total', 'hits', 0);
INSERT INTO `php_counter` VALUES ('record', 'hits', 0);

CREATE TABLE `php_download_categories` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `sub_category_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_downloads` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_link` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_link_categories` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `sub_category_id` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_settings` (
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
) ENGINE=MyISAM;

CREATE TABLE `php_smilies` (
  `smilie_id` bigint(10) NOT NULL auto_increment,
  `smilie_code` varchar(50) NOT NULL default '',
  `smilie_img` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`smilie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16;

INSERT INTO `php_smilies` VALUES (15, ':-)', 'smile.gif');
INSERT INTO `php_smilies` VALUES (14, '(pft)', 'dry.gif');
INSERT INTO `php_smilies` VALUES (13, ':-(', 'frown.gif');
INSERT INTO `php_smilies` VALUES (12, '8-)', 'cool.gif');
INSERT INTO `php_smilies` VALUES (11, 'o_o', 'awe.gif');
INSERT INTO `php_smilies` VALUES (10, ';-)', 'wink.gif');
INSERT INTO `php_smilies` VALUES (9, ':-D', 'bigsmile.gif');
INSERT INTO `php_smilies` VALUES (8, ':-P', 'tongue.gif');
INSERT INTO `php_smilies` VALUES (7, ':-|', 'stumped.gif');
INSERT INTO `php_smilies` VALUES (6, '^_^', 'smirk.gif');
INSERT INTO `php_smilies` VALUES (5, ':-o', 'shocked.gif');
INSERT INTO `php_smilies` VALUES (4, '(?)', 'question.gif');
INSERT INTO `php_smilies` VALUES (3, '(idea)', 'lightbulb.gif');
INSERT INTO `php_smilies` VALUES (2, '(!)', 'exclamation.gif');
INSERT INTO `php_smilies` VALUES (1, ':-S', 'confused.gif');

CREATE TABLE `php_stats_today` (
  `year` int(50) NOT NULL default '0',
  `month` int(50) NOT NULL default '0',
  `date` int(50) NOT NULL default '0',
  `hour` int(50) NOT NULL default '0',
  `hits` int(50) NOT NULL default '0'
) ENGINE=MyISAM;

CREATE TABLE `php_topics` (
  `id` bigint(5) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `img` varchar(255) NOT NULL default '',
  `description` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

INSERT INTO `php_topics` ( `id` , `name` , `img` , `description` ) VALUES ('', 'General', 'General.png', 'General Stuff');

CREATE TABLE `php_user_groups` (
  `group_id` bigint(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`group_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_users` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `php_users_online` (
`ip` VARCHAR( 15 ) NOT NULL ,
`reg` INT( 1 ) NOT NULL,
`time_stamp` INT( 15 ) NOT NULL ,
INDEX ( `ip` ) 
) ENGINE=MyISAM;

CREATE TABLE `php_guestbook` (
`id` BIGINT( 10 ) NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`user_id` INT( 10 ) NOT NULL ,
`email` VARCHAR( 50 ) NOT NULL ,
`entry` MEDIUMTEXT NOT NULL ,
`date` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `id` ) 
) ENGINE=MyISAM;

CREATE TABLE `php_hotbox` (
`tag_id` BIGINT( 10 ) NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`ip` VARCHAR( 15 ) NOT NULL ,
`tag` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `tag_id` ) 
) ENGINE=MyISAM;

INSERT INTO `php_News_Articles` ( `Article_ID` , `Article_Title` , `Article_Author` , `Article_Story` , `Article_Time` , `Article_Date` , `Article_Full_Story` , `Article_Views` , `Article_Topic` ) VALUES ('', 'PHPublisher has been successfully installed!', 'Timothy Hensley', 'PHPublisher has been successfully installed! You may now start using your new CMS! :-)', '500', '', '', '0', 'General');
INSERT INTO `php_users` VALUES ('', 'Guest', 0, 'Guest@mysite.com', '', '', '', 0, NULL, NULL, NULL, '', NULL, NULL, 'PHPublisher', NULL, NULL, NULL, 'Guest', 0, 0, 0, 0, 'english', '', '', '', '');  