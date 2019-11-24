<SCRIPT LANGUAGE="JavaScript">
<!--
function mySubmit() {
    setTimeout('document.Chat_Form.reset()',1);
}
//-->
</SCRIPT>
<?php
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}
include($Current_Directory."/Modules/Hotbox/language/".$user->language().".php"); 
?>
<form name="Chat_Form" action="<?php echo $GLOBALS['base_url']."/Modules/Hotbox/chat_log.php#chat" ?>" method="post" target="chat_window" onSubmit="mySubmit()">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td><div style="text-align: left;" id="chat_window">
				<iframe src="<?php echo $GLOBALS['base_url']."/Modules/Hotbox/chat_log.php#chat" ?>" width="100%" height="250" scrolling="yes" frameBorder="0" name="chat_window"></iframe>
			</div>
		</td>
	  </tr>
	  
	  <?php
	  if($user->id() == 1){
	  		if($GLOBALS['Guest_Allowed'] == 1){
	  ?>
	  <tr>
		<td><?php echo _USERNAME; ?>:</td>
	  </tr>
	  <tr>
		<td><input type="text" name="username" maxlength="25" value="Guest"></td>
	  </tr>
	  <tr>
		<td><?php echo _TAG; ?>:</td>
	  </tr>
	  <tr>
		<td><input type="text" name="tag" maxlength="100"></td>
	  </tr>
	  <tr>
		<td><input type="submit" name="Submit" value="Tag"></td>
	  </tr>
	  <?php
	  		}else{
				echo "<tr><td>"._MUST_LOG_IN."</td></tr>";
			}
	  }else{
	  		if ($user->id() > 1)
	  		{ ?>
	  <input name="user_id" type="hidden" value="<?php echo $user->id(); ?>">
	  <?php 
	  		} 
	  }
	  if ($user->id() > 1)
	  {
	  ?>
	  <tr>
		<td><?php echo _TAG; ?>:</td>
	  </tr>
	  <tr>
		<td><input type="text" name="tag" maxlength="100"></td>
	  </tr>
	  <tr>
		<td><input type="submit" name="Submit" value="Tag"></td>
	  </tr>
	  <?php } ?>
	</table>
</form>
