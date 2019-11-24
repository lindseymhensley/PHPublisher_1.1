<?php

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if(isset($_GET['poll_id']) && !is_numeric($_GET['poll_id'])){
	header("Location: ".$base_url);
	die();
}elseif(isset($_GET['poll_id']) && is_numeric($_GET['poll_id'])){
	$check_true = $MySQL->Rows("SELECT poll_id FROM ".$pre."Polls WHERE poll_id = '".$_GET['poll_id']."'");
	if($check_true <= 0){
		header("Location: ".$base_url);
		die();
	}
}

include_once($Current_Directory."/Modules/Polls/class.php");

$Poll = new polls($MySQL, $pre, $Table, $Current_Directory, $user, $Emoticon_On, $base_url, $Guest_Allowed, $date, $Censor_Words, $BBcode_On, $Show_Polls);

if(isset($show_polls)){
	$Table->Open();
		echo "<br>";
		$Poll->show_polls();
	$Table->Close();
}elseif(isset($show_poll)){
	if(isset($_GET['poll_id']) && is_numeric($_GET['poll_id'])){
		if($_COOKIE['VOTED_ON'] != $_GET['poll_id']){
			$Table->Open();
				$Poll->active($_GET['poll_id']);
			$Table->Close();		
		}else{
			$Poll->results();
		}
	}else{
		header("Location: ".$base_url);
		die();
	}
}elseif(isset($show_results)){
		$Poll->results();
}else{
	if(isset($included_inblock)){
		if(isset($_GET['Vote'])){
			if($user->lvl() != 99){
				if($_COOKIE['VOTED_ON'] != $_GET['poll_id']){
					$Poll->vote();
					header("Location: ".$base_url."/index.php?find=Polls&voted=1&poll_id=".$_GET['poll_id']);
					die();
				}else{
					header("Location: ".$base_url."/index.php?find=Polls&voted=1&poll_id=".$_GET['poll_id']);
					die();
				}
			}else{
				$Poll->vote();
				header("Location: ".$base_url."/index.php?find=Polls&voted=1&poll_id=".$_GET['poll_id']);
				die();
			}
		}elseif(isset($_GET['voted'])){
			$Poll->results();
		}else{
			if($user->lvl() != 99){
				if($_COOKIE['VOTED_ON'] != $Poll->poll_id()){
					$Poll->active();
				}else{
					echo _VOTED_ALREADY;
				}
			}else{
				$Poll->active();
			}
		}
	}elseif(isset($_GET['Vote'])){
		if(isset($_POST['Choice'])){
			if(is_numeric($_POST['Choice'])){
				if($_GET['poll_id'] != $_COOKIE['VOTED_ON']){
					$Poll->vote();
				}
			}
		}elseif(isset($_POST['check_box'])){
			if($_GET['poll_id'] != $_COOKIE['VOTED_ON']){
				$Poll->vote();
			}
		}
		header("Location: ".$base_url."/index.php?find=Polls&voted=1&poll_id=".$_GET['poll_id']);
		die();
	}elseif(isset($_GET['voted'])){
		$Poll->results();
	}else{
		header("Location: ".$base_url."/index.php?find=Polls&show_polls=1");
		die();
	}
}

?>
