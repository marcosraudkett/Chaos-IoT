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
                header("Location: signin"); 
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
    date_default_timezone_set('Europe/Helsinki');


?> <!--DOCTYPE html -->


<?php 

/* 
    @README
    tämän scriptin voisi luoda paremmin sillai että tehtäis yksi function jossa haettais aina -1 päivä jatkuvasti
    eli ei tarvitsis kirjoittaa jokaista päivää niinkun tässä scriptissä on tehty.
    Jos tekis sillai niin olisi mahdollista myös lisätä sivulle asetus josta voi vaihtaa aikaväliä esimerkiksi kun tämä
    skripti hetkellä toimii VAIN 6 edellisen päivän kanssa..
*/

/* 
  haetaan 6 edellistä päivää nykyisestä päivästä mutta pelkästään: Maanantai, Tiistai, Keskiviikko ... 
  esim $secondday -1 päivä nykyisestä eli jos nyt olisi maanantai niin haettais sunnuntaita.
  ucwords() -> muuttaa ensimmäisen kirjaimen isoksi seuraavasti: maanantai -> Maanantai
*/
$secondday = ucwords(strftime( '%A', strtotime( '-1 day'))); 
$thirdday = ucwords(strftime( '%A', strtotime( '-2 day'))); 
$fourthday = ucwords(strftime( '%A', strtotime( '-3 day'))); 
$fifthday = ucwords(strftime( '%A', strtotime( '-4 day')));
$sixthday = ucwords(strftime( '%A', strtotime( '-5 day'))); 
$seventhday = ucwords(strftime( '%A', strtotime( '-6 day'))); 

/* tässä haetaan edelliset päivät d.m PV.KK <- eli esim 20.07 */
$secondday_date = strftime( '%d.%m', strtotime( '-1 day') ); 
$thirdday_date = strftime( '%d.%m', strtotime( '-2 day') ); 
$fourthday_date = strftime( '%d.%m', strtotime( '-3 day') ); 
$fifthday_date = strftime( '%d.%m', strtotime( '-4 day') ); 
$sixthday_date = strftime( '%d.%m', strtotime( '-5 day') ); 
$seventhday_date = strftime( '%d.%m', strtotime( '-6 day') ); 

/* tässä haetaan edellisten päivien päivämäärät kokonaisuudessaan PV.KK.VUOSI <- eli esim 20.07.2017 */
$secondday_fulldate = strftime( '%d.%m.%Y', strtotime( '-1 day') ); 
$thirdday_fulldate = strftime( '%d.%m.%Y', strtotime( '-2 day') ); 
$fourthday_fulldate = strftime( '%d.%m.%Y', strtotime( '-3 day') ); 
$fifthday_fulldate = strftime( '%d.%m.%Y', strtotime( '-4 day') ); 
$sixthday_fulldate = strftime( '%d.%m.%Y', strtotime( '-5 day') ); 
$seventhday_fulldate = strftime( '%d.%m.%Y', strtotime( '-6 day') ); 

/* tässä luodaan tietokannan hakua varten päivämäärät nykyisestä -1 päivä ja kellonajat 00:00:00 - 23:59:59 joilla haetaan tietokannasta
siltä väliltä ja siltä päivältä */
$secondday_mysqlfulldate_1 = strftime( '%Y-%m-%d 00:00:00', strtotime( '-1 day') ); 
$secondday_mysqlfulldate = strftime( '%Y-%m-%d 23:59:59', strtotime( '-1 day') ); 

$thirdday_mysqlfulldate_1 = strftime( '%Y-%m-%d 00:00:00', strtotime( '-2 day') ); 
$thirdday_mysqlfulldate = strftime( '%Y-%m-%d 23:59:59', strtotime( '-2 day') ); 

$fourthday_mysqlfulldate_1 = strftime( '%Y-%m-%d 00:00:00', strtotime( '-3 day') ); 
$fourthday_mysqlfulldate = strftime( '%Y-%m-%d 23:59:59', strtotime( '-3 day') ); 

$fifthday_mysqlfulldate_1 = strftime( '%Y-%m-%d 00:00:00', strtotime( '-4 day') ); 
$fifthday_mysqlfulldate = strftime( '%Y-%m-%d 23:59:59', strtotime( '-4 day') ); 

$sixthday_mysqlfulldate_1 = strftime( '%Y-%m-%d 00:00:00', strtotime( '-5 day') ); 
$sixthday_mysqlfulldate = strftime( '%Y-%m-%d 23:59:59', strtotime( '-5 day') ); 

