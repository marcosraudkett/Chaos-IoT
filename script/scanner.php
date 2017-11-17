<?php 
 require_once("db.php"); /* tietokanta */

 if(isset($_COOKIE['ID_my_site'])){ /* tarkistetaan onko käyttyäjällä cookie ID_my_site olemassa */

        /* HUOM! Ei turvallinen tapa luoda tunnuksia tämä pitäisi vaihtaa esimerkiksi session key systeemiin 
        + salasanat hashata (lue readme.txt) ethän käytä tätä kirjautumistyyliä missään  */
        $email = $_COOKIE['ID_my_site'];  /* COOKIE["ID_my_site"] = Käyttäjänimi */
        $pass = $_COOKIE['Key_my_site'];  /* COOKIE["Key_my_site"] = Salasana */
        /* luodaan tietokanta query :: users jossa :: email on kirjautuneen käyttäjän $email */
        $check = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
        $email = mysql_real_escape_string($_POST['email']);
        $res = mysql_query("SELECT * FROM users WHERE email='$email'");

    /* haetaan tietokannasta $check */
    while($info = mysql_fetch_array( $check ))   { 
        /* jos salasana ei ole sama kuin tietokannassa niin lähetetään käyttäjä takaisin signin.php sivulle */
        if ($pass != $info['password']) {           
                header("Location: ../signin"); 
            } else { 

    /* käyttäjän email */
    $emailget = $_COOKIE[ID_my_site];
    $get = mysql_query("SELECT * FROM users WHERE email = '$emailget'");

    while ($row = mysql_fetch_assoc($get)) {
        /* luodaan käyttäjän tiedoille parametrit jotka haetaan $get query:istä */
        $firstname = $row["firstname"]; /* käyttäjän etunimi */
        $lastname = $row["lastname"]; /* käyttäjän sukunimi */
        $emailaddr = $row["email"]; /* käyttäjän sähköposti */
        $app_key = $row["app_key"]; /* käyttäjän app key */
    }


setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish'));
/* timezone Europe/Helsinki */
date_default_timezone_set('Europe/Helsinki');
/* aika nyt */
$postdate = date("Y-m-d H:i:s", time());


$left_off = $_GET["id"]; /* sivulta lähetetään viimeisen ilmoituksen id */

	$find = mysql_query("SELECT * FROM notifications WHERE notification_id > '$left_off' AND notification_appkey = '$app_key'"); /* hakee uusimpia ilmoituksia */
	$find_rows = mysql_num_rows($find); /* hakee rivien määrät */

	/* haetaan ilmoitusten tarvittavat tiedot */
	while($row = mysql_fetch_assoc($find)) {
		$id = $row["notification_id"];
		$timestamp = $row["notification_time"];
		$img = $row["notification_img"];
	}

	$timestamp_new = ucwords(strftime('%d.%m.%Y, %A klo %H:%M:%S', strtotime($timestamp)));

	if($find_rows == null) {
		/* EI LÖYTYNYT */
		$response_array['oc_status'] = 'error';
		$response_array['oc_timestamp'] = 'none';
		$response_array['oc_message'] = 'Ei uusia havaintoja.';
	} else {
		/* LÖYTYI UUSIA ILMOITUKSIA */
		$response_array['oc_status'] = 'success';
		$response_array['oc_id'] = $id;
		$response_array['oc_timestamp'] = $timestamp_new;
		$response_array['oc_amount'] = $find_rows;
		$response_array['oc_img'] = $img;
		$response_array['oc_message'] = 'Uusi havainto!';
	}


echo json_encode($response_array);  /* palautetaan tulokset javascriptiin joka sitten tulostaa käyttäjälle */
            } 
        } 
    } else {           
 header("Location: ../signin"); 
 } 



 ?> 