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

header('Content-type: application/json'); /* JSON muoto jotta voitaan keskustella javascriptin kanssa. */

/* locale */
setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish'));
/* timezone Europe/Helsinki */
date_default_timezone_set('Europe/Helsinki');
/* aika nyt */
$postdate = date("Y-m-d H:i:s", time());


$do = $_POST["do"]; /* sivulta lähetetään viimeisen ilmoituksen id */

	if($do == 'oc clear history') {
		$delete = mysql_query("DELETE FROM commander WHERE command_appkey = '$app_key'");
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$response_array['oc_id'] = $id;
	} else {

	if($do == 'oc show history') {
		$history = mysql_query("SELECT * FROM commander WHERE command_appkey = '$app_key'");
		
		while($row = mysql_fetch_assoc($history)) {
			$history_id = $row["command_id"];
			$history_do = mysql_num_rows($history);
			//$img = $row["notification_img"];
		}

		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		if($history_do == null) {
			$response_array['oc_msg'] = '<br><font color="red">0</font> previous commands.<br> type "help" for more.';
			
		} else {
			$response_array['oc_msg'] = '<br><font color="red">'.$history_do.'</font> previous commands.<br> type "help" for more.';
		}

	} else {

	if($do == 'whoami') { /* komento whoami */
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$response_array['oc_msg'] = '> I am Terminal, manufactured by Organized Chaos in Lahti Finland.';
	} else {

	if (strpos($do, 'print') !== false) { /* komento print */
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$last = str_replace("echo","",$do);
		//str_replace("ll", "", "good golly miss molly!", $count);
		$response_array['oc_msg'] = '> '.$last;
	} else {

	if (strpos($do, 'how are you') !== false) { /* komento how are you */
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$response_array['oc_msg'] = '> I am doing well, thank you for asking.';
	} else {

	if (strpos($do, 'hello') !== false || strpos($do, 'hey') !== false ) { /* komento hello tai hey */
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$response_array['oc_msg'] = '> Hey there.';
	} else {

	if (strpos($do, 'open') !== false) { /* komento open */
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$navto = str_replace("open", "", $do);
		$navto_real = str_replace(" ", "", $navto);
		$parsed = parse_url($navto_real);
		if (empty($parsed['scheme'])) {
		    $link = 'http://' . ltrim($navto_real, '/');
		}
		$response_array['oc_msg'] = '> opening '.$link."<script>window.open('".$link."')</script>";
	} else {

	if (strpos($do, 'ip') !== false) { /* komento ip */
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$navto = str_replace("ip", "", $do);
		$navto_real = str_replace(" ", "", $navto);
		$parsed = parse_url($navto_real);
		if (empty($parsed['scheme'])) {
		    $link = ltrim($navto_real);
		}
		$ip = gethostbyname($link);
		$response_array['oc_msg'] = '> Received packets from: '.$ip;
	} else {

	if (strpos($do, 'help') !== false) { /* komento help */
		$response_array['oc_status'] = 'success';
		$response_array['oc_msg'] = '> You could start using following commands:
		<br> -"<font color="orange">oc clear history</font>" (clears previous command history + fixes if bugged)
		<br> -"<font color="orange">oc show history</font>" (shows if you have any command history, for safety reasons we can only show the amount)
		<br> -"<font color="orange">ip google.com</font>" (gets the website google.com ip address)
		<br> -"<font color="orange">open google.com</font>" (opens a new tab and navigates to google.com)';
	} else {

	$insert = mysql_query("INSERT INTO commander (command_appkey, command_do, command_read) VALUES ('$app_key', '$do', '0')");
	//$update = mysql_query("INSERT commander SET command_do = '$do' WHERE command_appkey = '$app_key'"); /* hakee uusimpia ilmoituksia */
	$find = mysql_query("SELECT * FROM commander WHERE command_appkey = '$app_key' ORDER BY command_id"); /* hakee uusimpia ilmoituksia */
	$find_rows = mysql_num_rows($find); /* hakee rivien määrät */


	while($row = mysql_fetch_assoc($find)) {
		$id = $row["command_id"];
		$do = $row["command_do"];
		//$img = $row["notification_img"];
	}

	$timestamp_new = ucwords(strftime('%d.%m.%Y, %A klo %H:%M:%S', strtotime($timestamp)));

	if($find_rows == null) {
		/* EI LÖYTYNYT */
		$response_array['oc_status'] = 'error';
		$response_array['oc_do'] = $do;
		$response_array['oc_id'] = $id;
		$response_array['oc_msg'] = '';
	} else {
		/* LÖYTYI UUSIA ILMOITUKSIA */
		$response_array['oc_status'] = 'success';
		$response_array['oc_do'] = $do;
		$response_array['oc_id'] = $id;
		$response_array['oc_msg'] = '';
	}
								}
							}
						}
					}
				}
			}
		}
	}
}
echo json_encode($response_array);  /* palautetaan tulokset javascriptiin joka sitten tulostaa käyttäjälle */
            } 
        } 
    } else {           
 //header("Location: ../signin.php"); 
 } 



 ?> 