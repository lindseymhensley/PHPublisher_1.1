<?php
	$datekey = date("F j");
	$rcode = hexdec(md5($HTTP_USER_AGENT . $_GET['rand'] . $datekey));
	$code = substr($rcode, 2, 6);
	$base_url = $_GET['base_url'];
	$image = ImageCreateFromJPEG($base_url."/images/code_bg.jpg");
	$text_color = ImageColorAllocate($image, 80, 80, 80);	
	Header("Content-type: image/jpeg");
	ImageString ($image, 5, 12, 2, $code, $text_color);
	ImageJPEG($image, '', 75);
	ImageDestroy($image);
?>
