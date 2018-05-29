<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

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

	<div id="popup_my_reservation">

		<?php

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

			WHERE id_emprunteur=".$_SESSION['id_client']."

			ORDER BY id_periode

			") or die("die 65874".mysqli_error($connection));

		?>

		<div id="document_title">Réservations en cours (<span class="my_reservation_counter"><?php echo mysqli_num_rows($req_reservation);?></span>)</div>

		<div id="reservation_list">

			<?php

			//reservation list

			while( $data_reservation = mysqli_fetch_assoc($req_reservation) ){

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

			?>

				<div class="reservation_zone">
					
					<div class="reservation" data_id_reservation="<?php echo $data_reservation['id_reservation'];?>" data_id_malle="<?php echo $data_reservation['id_malle'];?>" data_id_periode="<?php echo $data_reservation['id_periode'];?>"><p class="titre"><strong>P<?php echo $data_reservation['id_periode'];?></strong> : <?php echo utf8_encode($data_reservation['titre']);?></p><p class="retrait"><span class="bold green">Retrait</span> : <?php echo ($retrait_lieu);?></p><p class="retour"><span class="bold green">Retour</span> : <?php echo ($retour_lieu);?></p></div><div class="button_cancel_reservation button">Annuler</div>

				</div>

			<?php }	?>

		</div>

	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>