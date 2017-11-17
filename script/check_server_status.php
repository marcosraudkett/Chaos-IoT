<?php 
 require_once("../db.php"); /* tietokanta */

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



//$app_key = $_GET["app_key"];
$app_key = $_POST["app_key"];

/* timezone Europe/Helsinki */
date_default_timezone_set('Europe/Helsinki');
/* aika nyt */
$postdate = date("Y-m-d H:i:s", time());

if($app_key == '') {
	echo 'Organized Chaos 2017'; /* jos app key on tyhjä niin näytetään joku viesti käyttäjälle? */
} else {

	//require_once("../db.php"); //tietokanta mutta ilmoitettu jo ylhäällä

	$find = mysql_query("SELECT setting_status, setting_ip FROM settings WHERE setting_appkey = '$app_key'"); /* hakee uusimpia ilmoituksia */
	$find_rows = mysql_num_rows($find); /* hakee rivien määrät */

	while($row = mysql_fetch_assoc($find)) {
		$status = $row["setting_status"];
		if($_POST["debug"] == true) {
			$time = strftime('%Y-%m-%d %H:%M:%S', strtotime($row["setting_status"]. '+7 days')); date_default_timezone_set('Europe/Helsinki');
			$ip = $row["setting_ip"];
		} else {
			$time = strftime('%Y-%m-%d %H:%M:%S', strtotime($row["setting_status"]. '+12 seconds')); date_default_timezone_set('Europe/Helsinki');
			$ip = $row["setting_ip"];
		}
		$ip = $row["setting_ip"];
		//$img = $row["notification_img"];
	}


	if($postdate <= $time) {
		//$update = mysql_query("UPDATE commander SET command_read = '1' WHERE command_appkey = '$app_key' AND command_id = '$id'");
		$response_array['oc_status'] = 'success';
		$response_array['oc_ip_address'] = $ip;
		$response_array['oc_app_key'] = $app_key;
	} else {
		$response_array['oc_status'] = 'error';
	}
echo json_encode($response_array); 

}





            } 



        } 



        } 



 else 



 



 //if the cookie does not exist, they are taken to the login screen 



 {           



$response_array['oc_status'] = 'offline';
echo json_encode($response_array); 


 } 



 ?> 