<?php
/*******************************************************************
 **
 ** Admin File: Censored Words/index.php
 ** Description: replace unwanted word output with more presentable
 ** and respectful ones
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if($user->lvl() <= 2){
	header("Location: ".$base_url."/index.php?access=no");
	die();
}

if(isset($_POST['submitted'])){
	$censor_word = str_replace(" ", "", $_POST['censor_word']);
	$replacement = str_replace(" ", "", $_POST['replacement']);
	if(isset($censor_word) && !empty($censor_word)){
		if(isset($replacement) && !empty($replacement)){
			$Insert_Word = 'INSERT INTO `'.$pre.'censor_words` (`word_id`, `bad_word`, `replacement`) VALUES (\'\', \''.$censor_word.'\', \''.$replacement.'\')';
			$MySQL->Query($Insert_Word);
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Censored Words&Succ=1&censor_word=".$censor_word."");
            		die();
    		}else{
			echo _REPLACEMENT_WORD_MISSING."<br><br>";
		}
	}else{
		echo _CENSORED_WORD_MISSING."<br><br>";
	}
}elseif(isset($_GET['Succ'])){
	echo _WORD_SUCCESSFULLY_CENSORED."<br><br>";
}elseif(isset($_GET['delete'])){
	if(isset($_GET['word_id'])){
		$check_word = $MySQL->Fetch("SELECT word_id FROM ".$pre."censor_words WHERE word_id = '".$_GET['word_id']."'");
		if($word_id != $check_word['word_id']){
			echo _MISSING_WORD_ID."<br><br>";
		}else{
			$MySQL->Query("DELETE FROM ".$pre."censor_words WHERE word_id = '".$_GET['word_id']."' LIMIT 1");
			header("Location: ".$base_url."/index.php?find=Admin_Panel&func=Censored Words&deleted=1");
            		die();
       		}
	}
}elseif(isset($_GET['deleted'])){
	echo _WORD_UNCENSORED."<br><br>";
}
 
$Table->Open(); ?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" align=center>
	
	<form action="index.php?find=Admin_Panel&amp;func=Censored Words" method="post">
	<input type="hidden" name="submitted" value="1">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="30%" align=left><?=_WORD_TO_CENSOR ?>:</td>
    <td width="70%" align=left><input name="censor_word" type="text"></td>
  </tr>
  <tr>
    <td align=left><?=_REPLACE_WITH ?>:</td>
    <td align=left><input name="replacement" type="text"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align=left><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>

	</form>
	
	</td>
    <td width="50%" align=center><table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr><td align=center><strong><?=_CENSORED_WORD ?></strong></td>
    <td align=center><strong><?=_REPLACEMENT ?></strong></td><td align=center><strong><?=_UNCENSOR ?></strong></td></tr><?
	
	$censored = $MySQL->Query("SELECT bad_word, replacement, word_id FROM ".$pre."censor_words ORDER BY word_id");
	while($word = mysql_fetch_array($censored)){
		echo "<tr>"
		. "<td align=center>".$word['bad_word']."</td>"
		. "<td align=center>".$word['replacement']."</td>"
		. "<td align=center><a href=\"index.php?find=Admin_Panel&amp;func=Censored Words&amp;delete=1&amp;word_id=".$word['word_id']."\">"._UNCENSOR."?</a></td>"
		. "</tr>";
	}
	
	?></table></td>
  </tr>
</table>


<? $Table->Close(); ?>
