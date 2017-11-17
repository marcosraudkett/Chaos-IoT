Koodit ei toimi jos palvelimella on php versio uudempi kuin <5.4 ja tietokanta <5.5.51-38.1

Jos haluat muuttaa koodin sopivaksi uudemmaks versioks niin tulee koodista vaihtaa mysql parametrit seuraavasti:
mysql -> mysqli tai PDO
kaikki mysql tulisi vaihtaa mysqli tai PDO menetelmäksi koska mysql on vanheutunutta versiotta ja toimii vain >5.4 versioissa ja on kokonaan poistettu 7.0 versiossa. 
ja jos tietokanta on uudempi kuin 5.5.51-38.1 niin pitää myös lähes kaikkiin muihin paitsi ID kohtiin myös vaihtaa null = null.

tosiaan koodissa on myös käytetty epäturvallisia methodeja mutta jos aiot käyttää tätä niin suositeltava myös vaihtaa kirjautumis menetelmä erillaiseksi esimerkiksi luomaan tietokantaan secret key kenttä ja käyttämällä sitä eikä tallentamalla username ja password kekseiksi (?).
myös salasanat pitäsi hashata seuraavasti rekisteröitymis sivulla: MD5($_POST["pass"]); ja myöhemmin decryptaa kirjautumissivulla kun tarkistaa että salasana on lisätty oikein. Kun tällä hetkellä siinä on vain: $_POST["pass"];

ja myös estääkseen SQL Injectioniä pitää myös suojata seuraavasti jokaisessa tietokanta lisäys koodissa:
	$activate = $_GET["status"];
pitäisi muutaa seuraavaksi:
	$activate_html = htmlspecialchars(isset($_GET["status"]) ? $_GET["status"] : "");
	$activate = str_replace("'", "&#39;", $activate_html);

tämä ylhäällä oleva koodi estää käyttäjän lisämällä epämääräisiä koodeja input kenttiin eli:
vaihtaa html koodin teksimuodoksi ja vaihtaa ' merkin &#39; mikä meinaa että se näyttää -> ' mutta se on teksimuodossa.. (?)

ilman tätä käyttäjä voi suoraan kenttään kirjoittaa: '; DROP TABLE members; tai '; SELECT FROM members 1=1;
mikä meinaa että se mm poistaa/hakea tietoja tietokannasta.

tätä voidaan myös estää lisääkin mutta kaikki siihen löytyy Googlesta.

Monesta script kansion skripteistä voi myös poistaa:

/* timezone Europe/Helsinki */
date_default_timezone_set('Europe/Helsinki');
/* aika nyt */
$postdate = date("Y-m-d H:i:s", time());

ja niille voisi tehdä vaikka erillisen config.php tiedoston johon ne lisäisi ja jonka sitten linkkaisi kaikkiin
skripteihin:

require_once("config.php");

tai sitten db.php tiedostoon mutta ei ehkä paras idea.





Lisää esimerkkejä:

Koodi:
	mysql_query("SELECT * FROM users WHERE email='$email'");

Tulisi vaihtaa seuraavasti:
	$mysql = "SELECT * FROM users WHERE email='$email'";
	$mysql_1 = mysqli_query($conn, $mysql); /* huom että $conn meinaa sitä tietokanta yhteyttä */


tietokanta tiedostossa:
	mysql_connect("TIETOKANTA OSOITE", "KÄYTTÄJÄNIMI", "SALASANA") or die(mysql_error()); mysql_select_db("TABLE") or die(mysql_error()); 

tulisi vaihtaa seuraavaksi:
	$conn = mysqli_connect("TIETOKANTA OSOITE", "KÄYTTÄJÄNIMI", "SALASANA") or die(mysql_error()); mysqli_select_db("TABLE") or die(mysqli_error()); /* HUOM mysqli toimii uusimissa <5.4 php versiossa. */


jos sivuston selaus ei toimi niin katsothan .htaccess tiedostoa mistä löytyy koodien/scriptien oikeat osoitteet/kansiot.
