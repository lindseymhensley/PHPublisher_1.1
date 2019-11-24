<?php
/*******************************************************************
 **
 ** Block: site-stats.php
 ** Description: Block that displays yesterdays traffic, todays
 ** traffic, the most traffic recieved in a day, and the total
 ** amount of page views since the site started.
 **                                                  
 *******************************************************************/ 

if(!defined("IN_DB")){
	die("Hacking Attempt!");
}

if(empty($_SESSION['stats']['traffic'])){
    $dot = date("d-m-Y-H");
    $now = explode ("-",$dot);
    $Hour = $now[3];
    $Year = $now[2];
    $Month = $now[1];
    $Day = $now[0];
    $Yesterday = $Day - 1;
	if($Yesterday <= 0){
		$Month = $Month - 1;
		if($Month <= 0){
			$Month = 12;
			$Yesterday = 31;
			$Year = $Year - 1;			
		}else{
			if ($Month == 1) $Yesterday = 31;
			if ($Month == 2) {
				if (date("L") == true) {
					$Yesterday = 29;
				} else {
					$Yesterday = 28;
				}
			}
			if ($Month == 3) $Yesterday = 31;
			if ($Month == 4) $Yesterday = 30;
			if ($Month == 5) $Yesterday = 31;
			if ($Month == 6) $Yesterday = 30;
			if ($Month == 7) $Yesterday = 31;
			if ($Month == 8) $Yesterday = 31;
			if ($Month == 9) $Yesterday = 30;
			if ($Month == 10) $Yesterday = 31;
			if ($Month == 11) $Yesterday = 30;
			if ($Month == 12) $Yesterday = 31;
		}
	}
	
	$total_yesterday = $MySQL->Query("SELECT hits FROM ".$pre."stats_today WHERE (year = '".$Year."') AND (month = '".$Month."') AND (date = '".$Yesterday."')");
    while($hits = mysql_fetch_array($total_yesterday)){
        $yhits += $hits['hits'];
    }
    $total_today = $MySQL->Query("SELECT hits FROM ".$pre."stats_today WHERE (year = '".$Year."') AND (month = '".date('m')."') AND (date = '".$Day."')");
	while($hits = mysql_fetch_array($total_today)){
        $t_hits += $hits['hits'];
    }
    $thits = $MySQL->Fetch("SELECT count FROM ".$pre."counter WHERE (var = 'hits') AND (type = 'total')");
	$record = $MySQL->Fetch("SELECT count FROM ".$pre."counter WHERE (var = 'hits') AND (type = 'record')");
    $_SESSION['stats']['traffic']['total_hits'] = $thits['count'];
    $_SESSION['stats']['traffic']['most_ever'] = $record['count'];
	$_SESSION['stats']['favorite']['browser'] = $MySQL->Fetch("SELECT var FROM ".$pre."counter WHERE type = 'browser' ORDER BY count DESC LIMIT 1");
    $_SESSION['stats']['favorite']['os'] = $MySQL->Fetch("SELECT var FROM ".$pre."counter WHERE type = 'os' ORDER BY count DESC LIMIT 1");
	$_SESSION['stats']['traffic']['total_today'] =  $t_hits;
	$_SESSION['stats']['traffic']['total_yesterday'] = $yhits;
	
	if($today_hits > $record['count']){
		$MySQL->Query("UPDATE ".$pre."counter SET count = '".$t_hits."' WHERE (var = 'hits') AND (type = 'record')");
		$_SESSION['stats']['traffic']['most_ever'] = $t_hits;
	}
	if($yhits > $record['count']){
		$MySQL->Query("UPDATE ".$pre."counter SET count = '".$yhits."' WHERE (var = 'hits') AND (type = 'record')");
		$_SESSION['stats']['traffic']['most_ever'] = $yhits;
	}

	$idle_time = 300;
	$sql = $MySQL->Query("SELECT * FROM ".$pre."users_online");
	$users_online = 0;
	while($users = mysql_fetch_array($sql)){
		$idle = $users['time_stamp'] + $idle_time;
		if(time() >= $idle){
			mysql_query("DELETE FROM ".$pre."users_online WHERE ip = '".$users['ip']."' AND time_stamp = ".$users['time_stamp']);
		}else{
			$users_online++;
		}
	}
	$_SESSION['stats']['traffic']['members_online'] = $MySQL->Rows("SELECT ip FROM ".$pre."users_online WHERE reg = 1");
	$_SESSION['stats']['traffic']['guests_online'] = $MySQL->Rows("SELECT ip FROM ".$pre."users_online WHERE reg = 0");
	$_SESSION['stats']['traffic']['users_online'] = $users_online;	
	if(empty($_GET['no_session'])){
		header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		die();
	}
}
if($_SESSION['stats']['traffic']['users_online'] == 0){
	$_SESSION['stats']['traffic']['users_online'] = 1;
	if($user->id() != 1){
		$_SESSION['stats']['traffic']['guests_online'] = 1;
	}else{
		$_SESSION['stats']['traffic']['members_online'] = 1;
	}
}
echo "<u>Our users:</u><br />
Prefer <strong>".$_SESSION['stats']['favorite']['browser']['var']."</strong> as their browser and <strong>".$_SESSION['stats']['favorite']['os']['var']."</strong> as their operating system.
<br /><br />
<u>We currently have:</u>
<br /><strong>".number_format($_SESSION['stats']['traffic']['users_online'])."</strong> Users Online<br />
<br /><strong>".number_format($_SESSION['stats']['traffic']['members_online'])."</strong> Member(s)
<br /><strong>".number_format($_SESSION['stats']['traffic']['guests_online'])."</strong> Guest(s)
<br /><br />
<u>Page views:</u><br />
Yesterday: <strong>".number_format($_SESSION['stats']['traffic']['total_yesterday'])."</strong><br />
Today: <strong>".number_format($_SESSION['stats']['traffic']['total_today'])."</strong><br />
Record: <strong>".number_format($_SESSION['stats']['traffic']['most_ever'])."</strong><br />";
echo "Total: <strong>".number_format($_SESSION['stats']['traffic']['total_hits'])."</strong>";
?>
