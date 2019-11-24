<?php
/*******************************************************************
 **
 ** File: comments.php
 ** Description: Place where comments maybe posted.
 **
 *******************************************************************/
 
function post_comment($extra_values, $hidden_fields = ""){
	global $Guest_Allowed, $user, $Emoticon_On, $BBcode_On, $MySQL, $pre;
	if($Guest_Allowed == 1 || $user->id() > 1){
        echo "<strong>Post Comment</strong>
        <form action=\"index.php".$extra_values."\" method=\"post\" name=\"Comment\">";
        if(isset($hidden_fields)){
            $array = $hidden_fields;
            $count = count($array);
            for($i = 0; $i <= $count; $i++){
                echo $array[$i];
            }
        }
        echo "<script language=\"JavaScript\" type=\"text/javascript\">
            function emoticon(text) {
            	var txtarea = document.Comment.Comment_Content;
            	text = ' ' + text + ' ';
            	if (txtarea.createTextRange && txtarea.caretPos) {
            		var caretPos = txtarea.caretPos;
            		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
            		txtarea.focus();
            	} else {
            		txtarea.value  += text;
            		txtarea.focus();
            	}
            }
        </script>
        <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=center>
        <tr>
        <td align=center><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr>
        <td align=center><table width=\"80%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=center>";

        if($Emoticon_On == 1){
    		$find_smilies = $MySQL->Query("SELECT smilie_code, smilie_img FROM ".$pre."smilies ORDER BY smilie_id ASC");
    		$i = 1;

    		while($emo = mysql_fetch_array($find_smilies)){
    			if($i == 1){
    				echo "<tr>"
    				."<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$GLOBALS['base_url']."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
    			}elseif($i >=2 && $i < 5){
    				echo "<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$GLOBALS['base_url']."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>";
    			}elseif($i == 5){
    				echo "<td><a href=\"javascript:emoticon('".$emo['smilie_code']."')\"><img src=\"".$GLOBALS['base_url']."/images/smilies/".$emo['smilie_img']."\" border=0></a></td>"
    				."</tr>";
    				unset($i);
    				$i = 0;
    			}
    			$i++;
    		}
    	}
    	echo "<tr><td align=center colspan=7>";
    	if($BBcode_On == 1){
    		echo toolbar("Comment", "Comment_Content");
    	}
    	echo "</td></tr></table></td></tr></table>
		<a name=\"Post_Comment\">
        <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        if($Guest_Allowed == 1 && $user->id() == 1){
            echo "<tr>
            <td width=\"20%\" valign=top align=right></td>
            <td width=\"80%\" align=left>"._NAME.": <br><input type=\"text\" name=\"name\"></td>
            </tr>
            <tr>
            <td width=\"20%\" valign=top align=right></td>
            <td width=\"80%\" align=left>"._EMAIL.": <br><input type=\"text\" name=\"email\"></td>
            </tr>";
        }
		if(isset($_GET['Comment_ID'])){
			if(isset($_GET['Article_ID'])){
				$comment_sql = "SELECT Comment_Author, Comment_Content FROM ".$pre."Article_Comments WHERE Comment_ID = '".$_GET['Comment_ID']."' && Story_ID = '".$_GET['Article_ID']."'";
				$PG_ID = $_GET['Article_ID'];
			}else{
				$comment_sql = "SELECT Comment_Author, Comment_Content FROM ".$pre."Poll_Comments WHERE Comment_ID = '".$_GET['Comment_ID']."' && Poll_ID = '".$_GET['poll_id']."'";
				$PG_ID = $_GET['poll_id'];
			}
			if(($get_cm = $MySQL->Fetch($comment_sql)) !== false){
				$ca = explode(">", $get_cm['Comment_Author']);
				$cb = explode("<", $ca[1]);
				$cauthor = $cb[0];
				if(($user->name() == $cauthor) || ($user->lvl() >= 3)){
					$comment_content = $get_cm['Comment_Content'];
					$update = "
					<input name=\"update_comment\" type=\"hidden\" value=\"1\">
					<input name=\"Comment_ID\" type=\"hidden\" value=\"".$_GET['Comment_ID']."\">
					<input name=\"PG_ID\" type=\"hidden\" value=\"".$PG_ID."\">
					";
				}
			}
		}
        echo "<tr>
        <td width=\"20%\"valign=top align=right></td>
        <td width=\"80%\" align=left>"._COMMENT.": <br><textarea name=\"Comment_Content\" cols=\"55\" rows=\"5\">".$_GET['Comment_Content'].$comment_content."</textarea>
        <br>".$update."<br>
        <input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>
        </tr>
        </table>
        </td></tr>
        <tr>
        <td>&nbsp;</td>
        </tr>
        </table>
        </form>";
	}elseif($Guest_Allowed == 0){
		echo _GUESTS_CANT_POST;
	}
}
?>
