<?php
/*******************************************************************
 **
 ** File: headers.php
 ** Description: important headers displayed on every page
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

ob_start(); 
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">"
	."<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n\n"
	."<head>\n" 
	."<title>".$Site_Title." - ".$Site_Slogan."</title>\n" 
	."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n"
	."<link href=\"".$base_url."/Templates/".$user->theme()."/style-css.php?base_url=".$base_url."\" rel=\"stylesheet\" type=\"text/css\" />\n"
	."<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".$base_url."/rss.php\" />\n"
	."<meta name=\"Description\" content=\"".$Site_Description."\" />\n" 
	."<meta name=\"Keywords\" content=\"".$Site_Keywords."\" />\n" 
	."<meta name=\"Admin_E-Mail\" content=\"".$Admin_Email."\" />\n" 
	."<meta name=\"Program\" content=\"PHPublisher - A Dynamic Content Management System\" />\n" 
	."<meta name=\"Copyright\" content=\"PHPublisher's backend code is (c) copyright 2005 by TimTimTimma\" />\n" 
	."<meta name=\"PHPublisher_Home\" content=\"http://PHPublisher.net\" />\n" 
	."\n</head>\n<body>\n";
	$Header = ob_get_contents(); 
ob_end_clean(); 
?>
