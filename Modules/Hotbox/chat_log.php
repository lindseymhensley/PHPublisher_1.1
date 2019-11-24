<?php
define("IN_DB", true);
require("../../config_info.php");
$only_spec = TRUE;
require("../../backend.php");
require("../../Modules/Hotbox/language/".$user->language().".php");
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
if (parent.location.href == self.location.href) {
window.location.href = '<?php echo $base_url; ?>/index.php';
}
//  End -->
</script>
<?php
$Mysql_Connection = mysql_connect($dbhost, $dbuser, $dbpasswd); 
mysql_select_db($dbname, $Mysql_Connection);
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
$_POST['tag'] = trim($_POST['tag']);
	if (!isset($_POST['user_id']))
	{
		$_POST['username'] = trim($_POST['username']);
		if (!empty($_POST['username']))
		{
			if (ereg('^[a-zA-Z0-9_-].+$', $_POST['username']))
			{
				if (($u_n_i = $MySQL->Fetch("SELECT username FROM ".$pre."users WHERE username = '".$_POST['username']."'")) == TRUE)
				{
					$message_er = _USERNAME_IN_USE."<br /><br />";
				}else
				{
					if(!empty($_POST['tag'])){
						mysql_query("INSERT INTO ".$pre."hotbox VALUES ('', '".htmlentities(addslashes($_POST['username']))."', '".$user->userip()."', '".htmlentities(addslashes($_POST['tag']))."')");
					}
				}
			}else
			{
				$message_er = _ILLEGAL_NAME."<br /><br />";
			}
		}
	}else
	{
		$g_u_i = mysql_fetch_array(mysql_query("SELECT username FROM ".$pre."users WHERE user_id = ".$_POST['user_id']));
		$user_posting = $g_u_i['username'];
		if(!empty($_POST['tag'])){
			mysql_query("INSERT INTO ".$pre."hotbox VALUES ('', '".$user_posting."', '".$user->userip()."', '".htmlentities(addslashes($_POST['tag']))."')");
		}
	}
}
echo "<div style=\"font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif\">".$message_er."</div>";
$count_tag = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."hotbox"));
if ($count_tag > 0)
{
	echo "<meta http-equiv=\"refresh\" content=\"15;URL=".$base_url."/Modules/Hotbox/chat_log.php\">";
	$start_result = $count_tag - 50;
	if($start_result <= 0){
		$start_result = 0;
	}
	$tag_sql = mysql_query("SELECT * FROM ".$pre."hotbox ORDER BY tag_id DESC LIMIT ".$start_result.",".$count_tag);
	echo "<div style=\"font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif\">";
	while($tag = mysql_fetch_array($tag_sql))
	{
		$tag_post = $tag['tag'];
		if($Censor_Words == 1){
			$tag_post = censor($tag_post);
		}
		if($Emoticon_On == 1){
			$tag_post = emoticon($tag_post);
		}
		if($BBcode_On == 1){
			$tag_post = bbcode($tag_post);
		}
		echo "<strong>".$tag['username']."</strong>: ".stripslashes($tag_post)."<br />";
	}
	echo "</div>";
}else
{
	echo "<div style=\"font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif\">";
	echo "There are currently no tags.";
	echo "</div>";
}
?></body></html>