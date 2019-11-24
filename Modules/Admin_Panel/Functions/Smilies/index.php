<?php
/*******************************************************************
 **
 ** Admin File: Smilies/index.php
 ** Description: Add / edit / remove smilies here
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}

if(isset($_GET['Delete'])){
	if(isset($_GET['smilie_id'])){
		$check_smilie_id = $MySQL->Fetch("SELECT smilie_id FROM ".$pre."smilies WHERE smilie_id = '".$_GET['smilie_id']."'");
		if($check_smilie_id['smilie_id'] != $_GET['smilie_id']){
			echo _SMILIE_DOESNT_EXIST."<br><br>";
		}else{
			$MySQL->Query("DELETE FROM ".$pre."smilies WHERE smilie_id = '".$_GET['smilie_id']."'");
			echo _SMILIE_REMOVED."<br><br>";
		}
	}
}

if($_POST['submitted']){
	$smilie_code = str_replace(" ", "", $_POST['smilie_code']);
	if(!isset($smilie_code) || empty($smilie_code)){
		echo _MISSING_SMILIE_CODE."<br><br>";
	}elseif(isset($smilie_code)){
		$Old_Code = $MySQL->Fetch("SELECT smilie_code FROM ".$pre."smilies WHERE smilie_code = '".$smilie_code."'");
		if($smilie_code === $Old_Code['smilie_code']){
			echo _CODE_ALREADY_USED."<br><br>";
		}else{
			$Insert_Smilie = 'INSERT INTO `'.$pre.'smilies` (`smilie_id`, `smilie_code`, `smilie_img`) VALUES (\'\', \''.$smilie_code.'\', \''.$_POST['smile'].'\')';
			$MySQL->Query($Insert_Smilie);
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Smilies&Succ=1");
			die();
		}
	}
}

if($_GET['Succ'] == 1){
	echo _SMILIE_ADDED."<br><br>";
}

$Table->Open();
?>
<table width="100%"  border="0" cellspacing="5" cellpadding="0">
<tr><td align=center><?=_ADD_SMILIE ?></td><td align=center><?=_CURRENT_SMILIES ?></td></tr>
  <tr>
    <td width="40%" align=center valign=top>
	<? 
		echo "<script langauge='javascript'>               
                  function showavatar(theURL) {                   
                    document.images.show_smilie.src=theURL+document.creator.smile.options[document.creator.smile.selectedIndex].value;
                  }                
          </script>";
	echo "<form action=\"index.php?find=Admin_Panel&amp;func=Smilies\" method=\"post\" onload=\"checkTheBox\"  enctype=\"multipart/form-data\" name=\"creator\">";
	if ($handle = opendir($Current_Directory.'/images/smilies')) {
		$dir = opendir($Current_Directory.'/images/smilies');
		while(false !== ($imge = readdir($dir))){
			if ($imge == "." || $imge == ".." || $imge == "index.htm"){
				continue;
			} 
			echo "<img src=\"".$base_url."/images/smilies/".$imge."\" name=\"show_smilie\" border=\"0\" hspace=\"15\"><br>";
			break;
		}
		echo "<select name=\"smile\" size=\"0\" onchange=\"showavatar('images/smilies/')\">";

			while (false !== ($img = readdir($handle))) { 
				if ($img == "." || $img == ".." || $img == "index.htm"){
					continue;
				} 
			echo "<option value=".$img.">".$img."</option>\n";
			}
		echo " </select><br>";
		closedir($handle); 
	}
	?>
	<br>
	<input type="hidden" name="submitted" value="1">
	<?=_SMILIE_CODE ?>: 
	<input type="text" name="smilie_code" size=6><br><br><input type="submit" name="Submit" value="Submit"></td>
    <td width="60%" align=center valign=top><table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td width="25%" align=center><?=_SMILIE_CODE ?></td>
    <td width="35%" align=center><?=_SMILIE_IMG ?></td>
	<td width="50%" align=center><?=_DELETE_SMILIE ?></td>
  </tr>

	<?
	$Smilie = $MySQL->Query("SELECT smilie_code, smilie_img, smilie_id FROM ".$pre."smilies ORDER BY smilie_id ASC");
	while($Smilies = mysql_fetch_array($Smilie)){
	echo "<tr>
    <td align=center>".$Smilies['smilie_code']."</td>
    <td align=center><img src=\"".$base_url."/images/smilies/".$Smilies['smilie_img']."\"></td>
	<td align=center><a href=\"index.php?find=Admin_Panel&amp;func=Smilies&amp;Delete=1&amp;smilie_id=".$Smilies['smilie_id']."\">"._DELETE_SMILIE."?</a></td>
  	</tr>";
	}
	?></table>
	</td>
  </tr>
  <tr>
    <td align=center></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<?
$Table->Close();

?>
