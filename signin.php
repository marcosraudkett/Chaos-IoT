<?php 
require_once("db.php"); /* tietokanta tiedosto */

if(isset($_COOKIE['ID_my_site'])) { 

$email = $_COOKIE['ID_my_site'];    
$pass = $_COOKIE['Key_my_site'];        
$check = mysql_query("SELECT * FROM users WHERE email = '$email'")or die(mysql_error());    

while($info = mysql_fetch_array( $check )) {       
        if ($pass != $info['password']) {                       

        } else {           
            header("Location: user");          
        }       
    } 
} 

 if (isset($_POST['submit'])) { 
    if(!$_POST['email'] | !$_POST['pass']) {
        die('You did not fill in a required field.');
    }

    if (!get_magic_quotes_gpc()) {
        $_POST['email'] = addslashes($_POST['email']);
    }

    $check = mysql_query("SELECT * FROM users WHERE email = '".$_POST['email']."'")or die(mysql_error());
    $check2 = mysql_num_rows($check);

    if ($check2 == 0) {
        die('Kyseist&auml; k&auml;yttj&auml;j&auml;&auml; ei ole olemassa.');
    }


         while($info = mysql_fetch_array($check)) {
            if($info["key"] == '1') {
              die('<center>Tilisi on terminoitu</center>');
            } else { }

            $_POST['pass'] = stripslashes($_POST['pass']);
            $info['password'] = stripslashes($info['password']);
            $_POST['pass'] = ($_POST['pass']);

            if ($_POST['pass'] != $info['password']) {
                die('K&auml;ytt&auml;j&auml;nimi tai salasana v&auml;&auml;in!');
            } else { 

            $_POST['email'] = stripslashes($_POST['email']); 
            $hour = time() + 3600; 
            setcookie(ID_my_site, $_POST['email'], $hour); 
            setcookie(Key_my_site, $_POST['pass'], $hour);  

         header("Location: user/"); 


        } 
    } 
 } else {    

?> <!--DOCTYPE html -->
<html><head>
    <meta charset="utf-8">
    <title>Organized Chaos - Kirjaudu</title>
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
    
    <!-- Font Awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    
    <div id="page" class="page">

       <center> <img src="/mvrclabs/code/scripts/admin/images/organizedchaos_withouttext.png" style="
    height: 230px;
    margin: auto;
    margin-bottom: -140px;
">
</center>

    <div class="container" style="
    border: 1px solid grey;
    height: 350px;
    width: 450px;
    margin-top: 150px;
    border-radius: 10px;
    background: #2f4154;
    box-shadow: 1px 1px 7px 4px #8c8c8c;
">
<img src="https://www.1plusx.com/app/mu-plugins/all-in-one-seo-pack-pro/images/default-user-image.png" style="
    height: 120px;
    border-radius: 50%;
    margin: auto;
    margin-top: 19px;
    margin-left: 36%;
    margin-right: 50%;
    border: 2px solid #bdc3c6;
">
<form action="" method="POST">
    <div class="row" style="
        margin-top: 19px;
    ">
        <div class="col-sm-12">
            <input name="email" id="email" class="form-control" type="email" placeholder="Sähköposti">
        </div>
    </div>

    <div class="row" style="margin-top: 20px;">
        <div class="col-sm-12">
            <input name="pass" id="pass" class="form-control" type="password" placeholder="Salasana">
        </div>
    </div>
<input style="
        margin-top: 20px;
        float: right;
    " class="btn btn-primary" id="submit_button" type="submit" value="Kirjaudu" input type="submit" name="submit"/>
<a style="margin-top: 20px;" style="float:left;" class="btn btn-info" href="signup.php">Rekisteröidy</a>
</form>
    </div>
        
    <!-- /.item --></div><!-- /#page -->


    <!-- Load JS here for greater good =============================-->
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


</body></html>
<?php } ?> 