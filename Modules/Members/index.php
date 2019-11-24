<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->id() == 1){
	header("Location: ".$base_url."/index.php?find=Members&file=Login");
	die();
}elseif($user->id() > 1){
	$Table->Open();
		echo _WELCOME;
		echo "<br><br><table width=\"100%\"  border=\"0\" cellspacing=\"5\" cellpadding=\"0\">";
		echo "<tr><td width=\"20%\"></td><td width=\"20%\"></td><td width=\"20%\"></td><td width=\"20%\"></td><td width=\"20%\"></td></tr>";	
		if ($handle = opendir($Current_Directory.'/Modules/Members/Functions/')) { 
			$i = 1;
			if($Site_Chg_Theme == 0){
				$theme = "Change Theme";
			}
			$dont_show = array(".", "..", ".htaccess", "index.php", "index.htm", "index.html", $theme);
			while (false !== ($function = readdir($handle))) { 
				if(in_array($function, $dont_show)){
					echo "";
				}else{
					if($i == 1){
						echo "<tr>"
    					."<td align=center valign=top><a href=\"index.php?find=Members&amp;func=".$function."\"><img src=\"".$base_url."/Modules/Members/Functions/".$function."/icon.png\" alt=\"".$function."\" border=\"0\"><br />".$function."</a></td>";
					}elseif($i >= 2 && $i < 5){
						echo "<td align=center valign=top><a href=\"index.php?find=Members&amp;func=".$function."\"><img src=\"".$base_url."/Modules/Members/Functions/".$function."/icon.png\" alt=\"".$function."\" border=\"0\"><br />".$function."</a></td>";
					}elseif($i == 5){
						echo "<td align=center valign=top><a href=\"index.php?find=Members&amp;func=".$function."\"><img src=\"".$base_url."/Modules/Members/Functions/".$function."/icon.png\" alt=\"".$function."\" border=\"0\"><br />".$function."</a></td>"
  						."</tr>";
						unset($i);
						$i = 0;
					}
					$i++;
				}
			} 
			closedir($handle); 
		} 
		echo "</table><br>";
	$Table->Close();
	echo "<br>";
	if(isset($_GET['func'])){
		$function = $Current_Directory."/Modules/Members/Functions/".$_GET['func']."/index.php";
		if(file_exists($function)){
			include($function);
		}else{
			include($Current_Directory."/Modules/Members/Functions/index.php");
		}
	}else{
		include($Current_Directory."/Modules/Members/Functions/index.php");
	} 
	

}else{
	echo _ERROR;
}
?>
