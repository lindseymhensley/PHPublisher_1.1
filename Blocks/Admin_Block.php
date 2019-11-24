<?php
/*******************************************************************
 **
 ** Block: Admin_Panel.php
 ** Description: Administrative options at your finger tips
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
$dont_show = array(
".",
"..",
".htaccess",
"index.php",
"index.htm",
"Modify User",
"User Groups",
"Banned",
"Articles",
"Topics",
"Smilies",
"Censored Words",
"Polls",
"Blocks",
"Content",
"Modules",
"Downloads",
"Web Links",
"Site Settings",
"Logging",
"Newsletter"
);
?>




<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td><strong>+ User Management </strong></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Modify User">Modify User</a> </td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=User Groups">User Groups</a> </td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Banned">Banned</a></td>
  </tr>
  <tr>
    <td><strong>+ Article Management </strong></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Articles">Articles</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Topics">Topics</a></td>
  </tr>
    <tr>
    <td><strong>+ Feature Management</strong></td>
  </tr>
    <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Smilies">Smilies</a></td>
  </tr>
    <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Censored Words">Censors</a></td>
  </tr>
  <tr>
    <td><strong>+ Content Management </strong></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Polls">Polls</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Blocks">Blocks</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Content">Content</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Modules">Modules</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Downloads">Downloads</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Web Links">Web Links</a></td>
  </tr>
    <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Newsletter">Newsletter</a></td>
  </tr>
  <tr>
    <td><strong>+ Site Management</strong></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Site Settings">Settings</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Logging">Logging</a></td>
  </tr>
    <tr>
    <td><strong>+ Other Management</strong></td>
  </tr>


<?
if ($handle = opendir($Current_Directory.'/Modules/Admin_Panel/Functions/')) { 
	while (false !== ($admin_func = readdir($handle))) { 
		if(in_array($admin_func ,$dont_show)){
			continue;
		}else{
		    echo "<tr>
					<td align=\"left\">| --  <a href=\"index.php?find=Admin_Panel&amp;func=".$admin_func."\">".$admin_func."</a></td>
				  </tr>";
		}
	} 
	closedir($handle); 
} 
$rLinks = $MySQL->Rows("SELECT id FROM ".$pre."link WHERE approved='0'");
$rDownloads = $MySQL->Rows("SELECT id FROM ".$pre."downloads WHERE approved='0'");
?>
  <tr>
    <td><strong>+ Hot Links </strong></td>
  </tr>
   <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&func=Web%20Links&manager=Request">Requested WL's</a>: <?=$rLinks ?></td>
  </tr>
    <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&func=Downloads&manager=Request">Requested DL's</a>: <?=$rDownloads ?></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Articles&amp;NewPost=1">Post Article</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Polls&amp;create_poll=1">Start Poll</a></td>
  </tr>
  <tr>
    <td align="left"> | -- <a href="index.php?find=Admin_Panel&amp;func=Blocks#block">Create Blocks</a></td>
  </tr>
</table>