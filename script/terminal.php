<?php 

/*
    MIT Marcos Raudkett © 2017
    https://marcosraudkett.com
*/

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


?> 
<script>
/* TÄSTÄ ALKAA TERMINALIN KOODI */
$('.shell').keypress(function (e) {
 var key = e.which;
 if(key == 13)  { /* jos enteriä on painettu */
 	$(".shell").attr("disabled","disabled"); /* .shell muuttuu -> disabled */
 	var old = $(".input").css("margin-top"); /* haetaan .input margin-top määrä */
 	var shell_text = $(".shell").val(); /* haetaan .shell kirjoitettua tekstiä */
 	var new_ = parseInt(old) + 15; /* lisäätään .input margin-top määrälle +15 */
 	var new_2 = parseInt(new_) - 14; /* miinustetaan äskeisestä -15 */
 	if(shell_text == '') { /* jos .shell teksti on tyhjä */
 		$(".shell").prop('disabled', false); /* .shell vaihtuu takaisin -> enabled */
 		$(".shell").focus(); /* .shell focus */
 	} else { /* jos .shell teksti ei ole tyhjä */
	 	if(old == "0px") { /* jos .input margin-top määrä on 0px */
	  		$(".input").attr("style","margin-top: 20"); /* lisätään .input margin-top: 20 */
	  		var connected = $(".Connected").val(); /* haetaan .connected value (tämä on käyttäjän ip osoite) */
	  		/* lisätään historiaan äskeinen lisäys ja sen tiedot */
	  		$(".history").append('<p class="history_'+new_+'"><font color="green"><?php echo $app_key ?>@'+connected+':</font><font color="#84b3ff">~ $ </font> <font class="command_'+new_+'" value="'+new_+'" color="white">'+shell_text+'</font>');
	  		/* muutetaan .shell input tyjhäksi */
	  		$(".shell").val("");
	  			/* ilmoitetaan mitkä määrät tai tiedot lähetetään eteenpäin ../script/command/ scriptiin. */
	  			dataString = 'do='+shell_text+'&app_key=<?php echo $app_key ?>';
		  		$.ajax({
		            url: '../script/command/', /* mihin scriptiin lähetetään tiedot */
		            type: 'POST', /* mitä methodia käytetään */
		            data: dataString, /* ilmoitetaan data kuten ylhäällä on dataString */
		            beforeSend: function(data) {
		            	/* tähän voisi vaikka luoda jonkunlaisen latauksen/animaation yms.. */
		            },
		            success: function(data) {
		            	if(data.oc_status == 'offline') { /* jos scriptistä palautetaan oc_status=offline sitten lähetetään käyttäjä kirjautumissivulle. */
		            			window.location.href = '../signin';
		            		} else {
			            	if(shell_text == 'sudo clear history') { /* clear history commandi */
			            		$(".history_"+new_).append(" <font color='#42f45f'><b>Status:</b> command successfully sent.</font> <font class='responsetru' color='orange'>(history cleared)</font>");
			            		$(".shell").prop('disabled', false);
			            		$(".shell").focus();
			            	} else {
				            	if(data.oc_status == 'success') { /* jos palautuu success tila */

				            		if(data.oc_msg == '') {
				            			/* lisätään command successfully sent */
					            		$(".history_"+new_).append(" <font color='#42f45f'><b>Status:</b> command successfully sent.</font> <font data-id='"+data.oc_id+"' class='responsetru response_"+data.oc_id+"' color='orange'>(waiting for response)</font>");
					            		$(".responsetru").attr('data-id',data.oc_id);
				            		} else {
				            			/* lisätään command successfully sent mutta ilman oc_msg */
				            			$(".history_"+new_).append(" <font color='#42f45f'><b>Status:</b> command successfully sent.</font> <font data-id='"+data.oc_id+"' class='responsetru response_"+data.oc_id+"' color='d0d0d0'>(Alternative Command, This command was not sent to your device)</font> <br><font color='#a7a7a7'>"+data.oc_msg+"</font>");
				            			$(".response_"+new_).remove();
				            			$(".shell").prop('disabled', false);
			            				$(".shell").focus();
				            		}
				            	} else {
				            		$(".history_"+new_).append(" <font color='red'><b>Status:</b> could not send command. (Reason: Raspberry Offline)</font>");
				            	}
				            }
				        }
		            },
		            error: function(data) {
		            	window.location.href = '../signin';
		            	//$(".history_"+new_).append(" <font color='red'><b>Status:</b> could not send command. (Reason: server offline)</font>");
		            },
		            fail: function(data) {
		            	$(".history_"+new_).append(" <font color='red'><b>Status:</b> sending failed. (Reason: failed)</font>");
		            }
		        });
	 	} else {
	  		$(".input").attr("style","margin-top:"+new_);
	  		$(".certifier").attr("style","margin-top:-"+new_);
	  		var connected = $(".Connected").val();
	  		$(".history").append('<p style="margin-bottom:-15px;" class="history_'+new_+'"><font color="green"><?php echo $app_key ?>@'+connected+':</font><font color="#84b3ff">~ $ </font> <font class="command_'+new_+'" value="'+new_+'" color="white">'+shell_text+'</font></p>');
	  		$(".shell").val("");

	  			dataString = 'do='+shell_text+'&app_key=<?php echo $app_key ?>';
		  		$.ajax({
		            url: '../script/command/',
		            type: 'POST',
		            data: dataString,
		            beforeSend: function(data) {

		            },
		            success: function(data) {
		            	if(data.oc_status == 'offline') {
		            			window.location.href = '../signin';
		            	} else {
			            	if(shell_text == 'sudo clear history') {
			            		$(".history_"+new_).append(" <font color='#42f45f'><b>Status:</b> command successfully sent.</font> <font class='responsetru' color='orange'>(history cleared)</font>");
			            		$(".response_"+new_).remove();
		            			$(".shell").prop('disabled', false);
		        				$(".shell").focus();
			            	} else {
				            	if(data.oc_status == 'success') {
				            		
									if(data.oc_msg == '') {
					            		$(".history_"+new_).append(" <font color='#42f45f'><b>Status:</b> command successfully sent.</font> <font data-id='"+data.oc_id+"' class='responsetru response_"+data.oc_id+"' color='orange'>(waiting for response)</font>");
					            		$(".responsetru").attr('data-id',data.oc_id);
				            		} else { 
				            			$(".history_"+new_).append(" <font color='#42f45f'><b>Status:</b> command successfully sent.</font> <font data-id='"+data.oc_id+"' class='responsetru response_"+data.oc_id+"' color='d0d0d0'>(Alternative Command, This command was not sent to your device)</font> <br><font color='#a7a7a7'>"+data.oc_msg+"</font>");
				            			$(".response_"+new_).remove();
				            			$(".shell").prop('disabled', false);
			            				$(".shell").focus();
				            		}
				            	} else {
				            		$(".history_"+new_).append(" <font color='red'><b>Status:</b> could not send command. (Reason: server offline)</font>");
				            	}
				            }
			            }
		            },
		            error: function(data) {
		            	$(".history_"+new_).append(" <font color='red'><b>Status:</b> could not send command. (Reason: server offline)</font>");
		            },
		            fail: function(data) {
		            	$(".history_"+new_).append(" <font color='red'><b>Status:</b> sending failed. (Reason: failed)</font>");
		            }
		        });
	 	}
	    $('input[name = command]').click();
	    return false;  
	  }
	}

});  

