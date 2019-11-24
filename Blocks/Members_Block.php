<?php
/*******************************************************************
 **
 ** Block: Members_Block.php
 ** Description: Registered users who are logged in get a neat
 ** control panel to help manage their account
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
if($user->id() > 1){
	if ($handle = opendir($Current_Directory.'/Modules/Members/Functions/')) { 
		if(($GLOBALS['Site_Chg_Theme'] == 0) && ($user->lvl() == 1)){
			$theme = "Change Theme";
		}
		$no_show = array(".","..",".htaccess","index.htm","index.php","Logout", $theme);
		while (false !== ($mem_func = readdir($handle))) { 
			if(in_array($mem_func, $no_show)){
				echo "";
			}else{
				echo "<a href=\"index.php?find=Members&amp;func=".$mem_func."\">".$mem_func."</a><br />"; 
			}
		} 
		closedir($handle); 
	}
	echo "<a href=\"index.php?find=Members&amp;func=Logout\">Logout</a></div>";
} else {
	include($Current_Directory."/Modules/Members/language/".$user->language().".php");
	?>
	<form method="post" action="index.php?find=Members&amp;file=Login">
	<input name="submitted" type="hidden" value="1" />
	<table width="100%"  border="0" cellspacing="2" cellpadding="0">
	  <tr>
		<td width="18%"><?=_USERNAME ?>:</td>
		<td width="82%"><input type="text" name="usr_name" size="10" /></td>
	  </tr>
	  <tr>
		<td><?=_PASSWORD ?>:</td>
		<td><input type="password" name="passwrd" size="10" /></td>
	  </tr>
	  <? if($GLOBALS['Security_Login'] == 1){ ?>
	  	  <tr>
		<td><?=_S_CODE ?>:</td>
		<td><?php
			$rand = rand(1,10000);
		?>
		<input name="rand" type="hidden" value="<?=$rand ?>" />
		<input name="Security_Login" type="hidden" value="1" />
		<img src="<?=$GLOBALS['base_url'] ?>/Modules/Members/img.php?rand=<?=$rand ?>&amp;base_url=<?=$GLOBALS['base_url'] ?>" alt="Security Code" /></td>
	  	  </tr>
	  	  <tr>
		<td><?=_S_VERIFY ?>:</td>
		<td><input name="security_code" type="text" maxlength="6" size="10" /></td>
	  	  </tr>
		  <? } ?>
	  	  <tr>
		<td><?=_R_ME ?>:</td>
		<td><input type="checkbox" name="remember_me" value="1" size="10" /></td>
	  	  </tr>
	  	  <tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="Submit" value="<?=_LOGIN ?>" /></td>
	  </tr>
	</table>
	</form> Don't have an account yet? You can <b><a href="index.php?find=Members&amp;file=Register">create one</a></b>. As a registered user you have some advantages like theme management, and posting comments with your name.<?
}
?>