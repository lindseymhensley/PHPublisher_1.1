<?PHP
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
 ** $dbhost                :: Host of your machine (usually localhost)
 ** $dbname                :: name of your MySQL Database
 ** $dbuser                :: Username to your MySQL Database
 ** $dbpasswd              :: Password to your MySQL Database
 ** $pre                   :: Prefix to your database Tables
 **
 ******************************************************************/
 
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

$dbhost = "";
$dbname = "";
$dbuser = "";
$dbpasswd = "";
$pre = "php_";

?>