<?php
header("Content-type: text/css");
?>
/* Template -> Tech */

/* Elements */
a:link {
	color:#333333;
	text-decoration:none;
}
a:visited {
	color:#333333;
	text-decoration:none;
}
a:hover {
	color:#FFFFFF;
	text-decoration:none;
}
body {
	background-attachment:fixed;
	background-image:url(<?=$_GET['base_url'] ?>/Templates/Tech/images/bg.gif);
	background-color:#C0C0C0;
	color:#000000;
	font-family:Tahoma, Arial, serif;
	margin: 5px;
}
input {
	background-color:#C0C0C0;
	border: 1px solid #000000;
	color:#000000;
	font-family:Tahoma, Arial, serif;
	font-size:10px;
	margin: 2px;
}
table {
	font-size:10px;
}
td {
	vertical-align:top;
}
textarea {
	background-color:#C0C0C0;
	border: 1px solid #000000;
	color:#000000;
	font-family:Tahoma, Arial, serif;
	font-size:10px;
	margin: 2px;
}

select {
	background-color:#C0C0C0;
	border: 1px solid #000000;
	color:#000000;
	font-family:Tahoma, Arial, serif;
	font-size:10px;
	margin: 2px;
}

/* Classes */
.banner {
	background-attachment:fixed;
	background-color:#657ba0;
	background-image:url(<?=$_GET['base_url'] ?>/Templates/Tech/images/banner.jpg);
	background-repeat:no-repeat;
	border-left:1px solid #000000;
	border-right:1px solid #000000;
	border-top:1px solid #000000;
	font-size:18px;
	height:45px;
	padding-left:10px;
	vertical-align:middle;
}
.block {
	background-color:#C0C0C0;
	border:1px solid #000000;
	font-size:10px;
	margin:0px 0px 10px 0px;
	padding:1px;
}
.cell {
	background-color:#C0C0C0;
	padding:2px;
}
.style {
	border:1px solid #000000;
}
.title {
	background-attachment:fixed;
	background-color:#CCCCCC;
	background-image:url(<?=$_GET['base_url'] ?>/Templates/Tech/images/arrow.gif);
	background-repeat:no-repeat;
	border-bottom:1px solid #000000;
	font-weight:bold;
	height:10px;
	margin-bottom:2px;
	padding-bottom:2px;
	text-align:center;
	vertical-align:middle;
}
.newstitle {
	background-attachment:fixed;
	background-color:#CCCCCC;
	background-image:url(<?=$_GET['base_url'] ?>/Templates/Tech/images/arrow.gif);
	background-repeat:no-repeat;
	border-bottom:1px solid #000000;
	font-weight:bold;
	height:10px;
	margin-bottom:2px;
	padding-bottom:2px;
	text-align:left;
	vertical-align:middle;
}
.welcome {
	background-color:C0C0C0;
	font-size:10px;
	font-weight:bold;
	padding:2px;
	text-align:right;
}
.table {
	border: 1px solid #CCCCCC;
}