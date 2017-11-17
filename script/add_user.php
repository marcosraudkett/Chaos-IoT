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

/* tarkistetaan ettei datassa ole mitään special characterei eli esimerkiksi html:llää: <a href="">  */
$mail_html = htmlspecialchars(isset($_POST["user"]) ? $_POST["user"] : "");
/* tämä on myös sql injection varten ja muutetaan ' merkki -> &#39; mikä tulostaa sivulle tai näyttää käyttäjälle samalta kun
' mutta ei kuitenkaan ole (teksi) */
$mail = str_replace("'", "&#39;", $mail_html);

if($mail == '') {
    /* jos sähköposti on tyhjä */
} else {
	$insert = mysql_query("INSERT INTO mailer (mailer_appkey, mailer_mail, mailer_created) VALUES ('$app_key', '$mail', '$postdate')");
}

/* palatetaan käyttäjä takaisin sivulle tähän voisi helposti lisätä $_SERVER["referrer"] joka veisi takaisi sivulle josta se tuli
mutta se hävittäis linkin lopusta #tab2_ */
header("Location: https://marcosraudkett.com/mvrclabs/code/scripts/admin/manage.php?created=true#tab2_");

            } 
        } 
    } else {           
 header("Location: ../signin"); /* JOS KÄYTTÄJÄ EI OLE KIRJAUTUNUT SISÄÄN NIIN SE PALAUTETAAN KIRJAUTUMISIVULLE */
 } 



 ?> 