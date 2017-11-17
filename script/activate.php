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

/* AIKA NYT */
date_default_timezone_set('Europe/Helsinki');
$postdate = date("Y-m-d H:i:s", time());

/* haetaan sivustolta status käyttämällä $_GET menetelmää */
$activate_html = htmlspecialchars(isset($_GET["status"]) ? $_GET["status"] : "");
$activate = str_replace("'", "&#39;", $activate_html);

/* jos status/activate on 1 */
if($activate == '1') {
	$insert = mysql_query("UPDATE settings SET setting_mode = 'false' WHERE setting_appkey = '$app_key'");
} else {
	$insert = mysql_query("UPDATE settings SET setting_mode = 'true' WHERE setting_appkey = '$app_key'");
}

/* palatetaan käyttäjä takaisin sivulle tähän voisi helposti lisätä $_SERVER["referrer"] joka veisi takaisi sivulle josta se tuli
mutta se hävittäis linkin lopusta #tab3_ */
header("Location: https://marcosraudkett.com/mvrclabs/code/scripts/admin/manage.php?activated=".$activate."#tab3_");

            } 
        } 
    } else {           
 header("Location: ../signin"); /* JOS KÄYTTÄJÄ EI OLE KIRJAUTUNUT SISÄÄN NIIN SE PALAUTETAAN KIRJAUTUMISIVULLE */
 } 



 ?> 