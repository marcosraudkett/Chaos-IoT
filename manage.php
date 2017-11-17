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


?> <!--DOCTYPE html -->
<html><head>
    <meta charset="utf-8">
    <title>Organized Chaos - Hallintapaneeli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="css/flat-ui.css" rel="stylesheet">
    
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-contact.css" rel="stylesheet">
    <link href="css/style-content.css" rel="stylesheet">
    <link href="css/style-footers.css" rel="stylesheet">
    <link href="css/style-headers.css" rel="stylesheet">
    <link href="css/style-portfolios.css" rel="stylesheet">
    <link href="css/style-pricing.css" rel="stylesheet">
    <link href="css/style-team.css" rel="stylesheet">
    <link href="css/style-dividers.css" rel="stylesheet">
    <link href="https://marcosraudkett.com/vivinetti/css/lightbox/lightbox.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
        <center><h2>Hallintapaneeli (<?php /* tulostetaan käyttäjän */ echo $firstname.' '.$lastname; ?>)</h2>
            <a class="btn btn-default btn-xs" href="logout.php">Kirjaudu Ulos</a>
        </center>
    
     <div id="page" class="page">
    
        <div class="item content padding-bottom-60">
            
            <div class="container">
                        
                <div class="row">
                
                    <div class="col-md-8 col-md-offset-2">
                                            
                        <ul class="nav nav-tabs nav-append-content">
                            <li id="tab1__" class="active"><a class="tab_link" href="#tab1" id="tab1_">Ilmoitukset</a></li>
                            <li id="tab2__"><a class="tab_link" href="#tab2" id="tab2_">Käyttäjät</a></li>
                            <li id="tab3__"><a class="tab_link" href="#tab3" id="tab3_">Asetukset</a></li>
                        </ul> <!-- /tabs -->
                        
                        <div class="tab-content tabs">
                            <div class="tab-pane active" id="tab1" href="#tab1">
                                
                                <div class="row">
                                    <?php
                                        if($_GET["emptied"] == 'true') {
                                            /* jos etusivulla klikataan tyhjennä nappia niin linkin loppuun lisätään ?emptied=true ja kun se löytyy linkistä niin tulostetaan käyttäjälle viesti että havainnot tyhjennetty. */
                                            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong></strong>Havainnot tyhjennetty.</div>';
                                            /* kun se on suoritettu niin poistetaan ?emptied=true linkin lopusta. */
                                            echo '<script>window.history.pushState({}, document.title, "/" + "mvrclabs/code/scripts/admin/manage.php#tab1_");</script>';
                                        }
                                    ?>


                                    <?php 
                                        /* luodaan uusi tietokanta query/haku */
                                        $get = mysql_query("SELECT * FROM settings WHERE setting_appkey = '$app_key'");
                                        /* haetaan teidto tietokannasta */
                                        while ($row = mysql_fetch_assoc($get)) {
                                            /* tässä haetaan setting_amount rivi settings nimisestä taulukosta ja jossa on sama setting_appkey kun nykyisellä käyttäjällä */
                                            $amount = $row["setting_amount"];
                                        }
                                        /* luodaan uusi tietokanta query/haku */
                                        $get = mysql_query("SELECT * FROM notifications WHERE notification_appkey = '$app_key' ORDER BY notification_id DESC LIMIT 0, $amount");
                                        /* lasketaan kaikki rivit siitä hausta */
                                        $find_rows = mysql_num_rows($get);
                                          
                                        /* jos rivejä on 0 */
                                        if($find_rows == 0) { 
                                            /* niin tyhjennä nappi piiloitetaan style="display:none;" */
                                            echo '<a href="script/empty.php" style="margin-bottom: 20px;display:none;" id="empty" class="btn btn-info btn-xs">Tyhjennä</a>';
                                           // echo '<center><h4>Ei löydetty yhtäkään liikettä.</h4></center>';
                                        } else {
                                            /* jos tietokannassa onkin havaintoja niin tulostetaan myös tyhjä */
                                            echo '<a href="script/empty.php" style="margin-bottom: 20px;" id="empty" class="btn btn-info btn-xs">Tyhjennä</a>';

                                        }

                                        /* Haetaan $GET eli havainnot tietokannasta (notifications) */
                                        while ($row = mysql_fetch_assoc($get)) {
                                            /* luodaan tietokannan informaatioille parametrit */
                                            $id = $row["notification_id"];
                                            $time = $row["notification_time"];
                                            $img = $row["notification_img"];
                                            /* timezone laitetaan Europe/Helsinki */
                                            date_default_timezone_set('Europe/Helsinki');
                                            /* Locale */
                                            setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish'));
                                            /* luodaan tietokannan $row["notification_time"] eli ajasta paremman muotonen koska tietokannan muoto on YYYY-MM-DD HH:MM:SS */
                                            $new_time = ucwords(strftime('%d.%m.%Y, %A klo %H:%M:%S', strtotime($row["notification_time"]))); 

                                            echo '<div id="'.$id.'" style="border: 1px solid #dddddd;margin-bottom: 5px;" class="notification col-md-12 older">';
                                                echo '<div class="col-md-9">';
                                                    echo '<h6>Liikettä havaittu!</h6>'; /* LIIKETTÄ HAVAITTU */
                                                    echo '<p>Aika: <b>'.$new_time.'</b></p>'; /* Kellonaika ($new_time) milloin havainto on luotu/tapahtunut */
                                                    if($img == '') { /* jos havainnolla puuttuu kuva niin sitä ei tulosteta */ } else { 
                                                        /* jos havainnolla on kuva niin se kuva tulostetaan */
                                                            echo '<p>Kuva: 
                                                        <a data-title="Havainto: '.$new_time.'" data-lightbox="havainto" rel="'.$new_time.'" href="uploads/'.$img.'">
                                                                <img alt="'.$new_time.'" style="height: 45px;margin-top: -5px;" src="uploads/'.$img.'"</p>
                                                        </a>';
                                                    }
                                                echo '</div>';
                                            echo '</div>';

                                        }
                                    ?>
                                
                                </div><!-- /.row -->
                                
                            </div><!-- /.tab-pane -->
                        
                            <div class="tab-pane" id="tab2" href="#tab2">

                                <?php
                                /* jos uus käyttäjä on luotu niin se tulostetaan sivustolle (ilmoitus) */
                                    if($_GET["created"] == 'true') {
                                        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong></strong>Käyttäjä lisätty.</div>';
                                        /* seuraavassa kohdassa poistetaan linkin lopusta $_GET["deleted"] jotta jos ensi kerralla lataa sivuston niin viestiä ei enään näy. */
                                        echo '<script>window.history.pushState({}, document.title, "/" + "mvrclabs/code/scripts/admin/manage.php#tab2_");</script>';
                                    }
                                ?>

                                <?php
                                /* jos käyttäjä on poistettu niin siitä luodaan ilmoitus */
                                        if($_GET["deleted"] == 'true') {
                                            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong></strong>Käyttäjä poistettu.</div>';
                                            /* seuraavassa kohdassa poistetaan linkin lopusta $_GET["deleted"] jotta jos ensi kerralla lataa sivuston niin viestiä ei enään näy. */
                                            echo '<script>window.history.pushState({}, document.title, "/" + "mvrclabs/code/scripts/admin/manage.php#tab2_");</script>';
                                        }
                                    ?>

                                <center><h5>Käyttäjät ketkä saavat ilmoituksen sähköpostiin</h5></center>

                                <a style="margin-bottom: 30px;float:right;" class="btn btn-default btn-xs" href="#" data-toggle="modal" data-target="#new_user">Uusi Käyttäjä</a>
                                
                                <div class="row">
                                
                                     <?php 
                                        /* luodaan uusi tietokanta query jossa etsitään käyttäjiä joilla on sinun/ylläpidon app key. ja järjestykseks laitettiin uusimmat ensin.  */
                                        $get = mysql_query("SELECT * FROM mailer WHERE mailer_appkey = '$app_key' ORDER BY mailer_created DESC");
                                            echo '<table class="table table-striped">';
                                                echo '<tr>';
                                                    echo '<th>Käyttäjä</th>'; /* käyttäjä */
                                                    echo '<th>Luotu</th>';    /* milloin se on luotu */
                                                    echo '<th></th>';         /* tyhjä kenttä poista nappia varten */
                                                echo '</tr>';
                                                echo '<tbody>';
                                        /* Seuraavassa haetaan siitä queryista ne tiedot/data */
                                        while ($row = mysql_fetch_assoc($get)) {
                                            /* luodaan tietokannan tiedoilla/datalle parametrin */
                                            $mail = $row["mailer_mail"]; /* tietokannassa mailer_mail kenttää voi nyt käyttää -> $mail nimellä */
                                            /* tässä muutetaan tietokannan alkuperäinen YYYY-MM-DD päivämäärä suomalaiseksi. */
                                            $created = strftime('%d.%m.%Y %H:%I', strtotime($row["mailer_created"])); date_default_timezone_set('Europe/Helsinki');

                                          
                                                    
                                        echo '<tr><td>'.$mail.'</td>'; /* tulostetaan käyttäjä kentälle sähköposti */
                                        echo '<td>'.$created.'</td>';  /* tulostetaan luotu kentälle aika milloin se on luotu */
                                        echo '<td>';
                                        /* jos sähköposti on ogkaaos@gmail.com niin tehdään siitä ns pääkäyttäjä jota ei voi poistaa. */
                                        if($mail == 'ogkaaos@gmail.com') {
                                        echo '<p>Pääkäyttäjä <span data-placement="top" data-toggle="tooltip" title="Pääkäyttäjää ei voi poistaa." style="background-color: gainsboro;border: 1px solid gainsboro; border-radius: 55%; font-size:12px;">&nbsp; ? &nbsp;</span></p>';
                                        } else {
                                        /* tässä tulostetaan poista käyttäjä nappi. */
                                        echo '<a class="btn btn-danger btn-xs" href="script/delete_user.php?user='.$mail.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                                        }
                                        echo '</td></tr>';

                                        }

                                    echo '</tbody>';
                                echo '</table>';
                                    ?>
                                
                                </div><!-- /.row -->
                                
                            </div><!-- /.tab-pane -->
                        
                            <div class="tab-pane" id="tab3" href="#tab3">
                                
                                <div class="row">

                                    <?php 
                                        /* APP KEY */
                                        /* tulostetaan käyttäjälle app key. */
                                        echo '<div class="well">App Key: <b>'.$app_key.'</b></div>'; 
                                    ?>

                                    <?php

                                    /* luodaan tietokanta query (settings taulukko) jossa on setting_appkey = käyttäjän appkey */
                                    $get = mysql_query("SELECT * FROM settings WHERE setting_appkey = '$app_key'");
                                    /* haetaan tiedot/data tietokannasta $get */
                                    while ($row = mysql_fetch_assoc($get)) {
                                        /* luodaan parametrit. */
                                        $amount = $row["setting_amount"]; /* imoituksia/havaintoja etusivulla */
                                        $time = $row["setting_time"]; /* kellonaika jolloin hälyttää */
                                        $stream = $row["setting_stream"]; /* streamaus linkki */
                                        $mode = $row["setting_mode"]; /* onko laite aktiivinen vai ei  */
                                        $message = $row["setting_other"]; /* oma custom viesti */

                                        if($row["setting_mode"] == 'false') {
                                            /* jos tietokannassa laitteen mode on false niin ekan nappulan class="fa fa-power-off" väri on punainen */
                                            $status = '0';
                                            $color = 'red';
                                        } else {
                                            /* jos tietokannassa laitteen mode on false niin ekan nappulan class="fa fa-power-off" väri on vihreä */
                                            $status = '1';
                                            $color = '#2eff5b';
                                        }
                                    }
                                    ?>
                                
                                    <a style="
                                    color: <?php echo $color; ?>;
                                    font-size: 60px;
                                    border: 1px solid #dddddd;
                                    padding: 15px;
                                    border-radius: 6px;
                                " data-placement="top" data-toggle="tooltip" title="<?php if($mode == 'true') { echo 'Sammuta ilmoitusten lähetys.'; } else { echo 'Käynnistä ilmoitusten lähetys.'; } ?>" href="script/activate.php?status=<?php echo $status; ?>"><i class="fa fa-power-off" aria-hidden="true"></i></a> <!-- On/Off nappi jolla voi kytkeä sivuston ilmoitukset joko pois päältä tai päälle -->

                                <a class="reboot" style="
                                    color: #2eff5b;
                                    font-size: 60px;
                                    border: 1px solid #dddddd;
                                    padding: 15px;
                                    border-radius: 6px;
                                " data-placement="top" data-toggle="tooltip" title="Käynnistä Raspberry uudelleen" href="#"><i class="fa fa-refresh" aria-hidden="true"></i></a> <!-- Uudelleen käynnistä raspberry nappi. -->

                                <a style="
                                    color: #2eff5b;
                                    background-color: black;
                                    font-size: 60px;
                                    border: 1px solid #dddddd;
                                    padding: 15px;
                                    border-radius: 6px;
                                " target="_blank" data-placement="top" data-toggle="tooltip" title="Käynnistä Remote-Terminal" href="remote/"><i class="fa fa-terminal" aria-hidden="true"></i></a> <!-- Käynnistä remote terminal nappi -->

                                <hr>


                                    <center><h4>Asetukset</h4></center>

                                    <?php
                                        if($_GET["saved"] == 'true') {
                                            /* jos käyttäjä klikannut "tallenna" nappia etusivulla niin luodaat linkin perään ?saved=true ja jos sellainen on olemassa niin tulostetaan käyttäjälle että tiedot ovat päivitetty. */
                                            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong></strong>Tiedot päivitetty.</div>';
                                            /* tässä poistetaan linkistä ?saved=true */
                                            echo '<script>window.history.pushState({}, document.title, "/" + "mvrclabs/code/scripts/admin/manage.php#tab3_");</script>';
                                        }
                                    ?>

                                    <br>
                                <form action="script/update_settings.php" method="POST">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>Ilmoituksia etusivulla</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control" name="amount">
                                                <optgroup label="Määrä ilmoituksia">
                                                    <option <?php if($amount == '10') { echo 'selected'; } ?> value="10">10</option>
                                                    <option <?php if($amount == '25') { echo 'selected'; } ?> value="25">25</option>
                                                    <option <?php if($amount == '50') { echo 'selected'; } ?> value="50">50</option>
                                                    <option <?php if($amount == '100') { echo 'selected'; } ?> value="100">100</option>
                                                    <option <?php if($amount == '250') { echo 'selected'; } ?> value="250">250</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <br>
                                    <br>

                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>Tietty Aika <span data-placement="top" data-toggle="tooltip" title="Voit valita tietyn ajan milloin liiketunnistin on aktiivinen." style="background-color: gainsboro;border: 1px solid gainsboro; border-radius: 55%; font-size:12px;">&nbsp; ? &nbsp;</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control" name="time">
                                                <optgroup label="Kellonaika">
                                                    <option <?php if($time == '') { echo 'selected'; } ?>  value="">Aina</option>
                                                    <option <?php if($time == 'e') { echo 'selected'; } ?>  value="e">Pois Päältä</option>
                                                    <option <?php if($time == '1') { echo 'selected'; } ?>  value="1">08:00 - 21:00</option>
                                                    <option <?php if($time == '2') { echo 'selected'; } ?>  value="2">21:00 - 08:00</option>
                                                    <option <?php if($time == '3') { echo 'selected'; } ?>  value="3">00:00 - 05:00</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <br>
                                    <br>

                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>Striimin linkki <span data-placement="top" data-toggle="tooltip" title="Kamera striimin linkki/osoite." style="background-color: gainsboro;border: 1px solid gainsboro; border-radius: 55%; font-size:12px;">&nbsp; ? &nbsp;</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input value="<?php echo $stream; ?>" type="text" name="link_to_stream" class="form-control" placeholder="Linkki">
                                        </div>
                                    </div>

                                    <br>
                                    <br>

                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>Muu Viesti <span data-placement="top" data-toggle="tooltip" title="Oma viesti mikä lähetetään käyttäjille ilmoituksen mukana." style="background-color: gainsboro;border: 1px solid gainsboro; border-radius: 55%; font-size:12px;">&nbsp; ? &nbsp;</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea style="max-width: 415px;max-height: 150px;" type="text" name="custom_message" class="form-control" placeholder="Oma viesti..."><?php echo $message; ?></textarea>
                                        </div>
                                    </div>

                                    <input style="margin-right:30px;margin-top: 30px;float:right;" class="btn btn-primary" id="submit_button" value="Tallenna" input type="submit" name="submit">
                                    <a style="margin-left:30px;margin-top: 30px;float:left;" class="btn btn-default" href="http://marcosraudkett.com/mvrclabs/code/scripts/admin/fastsend.php" target="_blank">Testilähetys (server #1 - fast)</a>
                                    <a style="margin-left:30px;margin-top: 30px;float:left;" class="btn btn-default" href="http://marcosraudkett.com/mvrclabs/code/scripts/admin/script/service.php" target="_blank">Testilähetys (server #2 - slow)</a>
                                </form>
                                <br><br><br>
                            <center>
                                    <code style="display:none;margin-top:25px;padding:7px;text-align:left;float:left;background-color:#333333;/* width:50%; *//* margin-left: 25%; *//* max-width: 1000px; *//* min-width: 880px; */">
                                    <p>http://marcosraudkett.com/mvrclabs/code/scripts/admin/script/<br>service.php?api_key=<b><font color="white">YOUR_APP_KEY</font></b></p>
                                </code>
                            </center>

                                
                                </div><!-- /.row -->
                                
                            </div>
                        </div> <!-- /tab-content -->
                                                                                                
                    </div><!-- /.col-md-4 col -->
                
                </div><!-- /.row -->
            
            </div><!-- /.container -->
        
        </div><!-- /.item -->
        
    <!-- /.item --></div><!-- /#page -->

<!-- Modal -->
<div id="new_user" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Uusi Käyttäjä</h4>
      </div>
      <div class="modal-body">
        <form action="script/add_user.php" method="POST">
        <input type="text" name="user" class="form-control" placeholder="Sähköposti">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit" name="submit" id="submit_button">Lisää Käyttäjä</button>
        </form>
      </div>
    </div>

  </div>
</div>


    <!-- Load JS here for greater good =============================-->
    <script src="https://marcosraudkett.com/vivinetti/js/lightbox/lightbox-plus-jquery.min.js"></script>
    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="js/jquery.ui.touch-punch.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrap-switch.js"></script>
    <script src="js/flatui-checkbox.js"></script>
    <script src="js/flatui-radio.js"></script>
    <script src="js/jquery.tagsinput.js"></script>
    <script src="js/jquery.placeholder.js"></script>
    <script src="js/application.js"></script>
    <script src="js/manage.js"></script>


    <script>
        /* kuva katselu koko max leveys: 920px */
        lightbox.option({
          'resizeDuration': 200,
          'wrapAround': true,
          'maxWidth': 920
        })
    </script>

<script>
/* raspberry uudellenkäynnistys toiminto */
$(document).ready(function() {
    /* jos .reboot nappia painetaan */
    $(".reboot").click(function() {
        /* data mikä lähetetään on do=reboot ja app_key on kirjautuneen käyttäjän appkey */
        dataString = 'do=reboot&app_key=<?php echo $app_key ?>';
        /* ajax */
        $.ajax({
            /* osoite tai tiedosto mihin se lähetetään dataString */
            url: 'script/command/', /* script/command -> script/commander.php */
            type: 'POST', /* tyyppi */
            data: dataString, /* data */
            beforeSend: function(data) {

            },
            success: function(data) {
                /* jos palautuksena tulee status -> offline commander.php tiedostosta  = offline niin  
                lähetetään käyttäjä kirjautumisivulle. */
                if(data.oc_status == 'offline') {
                        window.location.href = 'signin';
                } else {
                    /* jos palautuksena tulee status -> success niin muutetaan napin väri harmaaksi */
                    if(data.oc_status == 'success') {
                        $(".fa-refresh").css("color","grey");
                    }
                }
            }
        });
    });
});
</script>



</body></html>
<?php



            } 



        } 



        } else {           



 header("Location: signin"); 



 } 

/*
    MIT License // Marcos Raudkett © 2017
    https://marcosraudkett.com
*/


 ?> 