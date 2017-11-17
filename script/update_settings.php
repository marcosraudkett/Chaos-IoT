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


/* timezone Europe/Helsinki */
date_default_timezone_set('Europe/Helsinki');
/* aika nyt */
$postdate = date("Y-m-d H:i:s", time());


$amount_html = htmlspecialchars(isset($_POST["amount"]) ? $_POST["amount"] : ""); /* määrä ilmoituksia etusivulla */
$amount = str_replace("'", "&#39;", $amount_html);

$time_html = htmlspecialchars(isset($_POST["time"]) ? $_POST["time"] : ""); /* kellonajan rajaus */
$time = str_replace("'", "&#39;", $time_html);

$stream_html = htmlspecialchars(isset($_POST["link_to_stream"]) ? $_POST["link_to_stream"] : ""); /* striimin linkki */
$stream = str_replace("'", "&#39;", $stream_html);

$message_html = htmlspecialchars(isset($_POST["custom_message"]) ? $_POST["custom_message"] : ""); /* custom viesti */
$message = str_replace("'", "&#39;", $message_html);


$insert = mysql_query("UPDATE settings SET setting_amount = '$amount', setting_time = '$time', setting_stream = '$stream', setting_other = '$message' WHERE setting_appkey = '$app_key'");


header("Location: https://marcosraudkett.com/mvrclabs/code/scripts/admin/manage.php?saved=true#tab3_");

            } 
        } 
    } else {           
        header("Location: ../signin"); 
    } 

 ?> 