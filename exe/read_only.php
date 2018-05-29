<?php

session_start();

//global
include('include/global.php');

//connection
include('include/connection.php');

?>

<!doctype html>
<html lang="fr">
<head>

	<meta charset="utf-8">
	<title>Consultation des malles</title>
	<link rel="stylesheet" href="css/global_read_only.css">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/font_awesome/css/font-awesome.min.css">

</head>
<body>

	<div id="black_screen"></div>

	<div id="popup_document" class="popup">
		<div class="button_close_popup"><i class="fa fa-times-circle"></i></div>
		<iframe></iframe>
	</div>

	<div id="header">
		<div class="left"><img src="media/canope.png" /></div>
		<div class="right">Consultation des malles </div>
	</div>


	<div id="search_zone">
		<p><input type="text" id="search" placeholder="Moteur de recherche" class="field"> <button id="button_search">Chercher</button><button id="button_see_all">Tout voir</button></p>
	</div>


	<div id="result_zone"></div>


	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/read_only.js"></script>

	<script type="text/javascript">
	
	function close_popup(){

		$("#black_screen").hide();
		$("#popup_document").html('<div class="button_close_popup"><i class="fa fa-times-circle"></i></div><iframe></iframe>');
		$("#popup_document").hide();

	}


	function refresh_my_reservation_counter(counter){
		$("#button_my_reservation .my_reservation_counter").html(counter);
	}


	function refresh_available_flag(id_malle, id_periode, avalaible){

		if( avalaible == "yes" ){
			color = "#CDDC39";
		}else{
			color = "#F44336";
		}

		$("#result_zone").find("[data_id_malle='"+id_malle+"']").find(".p"+id_periode).css("background-color", color);
	}

	function return_reservation_counter(){
		return $("#button_my_reservation .my_reservation_counter").html();
	}



</script>

</body>
</html>