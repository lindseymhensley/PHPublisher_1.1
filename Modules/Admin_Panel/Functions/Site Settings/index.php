<?php
/*******************************************************************
 **
 ** Admin File: Site Settings/index.php
 ** Description: Change the way the entire site is set up from this
 ** file
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() != 99){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}

if(empty($_POST['site_title'])) $_POST['site_title'] = "Site Title";
if(empty($_POST['site_slogan'])) $_POST['site_slogan'] = "Site slogan here.";
if(empty($_POST['site_description'])) $_POST['site_description'] = "description";
if(empty($_POST['site_keywords'])) $_POST['site_keywords'] = "keywords";
if(empty($_POST['admin_email'])) $_POST['admin_email'] = "admin@yoursite.com";
if(empty($_POST['frontpage_content'])) $_POST['frontpage_content'] = "content goes here";
if(empty($_POST['footer1'])) $_POST['footer1'] = "Footer 1";
if(empty($_POST['footer2'])) $_POST['footer2'] = "Footer 2";
if(empty($_POST['base_location'])) $_POST['base_location'] = "http://yoursite.com";
if(empty($_POST['start_date'])) $_POST['start_date'] = $date;

if($_POST['Submit']){
	$MySQL->Query("UPDATE ".$pre."settings SET
    	site_title = '".$_POST['site_title']."',
    	site_slogan = '".$_POST['site_slogan']."',
    	site_description = '".$_POST['site_description']."',
    	site_keywords = '".$_POST['site_keywords']."',
    	admin_email = '".$_POST['admin_email']."',
    	site_frontpage = '".$_POST['enable_content']."',
    	site_frontpage_content = '".$_POST['frontpage_content']."',
    	site_theme = '".$_POST['theme']."',
    	guest_allowed = '".$_POST['guest_allowed']."',
    	site_lang = '".$_POST['lang']."',
    	censor_words = '".$_POST['censor_words']."',
    	footer1 = '".$_POST['footer1']."',
    	footer2 = '".$_POST['footer2']."',
    	poweredby = '".$_POST['enable_poweredby']."',
    	base_url = '".$_POST['base_location']."',
    	secure_login = '".$_POST['secure_login']."',
    	emoticon_on = '".$_POST['enable_emot']."',
    	show_new_news = '".$_POST['show_new_news']."',
    	show_old_news = '".$_POST['show_old_news']."',
    	bbcode_on = '".$_POST['enable_bbcode']."',
    	show_polls = '".$_POST['show_polls']."',
    	start_date = '".$_POST['start_date']."',
		site_logging = '".$_POST['site_logging']."',
		site_gzip = '".$_POST['site_gzip']."',
		site_max_sig = '".$_POST['site_max_sig']."',
		site_chgtheme = '".$_POST['chgtheme']."',
		site_cookie_domain = '".$_POST['site_cookie_domain']."',
		site_cookie_path = '".$_POST['site_cookie_path']."',
		site_suspect_filter = '".$_POST['site_suspect_filter']."'
    	WHERE setting_id = 1");
    
	$MySQL->Query("UPDATE ".$pre."users SET
    	user_theme = '".$_POST['theme']."'
    	WHERE user_id = '1'");
	session_unset();
	session_destroy();
	header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Site Settings&update=1");
	die();
}

if(isset($_GET['update'])){
	echo _SITE_UPDATED;
	echo "<br /><br />";
}

$Table->Open();
	echo "<center>"._WEBSITE_CONFIGURATION."</center>";
$Table->Close();
echo "<br>";
$Table->Open(_GEN_INFO);
?>
<form action="index.php?find=Admin_Panel&amp;func=Site Settings" method="POST">
<table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr><td align="left"><?=_SITE_URL ?>:</td>
<td align="left"><input type="text" name="base_location" value="<?=$base_url ?>"></td>
  </tr>
    <tr><td align="left"><?=_START_DATE ?>:</td>
<td align="left"><input type="text" name="start_date" value="<?=$Site_Start_Date ?>"></td>
  </tr>
  <tr>
    <td width="40%" align="left"><?=_SITE_TITLE ?>:</td>
    <td width="60%" align="left"><input type="text" name="site_title" value="<?=$Site_Title ?>"></td>
  </tr>
  <tr><td align="left"><?=_SITE_SLOGAN ?>:</td>
<td align="left"><input type="text" name="site_slogan" value="<?=$Site_Slogan ?>"></td>
  </tr>
      <tr><td align="left"><?=_SITE_DESCRIPTION ?>:</td>
<td align="left"><input type="text" name="site_description" value="<?=$Site_Description ?>"></td>
  </tr>
      <tr><td align="left"><?=_SITE_KEYWORDS ?>:</td>
<td align="left"><input type="text" name="site_keywords" value="<?=$Site_Keywords ?>"></td>
  </tr>
  <tr><td align="left"><?=_ADMIN_EMAIL ?>:</td>
<td align="left"><input type="text" name="admin_email" value="<?=$Admin_Email ?>"></td>
  </tr>
  
</table>
<?
$Table->Close();
?>
<br>
<? $Table->Open(_SITE_OPTIONS); ?>

<table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr><td align="left"><?=_SITE_MAX_SIG ?>:</td>
<td align="left"><select name="site_max_sig">
	<option value="100" <? if($Site_Max_Sig_Length == 100) echo "selected"; ?>>100</option>
	<option value="150" <? if($Site_Max_Sig_Length == 150) echo "selected"; ?>>150</option>
	<option value="200" <? if($Site_Max_Sig_Length == 200) echo "selected"; ?>>200</option>
	<option value="250" <? if($Site_Max_Sig_Length == 250) echo "selected"; ?>>250</option>
	<option value="500" <? if($Site_Max_Sig_Length == 500) echo "selected"; ?>>500</option>
	<option value="1000" <? if($Site_Max_Sig_Length == 1000) echo "selected"; ?>>1000</option>
    </select></td>
  </tr>
  <tr>
    <td width="40%" align="left"><?=_ENABLE_SECURE_LOGIN ?>:</td>
    <td width="60%" align="left"><input name="secure_login" type="radio" value="1" <? if($Security_Login == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="secure_login" type="radio" value="0" <? if($Security_Login == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
    <tr>
    <td width="40%" align="left"><?=_ENABLE_LOGGING ?>:</td>
    <td width="60%" align="left"><input name="site_logging" type="radio" value="1" <? if($Site_Logging == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="site_logging" type="radio" value="0" <? if($Site_Logging == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
      <tr>
    <td width="40%" align="left"><?=_ENABLE_SUSPECT_FILTER ?>:</td>
    <td width="60%" align="left"><input name="site_suspect_filter" type="radio" value="1" <? if($Enable_Suspect_Filter == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="site_suspect_filter" type="radio" value="0" <? if($Enable_Suspect_Filter == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
      <tr>
    <td width="40%" align="left"><?=_ENABLE_CHGTHM ?>:</td>
    <td width="60%" align="left"><input name="chgtheme" type="radio" value="1" <? if($Site_Chg_Theme == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="chgtheme" type="radio" value="0" <? if($Site_Chg_Theme == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
    <tr>
    <td width="40%" align="left"><?=_ENABLE_GZIP ?>:</td>
    <td width="60%" align="left"><input name="site_gzip" type="radio" value="1" <? if($Site_Gzip == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="site_gzip" type="radio" value="0" <? if($Site_Gzip == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
  <tr>
    <td width="40%" align="left"><?=_ALLOW_GUESTS ?>:</td>
    <td width="60%" align="left"><input name="guest_allowed" type="radio" value="1" <? if($Guest_Allowed == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="guest_allowed" type="radio" value="0" <? if($Guest_Allowed == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
    <tr>
    <td width="40%" align="left"><?=_ENABLE_EMOTICONS ?>:</td>
    <td width="60%" align="left"><input name="enable_emot" type="radio" value="1" <? if($Emoticon_On == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="enable_emot" type="radio" value="0" <? if($Emoticon_On == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
      <tr>
    <td width="40%" align="left"><?=_ENABLE_BBCODE ?>:</td>
    <td width="60%" align="left"><input name="enable_bbcode" type="radio" value="1" <? if($BBcode_On == 1)	echo "checked"; ?>> <?=_YES ?>
      <input name="enable_bbcode" type="radio" value="0" <? if($BBcode_On == 0)	echo "checked"; ?>>
      <?=_NO ?></td>
  </tr>
  <tr><td align="left"><?=_DEFAULT_THEME ?>:</td>
<td align="left"><? 
	$haystack = array(".","..",".htaccess","index.htm","index.html","index.php");
	echo "<select name=\"theme\">";
	if ($handle = opendir($Current_Directory.'/Templates/')) { 
		while (false !== ($theme = readdir($handle))) { 
			if(in_array($theme, $haystack)){
				echo "";
			}else{
				if($Site_Theme === $theme){
					echo "<option value=\"$theme\" selected>$theme</option>"; 
				}else{
					echo "<option value=\"$theme\">$theme</option>";
				}
			}
		} 
		closedir($handle); 
	} 
	echo "</select>";
	
	?></td>
  </tr>
    <tr><td align="left"><?=_DEFAULT_LANG ?>:</td>
<td align="left"><? 
	
	echo "<select name=\"lang\">";
	if ($handle = opendir($Current_Directory.'/Modules/Admin_Panel/language/')) { 
		while (false !== ($lang = readdir($handle))) { 
			if($lang === "." || $lang === ".." || $lang === ".htaccess"){
				echo "";
			}else{
				$lang = explode(".php", $lang);
				if($Site_Lang === $lang[0]){
					echo "<option value=\"".$lang[0]."\" selected>".$lang[0]."</option>"; 
				}else{
					echo "<option value=\"".$lang[0]."\">".$lang[0]."</option>";
				}
			}
		} 
		closedir($handle); 
	} 
	echo "</select>";
	
	?></td>
  </tr>
      <tr><td align="left"><?=_FRONT_PAGE_CONTENT_ENABLED ?>:</td>
<td align="left"><input name="enable_content" type="radio" value="1" <? if($Site_Frontpage == 1)	echo "checked"; ?>><?=_YES ?>
      <input name="enable_content" type="radio" value="0" <? if($Site_Frontpage == 0)	echo "checked"; ?>><?=_NO ?></td>
  </tr>
      <tr><td align="left" valign="top"><?=_FRONT_PAGE_CONTENT ?>:</td>
<td align="left"><textarea name="frontpage_content" cols="50" rows="5"><?=$Site_Frontpage_Content ?></textarea>&nbsp;</td>
  </tr>
    <tr><td align="left"><?=_CENSOR_WORDS ?>:</td>
<td align="left"><input name="censor_words" type="radio" value="1" <? if($Censor_Words == 1)	echo "checked"; ?>><?=_YES ?>
      <input name="censor_words" type="radio" value="0" <? if($Censor_Words == 0)	echo "checked"; ?>><?=_NO ?></td>
  </tr>
    </tr>
    <tr><td align="left"><?=_SHOW_NEW_ARTICLES ?>:</td>
<td align="left"><select name="show_new_news">
	<option value="5" <? if($Show_New_News == 5) echo "selected"; ?>>5</option>
	<option value="10" <? if($Show_New_News == 10) echo "selected"; ?>>10</option>
	<option value="15" <? if($Show_New_News == 15) echo "selected"; ?>>15</option>
	<option value="20" <? if($Show_New_News == 20) echo "selected"; ?>>20</option>
	<option value="25" <? if($Show_New_News == 25) echo "selected"; ?>>25</option>
	<option value="50" <? if($Show_New_News == 50) echo "selected"; ?>>50</option>
    </select></td>
  </tr>
    </tr>
    <tr><td align="left"><?=_SHOW_OLD_ARTICLES ?>:</td>
<td align="left"><select name="show_old_news">
	<option value="5" <? if($Show_Old_News == 5) echo "selected"; ?>>5</option>
	<option value="10" <? if($Show_Old_News == 10) echo "selected"; ?>>10</option>
	<option value="15" <? if($Show_Old_News == 15) echo "selected"; ?>>15</option>
	<option value="20" <? if($Show_Old_News == 20) echo "selected"; ?>>20</option>
	<option value="25" <? if($Show_Old_News == 25) echo "selected"; ?>>25</option>
	<option value="50" <? if($Show_Old_News == 50) echo "selected"; ?>>50</option>
    </select></td>
  </tr>
      <tr><td align="left"><?=_SHOW_POLLS ?>:</td>
<td align="left"><select name="show_polls">
	<option value="10" <? if($Show_Polls == 10) echo "selected"; ?>>10</option>
	<option value="15" <? if($Show_Polls == 15) echo "selected"; ?>>15</option>
	<option value="25" <? if($Show_Polls == 25) echo "selected"; ?>>25</option>
	<option value="50" <? if($Show_Polls == 50) echo "selected"; ?>>50</option>
	<option value="75" <? if($Show_Polls == 75) echo "selected"; ?>>75</option>
	<option value="100" <? if($Show_Polls == 100) echo "selected"; ?>>100</option>
    </select></td>
  </tr> 
  </table>
  <? $Table->Close(); ?>
  <br />
  <? $Table->Open("<strong>"._COOKIE_SETTINGS."</strong>"); ?>
  <table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr>
  <td align="left" width="40%"><?=_COOKIE_DOMAIN ?>:</td>
  <td align="left" width="60%"><input type="text" name="site_cookie_domain" value="<?=$Cookie_Domain ?>"></td>
  </tr>
  <tr>
  <td align="left"><?=_COOKIE_PATH ?>:</td>
  <td align="left"><input type="text" name="site_cookie_path" value="<?=$Cookie_Path ?>"></td>
  </tr>
  </table>
  <? $Table->Close(); ?>
  <br />
  <? $Table->Open(_FOOTERS); ?>
  <table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td width="40%" align="left" valign="top"><?=_FOOTER_1 ?>:</td>
    <td width="60%" align="left"><textarea name="footer1" cols="50" rows="5"><?=$Footer1 ?></textarea></td>
  </tr>
  <tr><td align="left" valign="top"><?=_FOOTER_2 ?>:</td>
<td align="left"><textarea name="footer2" cols="50" rows="5"><?=$Footer2 ?></textarea></td>
  </tr>
  <tr><td align="left"><?=_ENABLE_POWEREDBY ?></td>
<td align="left"><input name="enable_poweredby" type="radio" value="1" <? if($PoweredBy == 1)	echo "checked";?>><?=_YES ?>
      <input name="enable_poweredby" type="radio" value="0" <? if($PoweredBy == 0)	echo "checked";?>><?=_NO ?></td>
  </tr>
  <tr><td align="left">&nbsp;</td>
<td align="left"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>

  </form>
  <? $Table->Close(); ?>
