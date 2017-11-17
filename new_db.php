<?php 
  $servername = "TIETOKANNAN IP";
  $username = "NIMI";
  $password = "SALASANA";
  $dbname = "TABLE NIMI";
  $conn = new mysqli($servername, $username, $password, $dbname);
  date_default_timezone_set('Europe/Helsinki'); setlocale(LC_TIME, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish')); 
  mysqli_set_charset ($conn, "utf8");
?>