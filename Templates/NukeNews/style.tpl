<?=_HEADER ?>
<br>
<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" bgcolor="#ffffff">
<tr>
<td width="50%" valign=top bgcolor="#ffffff">
<img height="16" alt="" hspace="0" src="Templates/NukeNews/images/corner-top-left.gif" width="17" align="left">
<a href="index.php"><h1>$array[Site_Title]</h1>
</a> &quot;$array[Site_Slogan]&quot;</td>
<td width="0%" bgcolor="#999999"><IMG src="Templates/NukeNews/images/pixel.gif" width="1" height="1" alt="" border="0" hspace="0"></td>
<td width="40%" align="center" bgcolor="#cfcfbb">
<form action="index.php" method="get">
<input type="hidden" name="find" value="News">
<input type="hidden" name="file" value="View_Articles">
<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="55%" align="right"><input type="text" name="search" maxlength="50"></td>
    <td width="45%" align="left"><input type="submit" name="Submit" value="Search"></td>
  </tr>
</table>
</form>
</td>
<td width="10%" valign="top" bgcolor="#cfcfbb"><img height="17" alt="" hspace="0" src="Templates/NukeNews/images/corner-top-right.gif" width="17" align="right"></td>
</tr></table>
<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" bgcolor="#fefefe">
<tr>
<td bgcolor="#000000" colspan="4"><IMG src="Templates/NukeNews/images/pixel.gif" width="1" height=1 alt="" border="0" hspace="0"></td>
</tr>
<tr valign="middle" bgcolor="#dedebb">
<td width="15%" nowrap><font class="content" color="#363636">
<b>$array[Welcome]</b></font></td>
<td align="center" height="20" width="70%"><font class="content"><B>
<A href="index.php">Home</a>
&nbsp;&middot;&nbsp;
<A href="?find=Members&file=Register">Register</a>
&nbsp;&middot;&nbsp;
<A href="?find=Members">Members</a></B></font></td>
<td align="right" width="15%"><font class="content"><b>
<script type="text/javascript">
<!--   // Array ofmonth Names
var monthNames = new Array( "January","February","March","April","May","June","July","August","September","October","November","December");
var now = new Date();
thisYear = now.getYear();
if(thisYear < 1900) {thisYear += 1900}; // corrections if Y2K display problem
document.write(monthNames[now.getMonth()] + " " + now.getDate() + ", " + thisYear);
// -->
</script></b></font></td>
<td>&nbsp;</td>
</tr>
<tr>
<td bgcolor="#000000" colspan="4"><IMG src="Templates/NukeNews/images/pixel.gif" width="1" height="1" alt="" border="0" hspace="0"></td>
</tr>
</table>
<!-- FIN DEL TITULO -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" align="center"><tr valign="top">
<td bgcolor="#ffffff">&nbsp;</td>
</tr></table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" align="center"><tr valign="top">
<td width="100" bgcolor="#ffffff" align=left><img src="Templates/NukeNews/images/pixel.gif" width="10" height="1" border="0" alt=""><table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td>$array[LeftNav]</td>
  </tr>
</table>
</td>
<td bgcolor="#ffffff" width="719" valign="top" align=center><table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td align=center><table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">$array[PageContent]</td>
  </tr>
</table>
</td>
  </tr>
</table>
  
</td>
<td width="129" bgcolor="#ffffff" align=right><img src="Templates/NukeNews/images/pixel.gif" width=10 height=1 border=0 alt=""><table width="100%"  border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td>$array[RightNav]</td>
  </tr>
</table>

</td></tr></table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" align="center"><tr valign="top">
<td align="center" height="17">
<IMG height="17" alt="" hspace="0" src="Templates/NukeNews/images/corner-bottom-left.gif" width="17" align="left">
<IMG height="17" alt="" hspace="0" src="Templates/NukeNews/images/corner-bottom-right.gif" width="17" align="right">
</td></tr></table>
<br>
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" align="center"><tr valign="top">
<td><IMG height="17" alt="" hspace="0" src="Templates/NukeNews/images/corner-top-left.gif" width="17" align="left"></td>
<td width="100%">&nbsp;</td>
<td><IMG height="17" alt="" hspace="0" src="Templates/NukeNews/images/corner-top-right.gif" width="17" align="right"></td>
</tr><tr align="center">
<td width="100%" colspan="3">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="48%" align=center valign=middle>
$array[Footer1]
</td> 
<td width="5%" align=center>.<br>.<br>.</td>
<td width="48%" align=center valign=middle>
$array[Footer2]
</td>
  </tr>
</table>$array[Footer3]</td>
</tr><tr>
<td><IMG height="17" alt="" hspace="0" src="Templates/NukeNews/images/corner-bottom-left.gif" width="17" align="left"></td>
<td width="100%">&nbsp;</td>
<td><IMG height="17" alt="" hspace="0" src="Templates/NukeNews/images/corner-bottom-right.gif" width="17" align="right"></td>
</tr></table>
<center>
  <font color="#cfcfbb">$array[Footer4]</font>
</center>
