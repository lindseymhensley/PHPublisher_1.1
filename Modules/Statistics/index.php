<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

$param = array("hits","MSIE","FireFox","Netscape","Opera","Bot","Other","Windows","Linux","Mac/PPC","Other");
$count_param = count($param);

if(empty($_SESSION['stat'])){
	for($i = 0; $i <= $count_param; $i++){
		$_SESSION['stat'][$param[$i]] = $MySQL->Fetch("SELECT count FROM ".$pre."counter WHERE var = '".$param[$i]."'"); 
	}
	
	$_SESSION['stat']['misc']['Total_Articles'] = $MySQL->Rows("SELECT Article_ID FROM ".$pre."News_Articles");
	$_SESSION['stat']['misc']['Total_Comments'] = $MySQL->Rows("SELECT Comment_ID FROM ".$pre."Article_Comments") + $MySQL->Rows("SELECT Comment_ID FROM ".$pre."Poll_Comments");
	$_SESSION['stat']['misc']['Total_Members'] = $MySQL->Rows("SELECT user_id FROM ".$pre."users");
	
	header("Location: ".$base_url."/index.php?find=Statistics");
	die();
}

$Table->Open("<strong>"._TOTAL_HITS."</strong>");

echo "<table width=\"*%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=center>
  <tr>
    <td align=center>"._WE_HAVE_HAD." <strong>".number_format($_SESSION['stat']['hits']['count'])."</strong> "._PAGE_VIEWS." ".$Site_Start_Date.".</td>
  </tr>
</table>";

$Table->Close();
echo "<br />";
$Table->Open("<strong>"._BROWSERS."</strong>");

echo "<table width=\"210px\"  border=0 cellspacing=2 cellpadding=2 align=center>
  <tr>
  	<td width=*% align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/explorer.gif\"></td>
    <td width=50%>"._MSIE."</td>
    <td width=*%>".number_format($_SESSION['stat']['MSIE']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/firefox.gif\"></td>
    <td>"._FF."</td>
    <td>".number_format($_SESSION['stat']['FireFox']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/netscape.gif\"></td>
    <td>"._NS."</td>
    <td>".number_format($_SESSION['stat']['Netscape']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/opera.gif\"></td>
    <td>"._OPERA."</td>
    <td>".number_format($_SESSION['stat']['Opera']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/altavista.gif\"></td>
    <td>"._BOTS."</td>
    <td>".number_format($_SESSION['stat']['Bot']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/question.gif\"></td>
    <td>"._OTHER."</td>
    <td>".number_format($_SESSION['stat']['Other']['count'])."</td>
  </tr>
</table>";
$Table->Close();
echo "<br>";

$Table->Open("<strong>"._OS."</strong>");

echo "<table width=\"210px\" border=0 cellspacing=2 cellpadding=2 align=center>
  <tr>
  	<td width=*% align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/windows.gif\"></td>
    <td width=50%>"._WIN."</td>
    <td width=*%>".number_format($_SESSION['stat']['Windows']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/linux.gif\"></td>
    <td>"._LINUX."</td>
    <td>".number_format($_SESSION['stat']['Linux']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/mac.gif\"></td>
    <td>"._MAC."</td>
    <td>".number_format($_SESSION['stat']['Mac/PPC']['count'])."</td>
  </tr>
  <tr>
  	<td align=right><img src=\"".$GLOBALS['base_url']."/Modules/Statistics/images/question.gif\"></td>
    <td>"._OTHER."</td>
    <td>".number_format($_SESSION['stat']['Other']['count'])."</td>
  </tr>
</table>";
$Table->Close();
echo "<br>";

$Table->Open("<strong>"._MISC."</strong>");
	echo "<table width=\"220px\"  border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=center>
	  <tr>
	  	<td width=*%>&nbsp;</td>
		<td width=55%>"._TOTAL_ARTICLES."</td>
		<td width=*%>".number_format($_SESSION['stat']['misc']['Total_Articles'])."</td>
	  </tr>
	  <tr>
	  	<td>&nbsp;</td>
		<td>"._TOTAL_COMMENTS."</td>
		<td>".number_format($_SESSION['stat']['misc']['Total_Comments'])."</td>
	  </tr>
	  <tr>
	  	<td>&nbsp;</td>
		<td>"._TOTAL_MEMBERS."</td>
		<td>".number_format($_SESSION['stat']['misc']['Total_Members'])."</td>
	  </tr>
	  <tr>
	  	<td>&nbsp;</td>
		<td>"._VERSION."</td>
		<td>".$version."</td>
	  </tr>
	</table>";
$Table->Close();
?>
