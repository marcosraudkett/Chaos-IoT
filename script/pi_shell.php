<?php

//$app_key = $_GET["app_key"];
$app_key = $_GET["app_key"];
$pi_ip = $_SERVER['REMOTE_ADDR']; /* käyttäjän ip osoite */
$response = $_GET["response"];

date_default_timezone_set('Europe/Helsinki');
/* timezone Europe/Helsinki */
date_default_timezone_set('Europe/Helsinki');
/* aika nyt */
$postdate = date("Y-m-d H:i:s", time());

if($app_key == '') {
	echo 'Missing application key!';
} else {

	require_once("../db.php"); //database

	$find = mysql_query("SELECT * FROM commander WHERE command_appkey = '$app_key' AND command_read = '0'"); /* hakee uusimpia ilmoituksia */
	$find_rows = mysql_num_rows($find); /* hakee rivien määrät */
	
	$server_status = mysql_query("UPDATE settings SET setting_status = '$postdate', setting_ip = '$pi_ip' WHERE setting_appkey = '$app_key'");

	while($row = mysql_fetch_assoc($find)) {
		$id = $row["command_id"];
		$do = $row["command_do"];
		//$img = $row["notification_img"];
	}

	if($do == null) {

	} else {
		if($response == '') {
			$update = mysql_query("UPDATE commander SET command_read = '1', command_ip = '$pi_ip' WHERE command_appkey = '$app_key' AND command_read = '0'");
		} else {
			$update = mysql_query("UPDATE commander SET command_response = '$response', command_read = '1', command_ip = '$pi_ip' WHERE command_appkey = '$app_key' AND command_do = '$do' AND command_read = '0'");
		}
		echo $do;
	}
}

?>