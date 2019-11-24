<?php
 /*******************************************************************
 **
 ** File: rss.php
 ** Description: Real Simple Syndication
 **
 *******************************************************************/
define("IN_DB", true);
require("config_info.php");
require("backend.php");
$MySQL->Connection("Open");
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\"?>
<rss version=\"2.0\" xmlns:blogChannel=\"http://backend.userland.com/blogChannelModule\">
<channel>";
$result = $MySQL->Query("SELECT * FROM ".$pre."News_Articles ORDER BY Article_Time DESC LIMIT 5");
while($Get = mysql_fetch_array($result)) {
	$Get['Article_Title'] = htmlentities($Get['Article_Title']);
	$Get['Article_Story'] = htmlentities($Get['Article_Story']);
	$Get['Article_Full_Story'] = htmlentities($Get['Article_Full_Story']);
	echo "
	<item>
	<title>".$Get['Article_Title']."</title>
	<link>".$base_url ."/index.php?find=News&amp;file=Full_Story&amp;Show_Full=1&amp;Article_ID=".$Get['Article_ID']."</link>
	<author>".$Get['Article_Author']."</author>
	<date>". $Get['Article_Date']."</date>
	<description>". $Get['Article_Story']."</description>
	</item>";
}
echo "
</channel>
</rss>";
$MySQL->Connection("Close");
?>