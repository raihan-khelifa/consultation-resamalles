<?php

//session
session_start();

//globals
include('include/global.php');

//connection
include('include/connection.php');


?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Réservations de malles</title>
	<link rel="stylesheet" href="css/global.css">
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
		<div class="right">Réservations de malles<?php if(isset($_SESSION['client_email'])){echo "<p>".$_SESSION['client_email']."</p>";}?></div>
	</div>

	<?php if( !isset($_SESSION['status']) || !isset($_SESSION['id_client']) ){ ?>

	<div id="login_zone">
		<h1>Authentification</h1>
		<p><input type="text" id="user_mail" placeholder="Email abonné Canopé" class="field"></p>
		<p><input type="password" id="user_pwd" placeholder="Mot de passe abonné Canopé" class="field"></p>
		<p><button id="button_login">Valider</button></p>
		<p id="note">Vos identifiants sont les mêmes que pour le portail Cyber Services. Si vous les avez oubliés, allez sur <a href="http://crdp-cyberservices.ac-clermont.fr" target="_blank">crdp-cyberservices.ac-clermont.fr</a> et cliquez sur "Si vous ne savez pas si vous avez un compte ou si vous avez perdu vos codes, cliquez ici". Attention : si vous demandez un nouveau mot de passe, il sera aussi modifié pour le portail Cyber Services puisque c'est le même. Pour toute question, écrivez à <a href="mailto:canope-resamalles@ac-clermont.fr">canope-resamalles@ac-clermont.fr</a></p>
	</div>

	<?php 

		
		}else{ 

			//get client reservation
			$req_reservation = mysqli_query($connection, "SELECT id_reservation	FROM ".PREFIX."reservation WHERE id_emprunteur=".$_SESSION['id_client']) or die("die 99988".mysqli_error($connection));

	?>

	<div id="menu">
		<?php if( $_SESSION['status']=="client" ){ ?><span id="button_my_reservation">Réservations en cours (<span class="my_reservation_counter"><?php echo mysqli_num_rows($req_reservation);?></span>)</span> | <?php } ?><?php if( $_SESSION['status']=="admin" || $_SESSION['status']=="superadmin" ){ ?><span>Feuille de route du</span> <input type="text" id="input_date_tournee" size="10" maxlength="10" placeholder="jj/mm/aaaa" /> <span id="button_todolist">OK</span> | <?php } ?><span id="button_logout" title="Déconnexion <?php echo $_SESSION['status'];?>">Déconnexion</span>
	</div>

	<div id="search_zone">
		<p><input type="text" id="search" placeholder="Moteur de recherche" class="field"> <button id="button_search">Chercher</button><button id="button_see_all">Tout voir</button></p>
	</div>

	<div id="warning">Dans l'intérêt général, merci de ne pas emprunter plus de 3 malles par personne et par période.</div>

	<?php if( $_SESSION['status']=="superadmin" ){ echo '<div id="button_add_document"><i class="fa fa-plus-circle"></i> Ajouter une malle</div>'; } ?>

	<div id="result_zone"></div>

	<?php } ?>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/index.js"></script>

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

<?php mysqli_close($connection);?>