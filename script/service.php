<?php

require_once("../new_db.php"); /* tietokanta */

/*
    MIT Marcos Raudkett © 2017
    https://marcosraudkett.com
*/

//function send_contact_email($key, $email) {

	//valitaan JSON jotta voidaan kommunikoida functionController.js ja tämän scriptin välillä ja lähetettää esimerikiksi onko scripti läpäissy ja myös error tekstejä takaisin käyttäjälle AJAX:in kautta näin: functionController.js -> contact.html
    //header('Content-type: application/json');

    //voidaan valita php aikamuoto (ei pakollista)
    date_default_timezone_set('Europe/Helsinki');
    setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish'));

     /* modes: 
     sandbox = testausta varten (ei lähetä sähköpostia)
     build   = build (live) (lähettää sähköpostin)

     */

    $timestamp = $_GET['q']; // pakollinen
    $dropbox = 'https://dropbox.com/'.$_GET["dropbox"];

    
    $app_key = $_GET["app_key"];

    $img = $_GET["img"];
      

    /* 
      @README
      configuration kohdasta voi muuttaa joko $mode = 'build'; tai $mode = 'sandbox';
      build -> lähettää sähköpostin
      sandbox -> ei lähetä sähköpostia (skriptin testausta varten)
    */


     //CONFIGURATION
      $mode          = 'build'; //tähän voit valita joko sandbox tai build
      //$send_mail   = 'true'; //ei käytössä tässä scriptissä
      $sandbox_email = 'info@marcosraudkett.com';
      $build_email   = 'info@marcosraudkett.com';
      $company_name   = 'Timestamp';
     //CONFIGURATION END



    //sandbox moodi
    if($mode == 'sandbox'){
      $email_to = $timestamp; //kenelle viesti menee
      $email_from = "labs@organizedchaos.iot"; //keneltä viesti tulee
    }

    //build moodi
    if($mode == 'build'){
      $email_to = $timestamp; //kenelle viesti menee
      $email_from = "labs@organizedchaos.iot"; //keneltä viesti tulee
    }
  
    $todaywithouttime = ucwords(strftime("%B")); date_default_timezone_set('Europe/Helsinki');
    $day = ucwords(strftime("%A")); date_default_timezone_set('Europe/Helsinki');
    $added_date =  strftime("%d.%m.%Y"); setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish')); date_default_timezone_set('Europe/Tallinn');
    $added_time =  date("H:i:s"); setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish')); date_default_timezone_set('Europe/Tallinn');

    /* CURRENT TIME */
    $postdate = date("Y-m-d H:i:s", time());

    $dateplustime = $added_date. ' ' .$added_time;

    //viestin otsikko (subject)   
    $email_subject = "Liikettä Havaittu! ".$dateplustime;
     
 
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }

    
if($mode == 'sandbox') {	
	//jos on sandbox mode päällä niin sähköpostia ei lähetetä
  $insert = mysqli_query($conn, "INSERT INTO notifications (notification_appkey, notification_img, notification_time) VALUES ('$app_key', '$img', '$postdate')");

} else {
    $select_settings = mysqli_query($conn, "SELECT * FROM settings WHERE setting_appkey = '$app_key'");
    while($row = mysqli_fetch_assoc($select_settings)) {
      $setting_time = $row["setting_time"];
      $setting_stream = $row["setting_stream"];
      $setting_mode = $row["setting_mode"];
      $setting_other = $row["setting_other"];
    }

    $current_time = date("H:i", time());
    if($setting_time == '1') {
      /* kellonajat 08:00 ja 21:00 */
      $time1 = strftime('08:00'); date_default_timezone_set('Europe/Helsinki');
      $time2 = strftime('21:00'); date_default_timezone_set('Europe/Helsinki');
    }
    if($setting_time == '2') {
      /* kellonajat 21:00 ja 08:00 */
      $time1 = strftime('21:00'); date_default_timezone_set('Europe/Helsinki');
      $time2 = strftime('08:00'); date_default_timezone_set('Europe/Helsinki');
    }
    if($setting_time == '3') {
      /* kellonajat 00:00 ja 05:00 */
      $time1 = strftime('00:00'); date_default_timezone_set('Europe/Helsinki');
      $time2 = strftime('05:00'); date_default_timezone_set('Europe/Helsinki');
    }
    if($setting_time == '') {
      $time1 = 0;
      $time2 = 1;
    }

      if($setting_mode == 'false' || $setting_time == 'e') {
        $response_array['status'] = 'fail'; 
        $response_array['message'] = 'Offline Tilassa.';
      } else {
        /* tarkistetaan ensikis kellonajat */
        if($current_time > $time1 && $current_time < $time2 || $setting_time == '') {
          //sähköpostin viesti
          $email_top .= 'Liikettä havaittu! <br><br>Aika: <b>'.$day.', '.$dateplustime.'</b>';

          if($setting_stream == '') {
            $email_message .= '';
          } else {
            $email_message .= '<br>Linkki striimiin: '.$setting_stream;
          }

          $email_message = '<br> Kuva: <a href="https://marcosraudkett.com/mvrclabs/code/scripts/admin/uploads/'.$img.'">Katso</a>';

          if($setting_other == '') {
            $email_message .= '';
          } else {
            $email_message .= '<br> Viesti: '.$setting_other;
          }

          $email_message .= '<br><br><br>
          <a target="_blank" href="https://marcosraudkett.com/mvrclabs/code/scripts/admin/signin.php" style="
              padding: 10px;
              background: #1abc9c;
              /* margin-bottom: 10px; */
              text-decoration: none;
              color: white;
          ">Hallitse Ilmoituksia</a>
          <br><br>
          Älä vastaa tähän sähköpostiin.';

          $message_final = $email_top.$email_message;
          $insert = mysqli_query($conn, "INSERT INTO notifications (notification_appkey, notification_img, notification_time) VALUES ('$app_key', '$img', '$postdate')");

          $select_users = mysqli_query($conn, "SELECT * FROM mailer WHERE mailer_appkey = '$app_key'");
          while($row = mysqli_fetch_assoc($select_users)) {
        	 //sähköposti asetukset ja lähetys
            $email_to = $row["mailer_mail"];
            $headers = 'From: Organized Chaos <'.$email_from.">\r\n".
            'Reply-To: '.$email_from."\r\n" .
            'Content-type: text/html; charset=utf-8'."\r\n" .
            'X-Mailer: PHP/' . phpversion();
            @mail($email_to, $email_subject, $message_final, $headers); 
            $response_array['status'] = 'success'; 
            $response_array['message'] = 'Ilmoitus lähetetty!';
          }
        } else {
          $response_array['status'] = 'fail'; 
          $response_array['message'] = 'Tämä aika ei ole sallittu.';
        }
      } 
        
      

    //tähän voidaan lisätä toinen sähköpostin lähetys joka lähettää ylläpitäjälle myös viestin.
}

    //kun sähköposti on onnistuneesti lähetetty (lähettää viestin käyttäjälle että kiitos yhteydenotosta)
	

//print the status message to array which will be printed to the user via AJAX request.
//echo json_encode($response_array); 


//}
?>

<script>
window.close();
  </script>