$(".shell").keydown(function(e) {
	var key = e.which;
    if(key == 38)  {
    	var old = $(".input").css("margin-top");
    	if(old == '0px') {
    		var old = $(".input").css("margin-top");
		 	var shell_text = $(".shell").val();
		 	var new_ = parseInt(old);

		  	var older = $(".command_"+new_).text();
		  	$(".shell").val(older);
		  	console.log('old: '+old+' | shell_text: '+shell_text+' | new_: '+new_+' | text: '+older);
    	} else {
	    	var old = $(".input").css("margin-top");
		 	var shell_text = $(".shell").val();
		 	var new_ = parseInt(old);

		  	var older = $(".command_"+new_).text();
		  	$(".shell").val(older);
		  	console.log('old: '+old+' | shell_text: '+shell_text+' | new_: '+new_+' | text: '+older);
		}
	 }
});

$("html").on('click' ,function(){
	$(".shell").focus();
});


$("body").on('click' ,function(){
	$(".shell").focus();
});

$(document).ready(function () {
	$(".shell").focus();
});

$(document).ready(function () {
    setInterval(function(){
        check_for_response();
        check_server_status();
        //$(".0").attr("style","border: 1px solid #dddddd;margin-bottom: 5px;");
        }, 5000); /*  */
});

var audio = new Audio('../assets/sound/terminal.mp3');
function check_server_status() {
	<?php if($_GET["debug"] == 'true') {
		echo 'dataString="app_key='.$app_key.'&debug=true";';
	} else {
		echo "dataString = 'app_key=".$app_key."';";
	} ?>

	$.ajax({
		url: '../script/status/',
		type: 'POST',
		data: dataString,
		beforeSend: function(data) {
			//document.title = "Connecting - (Remote Terminal)";
			$(".loader-text").html('Connecting');
			$(".loader-text").css("margin-left","-7px");
			$(".loader-text").css("width","250px");
			$(".loader-text").css("color","white");
			$(".one").css("animation","rotate-one 1s linear infinite");
			$(".two").css("animation","rotate-two 1s linear infinite");
			$(".three").css("animation","rotate-three 1s linear infinite");
		},
		success: function(data) {
			if(data.oc_status == 'offline') {
	            			window.location.href = '../signin';
	        } else {
				if(data.oc_status == 'success') {
					$("body").css("cursor","text");
					$(".Connected").val(data.oc_ip_address);
					$(".user").text(data.oc_app_key+"@"+data.oc_ip_address);
					document.title = data.oc_app_key+"@"+data.oc_ip_address+" (Remote Terminal)";
					//var audio = new Audio('../assets/sound/terminal.mp3');
            		audio.pause();
					audio.currentTime = 0;

					$(".loader-text").text("Successfully Connected.").fadeOut(1200);
					$(".loader-text").css("color","#94ffb0");
					$(".loader-text").css("margin-left","-47px");

					$(".inner.one").css("animation","rotate-one 2s linear infinite");
					$(".inner.two").css("animation","rotate-two 2s linear infinite");
					$(".inner.three").css("animation","rotate-three 2s linear infinite");

					$(".history").fadeIn();
					$(".loader").fadeOut(1200);
				   	$(".input").fadeIn(500);
				   	$(".shell").focus();
				   	$(".organized_chaos-header").fadeIn(7500);
				   	//$(".title").fadeIn(2500);
				} else {
					$("body").css("cursor","default");
					document.title = "Raspberry Offline - (Remote Terminal)";
					//var audio = new Audio('../assets/sound/terminal.mp3');
					//audio.play();
					audio.volume = 0.01;

					$(".loader-text").html('Raspberry offline. <a style="color:green;" onClick="check_server_status()" href="#">Retry</a>');
					$(".loader-text").css("margin-left","-45px");
					$(".loader-text").css("width","250px");
					$(".loader-text").css("color","#ff7373");
					$(".inner.one").css("animation","rotate-one 5s linear infinite");
					$(".inner.two").css("animation","rotate-two 5s linear infinite");
					$(".inner.three").css("animation","rotate-three 5s linear infinite");

					$(".organized_chaos-header").css("display","none");
					$(".organized_chaos-header").css("display","none");
					$(".input").css("display","none");
					$(".input").css("display","none");
					$(".loader").fadeIn();
					$(".loader-text").fadeIn();
					$(".history").fadeOut();
					//$(".loader").fadeOut(200);
				}
			}
		},
		error: function(data) {
			$("body").css("cursor","default");
			document.title = "Error occurred while connecting - (Remote Terminal)";
			//var audio = new Audio('../assets/sound/terminal.mp3');
			//audio.play();
			audio.volume = 0.01;

			$(".loader-text").html('Error occurred while connecting. <a style="color:green;" onClick="check_server_status()" href="#">Retry</a>');
			$(".loader-text").css("margin-left","-85px");
			$(".loader-text").css("width","300px");
			$(".loader-text").css("color","#ff7373");
			$(".inner.one").css("animation","rotate-one 5s linear infinite");
			$(".inner.two").css("animation","rotate-two 5s linear infinite");
			$(".inner.three").css("animation","rotate-three 5s linear infinite");

			$(".organized_chaos-header").css("display","none");
			$(".organized_chaos-header").css("display","none");
			$(".input").css("display","none");
			$(".input").css("display","none");
			$(".loader").fadeIn();
			$(".loader-text").fadeIn();
			$(".history").fadeOut();
			//$(".loader").fadeOut(200);
		},
		fail: function(data) {
			$("body").css("cursor","default");
			document.title = "Connection failed - (Remote Terminal)";
			//var audio = new Audio('../assets/sound/terminal.mp3');
			//audio.play();
			audio.volume = 0.01;


			$(".loader-text").html('Connection Failed. <a style="color:green;" onClick="check_server_status()" href="#">Retry</a>');
			$(".loader-text").css("margin-left","-49px");
			$(".loader-text").css("width","300px");
			$(".loader-text").css("color","#ff94c1");
			$(".inner.one").css("animation","rotate-one 5s linear infinite");
			$(".inner.two").css("animation","rotate-two 5s linear infinite");
			$(".inner.three").css("animation","rotate-three 5s linear infinite");

			$(".organized_chaos-header").css("display","none");
			$(".organized_chaos-header").css("display","none");
			$(".input").css("display","none");
			$(".input").css("display","none");
			$(".loader").fadeIn();
			$(".loader-text").fadeIn();
			$(".history").fadeOut();
			//$(".loader").fadeOut(200);
		}
	});
}

