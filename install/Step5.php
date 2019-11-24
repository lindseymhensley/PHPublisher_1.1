<?
if(!session_id()){
	session_start();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Install PHPublisher! - Step 5</title>
<link href="style-css.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="text-align: center; font-weight: bold; font-size:25px; margin:10px;">PHPublisher Installation Step 5 </div>
<div style="text-align: center; margin:10px;">| -&gt; First Step -&gt; Language -&gt; MySQL Information -&gt; Admin Account -&gt; <strong>Ready?</strong> -&gt; Lets do this! &lt;- | </div>
<div style="background-color: rgb(232, 232, 240);">&nbsp;</div><br>
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
?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_PHPUBLISHER_INSTALL_SELF ?>
      <br>
      <br><a href="Step6.php"><?=_INSTALL_IT ?></a>
    </strong></div></td>
</tr>
</table>
</body>
</html>
