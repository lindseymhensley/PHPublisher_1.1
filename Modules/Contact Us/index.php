<?
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
$Table->Open("<strong>"._CONTACT_US."</strong>");
?><form method="post" action="index.php?find=Contact Us&amp;file=send">
<?=_DONT_SPAM ?><br />
<table width="100%"  border="0" cellspacing="5" cellpadding="5">
<? if($user->lvl() == 0){ ?>
  <tr>
    <td width="17%" align=right><?=_NAME ?>: </td>
    <td width="83%" align=left><input type="text" name="name"></td>
  </tr>
  <? } ?>
  <tr>
    <td align=right><?=_EMAIL ?>: </td>
    <td align=left><input type="text" name="email"></td>
  </tr>
    <tr>
    <td align=right><?=_SUBJECT ?>: </td>
    <td align=left><select name="subject">
	<option>Security issue/ Bugs</option>
	<option>Suggestion</option>
    <option>Feedback</option>
	<option>Other</option>
	</select></td>
    </tr>
  <tr>
    <td align=right valign=top><?=_MESSAGE ?>: </td>
    <td align=left><textarea name="message" cols="50" rows="10"></textarea></td>
  </tr>
  <tr>
    <td align=right>&nbsp;</td>
    <td align=left><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>
</form>
<?
$Table->Close();
?>