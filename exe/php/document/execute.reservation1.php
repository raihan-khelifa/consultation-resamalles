<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

//new reservation or back button ?
if( isset( $_GET['id_malle'] ) ){

	//new reservation
	if( isset($_GET['id_client']) ){
		//admin
		$id_client = $_GET['id_client'];
	}else{
		//client
		$id_client = $_SESSION['id_client'];
	}

	//check if available
	$req_available = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE id_malle=".$_GET['id_malle']." AND id_periode=".$_GET['id_periode']) or die("error 99896");

	if( mysqli_num_rows($req_available) > 1 ){
		die("La malle n'est plus disponible.");
	}

	//insert reservation
	mysqli_query($connection, "INSERT INTO ".PREFIX."reservation(jeton_reservation,id_malle,id_periode,id_emprunteur,timestamp,finalisation) VALUES('".md5(date("YmdHis"))."','".$_GET['id_malle']."','".$_GET['id_periode']."','".$id_client."','".date("Y-m-d H:i:s")."','0')") or die("error 9985".mysqli_error($connection));

	//get lastid
	$req_lastid = mysqli_query($connection, "SELECT id_reservation FROM ".PREFIX."reservation ORDER BY id_reservation DESC LIMIT 1") or die("erreur 234167");
	$data_lastid = mysqli_fetch_assoc($req_lastid);

	//update malle
	mysqli_query($connection, "UPDATE ".PREFIX."malle SET disponibilite".$_GET['id_periode']."='no' WHERE id_malle=".$_GET['id_malle']) or die("error 12548".mysqli_error($connection));
	echo '<script> current_counter= parseInt(window.parent.return_reservation_counter()); new_counter=parseInt(current_counter+1); window.parent.refresh_my_reservation_counter(new_counter); window.parent.refresh_available_flag('.$_GET['id_malle'].','.$_GET['id_periode'].',"no");</script>';

	//save details
	$req_document = mysqli_query($connection, "SELECT * FROM ".PREFIX."malle WHERE id_malle=".$_GET['id_malle']." AND jeton_malle='".$_GET['jeton_malle']."'") or die("error 1254".mysqli_error($req_document));
	$data_document = mysqli_fetch_assoc($req_document);

	$_SESSION['id_malle'] = $_GET['id_malle'];
	$_SESSION['jeton_malle'] = $data_document['jeton_malle'];
	$_SESSION['titre'] = $data_document['titre'];
	$_SESSION['numero_exemplaire'] = $data_document['numero_exemplaire'];
	$_SESSION['id_periode'] = $_GET['id_periode'];
	$_SESSION['id_reservation'] = $data_lastid['id_reservation'];

	$_SESSION['retrait_lieu'] = "";
	$_SESSION['retrait_commentaire'] = "";
	$_SESSION['retrait_date'] = "";
	$_SESSION['retrait_college'] = "";
	$_SESSION['retrait_id_tournee'] = "";

	$_SESSION['emprunteur_nom'] = "";
	$_SESSION['emprunteur_prenom'] = "";
	$_SESSION['emprunteur_email'] = "";

}else{

	//back button or modification



}//end if

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

	<div id="popup_reservation1">

		<form id="form_reservation1" method="post" action="execute.reservation2.php"> 

			<div id="document_title">Retrait des documents</div>

			<p class="detail"><?php echo utf8_encode($_SESSION['titre']);?> - <?php echo "P".$_SESSION['id_periode'];?></p>
			<p class="label">Où souhaitez-vous retirer la malle ?</p>

			<p class="radio"><input type="radio" name="retrait" id="retrait_canope" value="canope" checked="checked"><label for="retrait_canope">au Canopé de Moulins</label></p>
			<p class="radio"><input type="radio" name="retrait" id="retrait_college" value="college" <?php if( $_SESSION['retrait_lieu']=="college" ){ echo 'checked="checked"'; } ?>><label for="retrait_college">dans le collège de votre choix</label>
			<select name="college_retrait" id="college_retrait">
				<option value="0">Sélectionnez le collège et la date de passage...</option>
				<?php

				//get tournee
				$req_tournee = mysqli_query($connection, "SELECT id_tournee,college,date_depot,numero_tournee FROM ".PREFIX."tournee WHERE id_periode=".$_SESSION['id_periode']." ORDER BY college") or die("error 9987".mysqli_error($req_document));
				while( $data_tournee = mysqli_fetch_assoc($req_tournee) ){

					//select college
					if( $_SESSION['retrait_id_tournee'] == $data_tournee['id_tournee'] ){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}

					echo '<option '.$selected.' value="'.$data_tournee['id_tournee'].'#'.$data_tournee['numero_tournee'].'#'.$data_tournee['date_depot'].'#'.utf8_encode($data_tournee['college']).'">'.$data_tournee['date_depot'].' : '.utf8_encode($data_tournee['college']).'</option>';

				}

				?>
			</select></p>

			<p class="radio" style="visibility: hidden;"><input type="radio" name="retrait" id="retrait_autre" value="autre" <?php if( $_SESSION['retrait_lieu']=="autre" ){ echo 'checked="checked"'; } ?>><label for="retrait_autre">autre</label> <input type="text" placeholder="Précisions libres..." name="information" class="information" value="<?php echo $_SESSION['retrait_commentaire'];?>" /></p>

			<!-- <p class="information"><textarea placeholder="Précisions libres..." name="information"><?php echo $_SESSION['retrait_commentaire'];?></textarea></p>-->

			<p class="label">Emprunteur :</p>

			<input type="text" placeholder="Nom" required="required" name="user_name" class="user_info" value="<?php echo $_SESSION['emprunteur_nom'];?>" /><input type="text" placeholder="Prénom" required="required" name="user_surname" class="user_info" value="<?php echo $_SESSION['emprunteur_prenom'];?>" /><input type="text" placeholder="Email" required="required" name="user_email" class="user_info" value="<?php echo $_SESSION['emprunteur_email'];?>" />

			<p class="menu"><span id="button_cancel_reservation" class="button">Annuler la réservation</span><input type="submit" value="Suivant" id="button_reservation2" class="button" /></p>

		</form>

	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>