function check_for_response() {

	$('.responsetru').each(function(i) {
        //var lastid = $(this).attr('data-id',i);
        var arr = [parseInt($(this).data('id'))];
        var lastid = Math.max(arr);
		dataString = 'id='+lastid+'&app_key=<?php echo $app_key ?>';
	//var lastid = $(".responsetru").attr("data-id");

    var old = $(".input").css("margin-top");
 	var shell_text = $(".shell").val();
 	var new_ = parseInt(old);

  	var older = $(".command_"+new_).text();

	$.ajax({
		url: '../script/response/',
		type: 'POST',
		data: dataString,
		beforeSend: function(data) {

		},
		success: function(data) {
			if(data.oc_status == 'success') {
				if(data.oc_response == '') {
					$("body").css("cursor","text");
					$(".shell").prop('disabled', false);
					$(".shell").focus();
					//$(".shell").prop('disabled', true);
					$(".response_"+data.oc_id).attr("color","#d0d0d0");
					$(".response_"+data.oc_id).text("(Command Executed)");
					//$(".loader").fadeOut();
				} else {
					$("body").css("cursor","text");
					//$(".loader").fadeOut();
					$(".shell").prop('disabled', false);
					$(".shell").focus();
					//$(".shell").prop('disabled', true);
					$(".response_"+data.oc_id).attr("color","#d0d0d0");
					$(".response_"+data.oc_id).text("(Command Executed)");
					//$(".history_"+data.oc_id).html(data.oc_ip+"> "+data.oc_response);
					$(".response_"+lastid).append('<br> <font color="#cb78ff">'+data.oc_ip+"</font>: "+data.oc_response);
				}
			} else {
				$("body").css("cursor","default");
			}
		}
	});
  });
}



</script>
<?php
            }
        } 
    } else {           
 		header("Location: ../signin"); 
 	} 
 ?> 
