<?php
/*******************************************************************
 **
 ** File: refresh_content.php
 ** Description: When asked it unsets every session in the entire
 ** site and then allows the site to reset everything itself
 **                                                  
 *******************************************************************/ 

if(isset($settings)){
	session_unset();
	session_destroy();
}else{
	include($Current_Directory."/includes/headers.php");
	echo $Header;
	echo "<div align=\"center\" style=\"margin-top: 150px\">"
	."<table width=\"50%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
	."<tr><td align=\"center\">";
	$Table->Open();
	?>
	<meta http-equiv="refresh" content="3;URL=<?=$base_url ?>/index.php">
	  <p style="text-align: center;">Refreshing content, please be patient while we forward you...</p>
	  <p style="text-align: center;"><a href="<?=$base_url ?>/index.php"><em>(Click here if you are not forwarded)</em></a></p>
	<?
	$Table->Close();
	echo "</td></tr></table></div></body>
	</html>";
	session_unset();
	session_destroy();
}
?>