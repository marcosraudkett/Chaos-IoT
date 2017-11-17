<?php

header('Content-type: application/json'); /* JSON muoto jotta voitaan keskustella javascriptin kanssa. */

//$app_key = $_GET["app_key"];
$app_key = $_POST["app_key"];
$id = $_POST["id"];

if($app_key == '') {
	echo 'Organized Chaos 2017'; /* jos app key on tyhjä niin näytetään joku viesti käyttäjälle? */
} else {

	require_once("../db.php"); /* tietokanta */

	/* query */
	$find = mysql_query("SELECT * FROM commander WHERE command_id = '$id' AND command_appkey = '$app_key'"); /* hakee uusimpia ilmoituksia */
	$find_rows = mysql_num_rows($find); /* hakee rivien määrät */

	while($row = mysql_fetch_assoc($find)) {
		/* haetaan tietokannasta : commander -> command_id, command_do, command_ip, command_read, command_response */
		$id = $row["command_id"];
		$do = $row["command_do"];
		$ip = $row["command_ip"];
		$read = $row["command_read"];
		$response = $row["command_response"];
		//$img = $row["notification_img"];
	}

	if($read == '2') {
	/* jos read on 2 */
		if($response == '') {
			/* jos read on tyhjä */
			$response_array['oc_status'] = 'success';
			$response_array['oc_id'] = $id;
			$response_array['oc_ip'] = $ip;
			//$response_array['oc_response'] = $response;
		} else {
			/* jos read ei ole tyhjä */
			$response_array['oc_status'] = 'success';
			$response_array['oc_id'] = $id;
			$response_array['oc_ip'] = $ip;
			$response_array['oc_response'] = $response;
		}
	} else {
		$response_array['oc_status'] = 'error';
		$response_array['oc_id'] = $id;
		$response_array['oc_ip'] = $ip;
	}

	echo json_encode($response_array); /* tulostetaan $response_array -> JSON */
}

?> 