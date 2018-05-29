<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

//get reservation
$req_reservation = mysqli_query($connection, "SELECT 

	".PREFIX."reservation.id_reservation, 
	".PREFIX."reservation.jeton_reservation, 
	".PREFIX."reservation.id_malle, 
	".PREFIX."reservation.id_periode,
	".PREFIX."reservation.retrait_lieu,
	".PREFIX."reservation.retrait_college,
	".PREFIX."reservation.retrait_commentaire,
	".PREFIX."reservation.retour_lieu,
	".PREFIX."reservation.retour_college,
	".PREFIX."reservation.retour_commentaire,

	".PREFIX."malle.titre,
	".PREFIX."malle.cote

	FROM ".PREFIX."reservation 

	LEFT JOIN ".PREFIX."malle

	ON ".PREFIX."malle.id_malle	= ".PREFIX."reservation.id_malle

	WHERE id_reservation=".$_POST['id_reservation']) or die("error 145");

$data_reservation = mysqli_fetch_assoc($req_reservation);

//determine transportation
$retrait_lieu = "";
if( $data_reservation['retrait_lieu'] == "canope" ){ $retrait_lieu = "Canopé de Moulins"; }
if( $data_reservation['retrait_lieu'] == "autre" ){ $retrait_lieu = "Autre"; }
if( $data_reservation['retrait_lieu'] == "college" ){ 
	$retrait_lieu = "Collège de "; 
	$temp = explode("#", $data_reservation['retrait_college']);
	$retrait_lieu .= utf8_encode($temp[3])." le ".$temp[2];
}
if( $data_reservation['retrait_commentaire'] != "" ){ $retrait_lieu .= " (".utf8_encode($data_reservation['retrait_commentaire']).")"; }

$retour_lieu = "";
if( $data_reservation['retour_lieu'] == "canope" ){ $retour_lieu = "Canopé de Moulins"; }
if( $data_reservation['retour_lieu'] == "autre" ){ $retour_lieu = "Autre"; }
if( $data_reservation['retour_lieu'] == "college" ){ 
	$retour_lieu = "Collège de "; 
	$temp = explode("#", $data_reservation['retour_college']);
	$retour_lieu .= utf8_encode($temp[3])." le ".$temp[2];
}
if( $data_reservation['retour_commentaire'] != "" ){ $retour_lieu .= " (".utf8_encode($data_reservation['retour_commentaire']).")"; }

//send mails
$mail = "";
$mail .= "Bonjour,".PHP_EOL.PHP_EOL;
$mail .= "Vous venez d'annuler une réservation :".PHP_EOL.PHP_EOL;
$mail .= "Emprunteur : ".utf8_encode($_SESSION['client_email']).PHP_EOL.PHP_EOL;
$mail .= "Malle : ".utf8_encode($data_reservation['titre']).PHP_EOL.PHP_EOL;
$mail .= "Période : ".$data_reservation['id_periode'].PHP_EOL.PHP_EOL;
$mail .= "Retrait : ".$retrait_lieu.PHP_EOL.PHP_EOL;
$mail .= "Retour : ".$retour_lieu.PHP_EOL.PHP_EOL;
$mail .= "L'équipe Canopé".PHP_EOL.PHP_EOL;
$mail .= "Fait le ".date("d/m/Y")." à ".date("H:i");

mail("contact.atelier03@reseau-canope.fr", utf8_decode("réservation annulée"), utf8_decode($mail), "FROM:canope-resamalles@ac-clermont.fr");
mail($_SESSION['client_email'], utf8_decode("réservation annulée"), utf8_decode($mail), "FROM:canope-resamalles@ac-clermont.fr");

//update malle
mysqli_query($connection, "UPDATE ".PREFIX."malle SET disponibilite".$data_reservation['id_periode']."='yes' WHERE id_malle=".$data_reservation['id_malle']) or die("error 964".mysqli_error($connection));

mysqli_query($connection, "DELETE FROM ".PREFIX."reservation WHERE id_reservation=".$_POST['id_reservation']) or die("error 9698".mysqli_error($connection));

echo "ok";

?>

<?php mysqli_close($connection);?>