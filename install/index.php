<?
if(!session_id()){
	session_start();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style-css.css">

<title>Install PHPublisher! - Step 1</title>
<link href="style-css.css" rel="stylesheet" type="text/css" />
</head>
<body>  <div style="text-align: center; font-weight: bold; font-size:25px; margin:10px;">PHPublisher Installation Step 1 </div>
<div style="text-align: center; margin:10px;">| -&gt; <strong>First Step</strong> -&gt; Language -&gt; MySQL Information -&gt; Admin Account -&gt; Ready? -&gt; Lets do this! &lt;- | </div>
<div style="background-color: rgb(232, 232, 240);">&nbsp;</div>
<form action="Step2.php" method="post">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="35%" align="right" style="background-color: rgb(232, 232, 240);"><div style="margin:10px;"><strong>URL pointing where phpublisher will be installed :</strong></div>
  	<td width="65%" align="left"><div style="margin:10px;">
	  <input type="text" name="url" size="50" value="http://">
  	(No trailing slash! Ex: "http://mysite.com" NOT "http://mysite.com/")</div></td>
  </tr>
  <tr>
    <td align="right"></td>
    <td align="left"><div style="margin:10px;">
      <input type="submit" name="Submit" value="Continue to Step 2!">
    </div></td>
  </tr>
</table></form>
</body>
</html>
