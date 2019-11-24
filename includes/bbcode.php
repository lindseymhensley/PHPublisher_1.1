<?php

/*******************************************************************
 **
 ** File: bbcode.php
 ** Description: if the admin has the site set to allow bbcode
 ** this function bbcode() will search and replace all elements in it.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

function bbcode($text) {
	$text = preg_replace('/\[CODE\](.+)\[\/CODE\]/siU', '<div style="text-align: left; border: #000000 1px dotted; margin: 10px; padding: 3px;"><strong>Code:</strong><div>'.htmlspecialchars("\\1").'</div></div>', $text);
	$text = preg_replace('/\[QUOTE\](.+)\[\/QUOTE\]/siU', '<div style="text-align: left; border: #000000 1px dotted; margin: 10px; padding: 3px;"><strong>Quote:</strong><div>'.htmlspecialchars("\\1").'</div></div>', $text);

	$text = preg_replace('/\[B\](.+)\[\/B\]/siU', '<b>\\1</b>', $text);
	$text = preg_replace('/\[I\](.+)\[\/I\]/siU', '<i>\\1</i>', $text);
	$text = preg_replace('/\[U\](.+)\[\/U\]/siU', '<u>\\1</u>', $text);
	$text = preg_replace('/\[S\](.+)\[\/S\]/siU', '<s>\\1</s>', $text);
	$text = preg_replace('/\[CENTER\](.+)\[\/CENTER\]/siU', '<center>\\1</center>', $text);

	$text = preg_replace('/\[URL=(.+)\](.+)\[\/URL\]/siU', '<a href="\\1" target="_blank">\\2</a>', $text);
	$text = preg_replace('/\[URL\](.+)\[\/URL\]/siU', '<a href="\\1" target="_blank">\\1</a>', $text);

	$text = preg_replace('/\[EMAIL\]([a-zA-Z0-9]+)\@([a-zA-Z0-9]+)\.([a-zA-Z]+)\[\/EMAIL\]/siU', '<a href="mailto:\\1@\\2.\\3">\\1@\\2.\\3</a>', $text);
	$text = preg_replace('/\[IMG\](\r\n|\r|\n)*((http|https):\/\/([^;<>\*\(\)\"]+)|[a-z0-9\/\\\._\- ]+)\[\/IMG\]/siU', '<img src="\\2" border="0" alt="image">', $text);

	$text = preg_replace('/\[COLOR=(.+)\](.+)\[\/COLOR\]/siU', '<font color="\\1">\\2</font>', $text);
	$text = preg_replace('/\[SIZE=([0-9]+)\](.+)\[\/SIZE\]/siU', '<font style="font-size: \\1">\\2</font>', $text);
	$text = preg_replace('/\[FACE=(.+)\](.+)\[\/FACE\]/siU', '<font style="font-family: \\1">\\2</font>', $text);

	return $text;
}

/*******************************************************************
 **
 ** Function: toolbar()
 ** Description: function that will display the bbcode toolbar
 ** wherever you want.
 **                                                  
 *******************************************************************/ 

function toolbar($form_name, $element_name) {
	$toolbar =  "
	<script language=\"javascript\">
	<!-- Begin
	
	function bold".$element_name."() {		
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[B]\" + str + \"[/B]\";
	}

	function italic".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[I]\" + str + \"[/I]\";
	}

	function underline".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[U]\" + str + \"[/U]\";
	}

	function strikethrough".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[S]\" + str + \"[/S]\";
	}

	function font_face".$element_name."(face) {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[FACE=\" + face + \"]\" + str + \"[/FACE]\";
	}

	function font_size".$element_name."(size) {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[SIZE=\" + size + \"]\" + str + \"[/SIZE]\";
	}

	function font_color".$element_name."(color) {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[COLOR=\" + color + \"]\" + str + \"[/COLOR]\";
	}

	function link".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[URL=\" + str + \"]\" + str + \"[/URL]\";
	}

	function img".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[IMG]\" + str + \"[/IMG]\";
	}

	function email".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[EMAIL]\" + str + \"[/EMAIL]\";
	}

	function quote".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[QUOTE]\" + str + \"[/QUOTE]\";
	}

	function code".$element_name."() {
		var str = document.selection.createRange().text;
		document.".$form_name.".".$element_name.".focus();
		var sel = document.selection.createRange();
		sel.text = \"[CODE]\" + str + \"[/CODE]\";
	}
	//  End -->
	</script>

	<table width='100%' border='0' cellspacing='0' cellpadding='0'>
	<tr><td>
	<div style='margin-top: 5; margin-bottom: 5'>
	<input type='button' value='B' style='font-weight: bold' onclick='bold".$element_name."();'> 
	<input type='button' value='I' style='font-style: italic' onclick='italic".$element_name."();'> 
	<input type='button' value='U' style='text-decoration: underline' onclick='underline".$element_name."();'> 
	<input type='button' value='S' style='text-decoration: line-through' onclick='strikethrough".$element_name."();'> 

	<select name='face' onchange='font_face".$element_name."(value);'>
	<option value=''>Font</option>
	<option value='arial'>Arial</option>
	<option value='times'>Times</option>
	<option value='courier'>Courier</option>
	<option value='impact'>Impact</option>
	<option value='geneva'>Geneva</option>
	<option value='optima'>Optima</option>
	<option value='verdana'>Verdana</option>
	</select> 

	<select name='size' onchange='font_size".$element_name."(value);'>
	<option value=''>Size</option>
	<option value='8'>Smallest</option>
	<option value='10'>Small</option>
	<option value='14'>Large</option>
	<option value='20'>Largest</option>
	</select> 

	<select name='color' onchange='font_color".$element_name."(value);'>
	<option value=''>Color</option>
	<option value='#DD0000'>Red</option>
	<option value='#FF9900'>Orange</option>
	<option value='#33CC00'>Green</option>
	<option value='#3300FF'>Blue</option>
	<option value='#660066'>Purple</option>
	<option value='#FFFF00'>Yellow</option>
	<option value='#797979'>Grey</option>
	<option value='#FF99CC'>Pink</option>
	</select> 
	</div>

	<div style='margin-top: 5; margin-bottom: 5'>
	<input type='button' value='http://' onclick='link".$element_name."();'> 
	<input type='button' value='IMG' onclick='img".$element_name."();'> 
	<input type='button' value='Email' onclick='email".$element_name."();'> 
	<input type='button' value='Quote' onclick='quote".$element_name."();'> 
	<input type='button' value='Code' onclick='code".$element_name."();'> 
	</div>
	</td></tr>
	</table>";

	return $toolbar;
}
?>