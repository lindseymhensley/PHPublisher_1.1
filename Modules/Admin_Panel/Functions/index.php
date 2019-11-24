<? 
if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

$Table->Open("<strong>"._WELCOME.", ".$user->name()."</strong>!");
echo _MAKE_YOUR_SELECTION;
$Table->Close(); 
?>