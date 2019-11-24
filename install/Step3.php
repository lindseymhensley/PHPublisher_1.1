<?
if(!session_id()){
	session_start();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Install PHPublisher! - Step 3</title>
<link href="style-css.css" rel="stylesheet" type="text/css" />
</head>
<body>  <div style="text-align: center; font-weight: bold; font-size:25px; margin:10px;">PHPublisher Installation Step 3 </div>
<div style="text-align: center; margin:10px;">| -&gt; First Step -&gt; Language -&gt; <strong>MySQL Information</strong> -&gt; Admin Account -&gt; Ready? -&gt; Lets do this! &lt;- | </div>
<div style="background-color: rgb(232, 232, 240);">&nbsp;</div>
<form action="Step4.php" method="post">
<?php
$_SESSION['Install']['Language'] = $_POST['lang'];
include(dirname(__FILE__)."/language/".$_SESSION['Install']['Language'].".php");
?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_DATABASE_HOST ?>:</strong></div></td>
    <td width="65%" align="left"><div style="margin:10px;">
      <input type="text" name="host">
    </div></td>
  </tr>  <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_DATABASE_NAME ?>:</strong></div></td>
    <td width="65%" align="left"><div style="margin:10px;">
      <input type="text" name="name">
    </div></td>
  </tr>  <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_DATABASE_USER ?></strong>:</div></td>
    <td width="65%" align="left"><div style="margin:10px;">
      <input type="text" name="user">
    </div></td>
  </tr>  <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_DATABASE_PASS ?></strong>:</div></td>
    <td width="65%" align="left"><div style="margin:10px;">
      <input type="password" name="pass">
    </div></td>
	  <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong><?=_TABLE_PREFIX ?></strong>:</div></td>
    <td width="65%" align="left"><div style="margin:10px;">
      <input type="text" name="pre" value="php_">
    </div></td>
  </tr>  <tr>
    <td width="35%" align="right"></td>
    <td width="65%" align="left"><div style="margin:10px;"><input name="submit" type="Submit" value="<?=_CONTINUE_STEP_4 ?>"></div></td>
  </tr>
</table>
</form>
</body>
</html>