$seventhday_mysqlfulldate_1 = strftime( '%Y-%m-%d 00:00:00', strtotime( '-6 day') ); 
$seventhday_mysqlfulldate = strftime( '%Y-%m-%d 00:00:00', strtotime( '-6 day') ); 

?>



<?php 
/* haetaan asetukset */
$get = mysql_query("SELECT * FROM settings");
while ($row = mysql_fetch_assoc($get)) {
    $amount = $row["setting_amount"];
}

/* toka päivä -1 days */
$second = mysql_query("SELECT * FROM notifications WHERE notification_appkey = '$app_key' AND notification_time BETWEEN '$secondday_mysqlfulldate_1' AND '$secondday_mysqlfulldate' ORDER BY notification_id DESC");
$second_rows = mysql_num_rows($second);

/* -2 days haku */
$third = mysql_query("SELECT * FROM notifications WHERE notification_appkey = '$app_key' AND notification_time BETWEEN '$thirdday_mysqlfulldate_1' AND '$thirdday_mysqlfulldate' ORDER BY notification_id DESC");
$third_rows = mysql_num_rows($third);
  
/* -3 days haku */
$fourth = mysql_query("SELECT * FROM notifications WHERE notification_appkey = '$app_key' AND notification_time BETWEEN '$fourthday_mysqlfulldate_1' AND '$fourthday_mysqlfulldate' ORDER BY notification_id DESC");
$fourth_rows = mysql_num_rows($fourth);

/* -4 days haku */
$fifth = mysql_query("SELECT * FROM notifications WHERE notification_appkey = '$app_key' AND notification_time BETWEEN '$fifthday_mysqlfulldate_1' AND '$fifthday_mysqlfulldate' ORDER BY notification_id DESC");
$fifth_rows = mysql_num_rows($fifth);

/* -5 days haku */
$sixth = mysql_query("SELECT * FROM notifications WHERE notification_appkey = '$app_key' AND notification_time BETWEEN '$sixthday_mysqlfulldate_1' AND '$sixthday_mysqlfulldate' ORDER BY notification_id DESC");
$sixth_rows = mysql_num_rows($sixth);

/* -6 days haku */
$seventh = mysql_query("SELECT * FROM notifications WHERE notification_appkey = '$app_key' AND notification_time BETWEEN '$seventhday_mysqlfulldate_1' AND '$seventhday_mysqlfulldate' ORDER BY notification_id DESC");
$seventh_rows = mysql_num_rows($seventh);


while ($row = mysql_fetch_assoc($get)) {
    $id = $row["notification_id"];
    $time = $row["notification_time"];
    $img = $row["notification_img"];
    
    setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish'));
    $new_time = ucwords(strftime('%d.%m.%Y, %A klo %H:%M:%S', strtotime($row["notification_time"]))); 
}
?>
<html>
  <head>
    <title>Tilastot</title>
    <meta charset="utf-8">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          /* tässä tulostetaan seuraavasti ['Maanantai (20.07)', MÄÄRÄ] */
          ['Päivä', 'Havaintoja'],
          ['<?php echo $secondday.' ('.$secondday_date.')'; ?>',  <?php echo $second_rows; ?>],
          ['<?php echo $thirdday.' ('.$thirdday_date.')'; ?>', <?php echo $third_rows; ?>],
          ['<?php echo $fourthday.' ('.$fourthday_date.')'; ?>',  <?php echo $fourth_rows; ?>],
          ['<?php echo $fifthday.' ('.$fifthday_date.')'; ?>',  <?php echo $fifth_rows; ?>],
          ['<?php echo $sixthday.' ('.$sixthday_date.')'; ?>',  <?php echo $sixth_rows; ?>],
          ['<?php echo $seventhday.' ('.$seventhday_date.')'; ?>',  <?php echo $seventh_rows; ?>]
        ]);

        var options = {
          title: 'Edellisen 7 päivän havainnot',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.charts.Line(document.getElementById('line_top_x'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <center>
      <div id="line_top_x" style="width: 1200px; height: 500px"></div>
    </center>
  </body>
</html>
<?php
          } 
        } 
      } else {  
 /* jos käyttäjä ei olekkaan kirjautunut niin se lähetetään kirjautumissivulle. */         
 header("Location: signin"); 
 } 



 ?> 