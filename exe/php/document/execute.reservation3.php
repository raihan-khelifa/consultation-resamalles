<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

//save information
$_SESSION['retour_lieu'] = $_POST['retour'];
$_SESSION['retour_commentaire'] = $_POST['information2'];
if( $_POST['retour'] == "college" ){

	$temp = explode("#", $_POST['college_retour']);
	$_SESSION['retour_date'] = $temp[2];
	$_SESSION['retour_college'] = $temp[3];

}else{

	$_SESSION['retour_date'] = "";
	$_SESSION['retour_college'] = "";

}

//update reservation
mysqli_query($connection, "UPDATE ".PREFIX."reservation SET retour_lieu='".safemode_injection($_POST['retour'])."', retour_college='".safemode_injection($_POST['college_retour'])."', retour_commentaire='".safemode_injection($_POST['information2'])."', finalisation=1 WHERE id_reservation=".$_SESSION['id_reservation']) or die("error 4456".mysqli_error($connection));

?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Popup</title>
	<link rel="stylesheet" href="../../css/global.css">
	<link rel="stylesheet" href="../../css/popup_document.css">
	<link rel="stylesheet" href="../../css/font_awesome/css/font-awesome.min.css">
</head>
<body>

	<div id="popup_reservation3">

		<div id="document_title">Confirmation de réservation</div>

		<div id="reservation_zone">

			<p class="detail"><?php echo utf8_encode($_SESSION['titre']);?> - <?php echo "P".$_SESSION['id_periode'];?></p>

			<?php

			//retrait

			$retrait = ""; 

			if( $_SESSION['retrait_lieu'] == "canope" ){ $retrait .= "Canopé de Moulins"; }
			if( $_SESSION['retrait_lieu'] == "college" ){ $retrait .= "Collège de ".$_SESSION['retrait_college']." le ".$_SESSION['retrait_date']; }
			if( $_SESSION['retrait_lieu'] == "autre" ){	$retrait .= "Autre"; }
			if( $_SESSION['retrait_commentaire'] != "" ){ $retrait .= " (".$_SESSION['retrait_commentaire'].")"; }

			//retour
			
			$retour = ""; 

			if( $_SESSION['retour_lieu'] == "canope" ){ $retour .= "Canopé de Moulins";	}
			if( $_SESSION['retour_lieu'] == "college" ){ $retour .= "Collège de ".$_SESSION['retour_college']." le ".$_SESSION['retour_date']; }
			if( $_SESSION['retour_lieu'] == "autre" ){ $retour .= "Autre"; }
			if( $_SESSION['retour_commentaire'] != "" ){ $retour .= " (".$_SESSION['retour_commentaire'].")"; }

			//envoi des mails
			$mail = "";
			$mail .= "Bonjour,".PHP_EOL.PHP_EOL;
			$mail .= "Voici les détails de votre réservation :".PHP_EOL.PHP_EOL;
			$mail .= "Emprunteur : ".$_SESSION['emprunteur_prenom']." ".$_SESSION['emprunteur_nom']." (".$_SESSION['emprunteur_email'].")".PHP_EOL.PHP_EOL;
			$mail .= "Etablissement : ".utf8_encode($_SESSION['etablissement_structure'])." ".utf8_encode($_SESSION['etablissement_organisme'])." ".utf8_encode($_SESSION['etablissement_commune'])." ".PHP_EOL.PHP_EOL;
			$mail .= "Malle : ".utf8_encode($_SESSION['titre']).PHP_EOL.PHP_EOL;
			$mail .= "Période : ".$_SESSION['id_periode'].PHP_EOL.PHP_EOL;
			$mail .= "Retrait : ".$retrait.PHP_EOL.PHP_EOL;
			$mail .= "Retour : ".$retour.PHP_EOL.PHP_EOL;
			$mail .= "Merci pour votre confiance".PHP_EOL.PHP_EOL;
			$mail .= "L'équipe Canopé".PHP_EOL.PHP_EOL;
			$mail .= "Réservation faite le ".date("d/m/Y")." à ".date("H:i");

			mail("contact.atelier03@reseau-canope.fr", utf8_decode("réservation de malle"), utf8_decode($mail), "FROM:canope-resamalles@ac-clermont.fr");
			mail($_SESSION['emprunteur_email'], utf8_decode("réservation de malle"), utf8_decode($mail), "FROM:canope-resamalles@ac-clermont.fr");
			mail($_SESSION['client_email'], utf8_decode("réservation de malle"), utf8_decode($mail), "FROM:canope-resamalles@ac-clermont.fr");

			?>

			<p><label>Retrait : </label><?php echo $retrait;?></p>

			<p><label>Retour : </label><?php echo $retour;?></p>

		</div>

		<?php if( $_SESSION['status'] == "client" ){ ?>

		<div id="goodbye_zone">

			<p style="color:black;">Un mail récapitulatif vient d'être envoyé à l'adresse<br><br><?php echo $_SESSION['emprunteur_email'];?></p>

			<p>Retrouvez toutes vos réservations dans l'onglet Réservations en cours</p>

		</div>

		<?php } ?>

	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>