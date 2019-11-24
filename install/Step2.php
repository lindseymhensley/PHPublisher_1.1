<?
if(!session_id()){
	session_start();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Install PHPublisher! - Step 2</title>
<link href="style-css.css" rel="stylesheet" type="text/css" />
</head>
<body>  <div style="text-align: center; font-weight: bold; font-size:25px; margin:10px;">PHPublisher Installation Step 2 </div><form action="Step3.php" method="post">
<div style="text-align: center; margin:10px;">| -&gt; First Step -&gt; <strong>Language</strong> -&gt; MySQL Information -&gt; Admin Account -&gt; Ready? -&gt; Lets do this! &lt;- | </div>
<div style="background-color: rgb(232, 232, 240);">&nbsp;</div>
<?php
$_SESSION['Install']['URL'] = $_POST['url'];
?>
<br>  
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong>Language:</strong></div>
  	<td width="65%" align="left"><div style="margin:10px;">
	<select name="lang">
	<option value="english">English</option>
    </select></div></td>
  </tr>
  <tr>
    <td align="right"></td>
    <td align="left"><div style="margin:10px;">
      <input type="submit" name="Submit" value="Continue to Step 3!">
    </div></td>
  </tr>
</table></form>
</body>
</html>
