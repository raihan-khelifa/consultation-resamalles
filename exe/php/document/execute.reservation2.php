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
$_SESSION['retrait_lieu'] = $_POST['retrait'];
$_SESSION['retrait_commentaire'] = $_POST['information'];
if( $_POST['retrait'] == "college" ){

	$temp = explode("#", $_POST['college_retrait']);
	$_SESSION['retrait_id_tournee'] = $temp[0];
	$_SESSION['retrait_date'] = $temp[2];
	$_SESSION['retrait_college'] = $temp[3];

}else{

	$_SESSION['retrait_id_tournee'] = "";
	$_SESSION['retrait_date'] = "";
	$_SESSION['retrait_college'] = "";

}
$_SESSION['emprunteur_nom'] = $_POST['user_name'];
$_SESSION['emprunteur_prenom'] = $_POST['user_surname'];
$_SESSION['emprunteur_email'] = $_POST['user_email'];

//update reservation
mysqli_query($connection, "UPDATE ".PREFIX."reservation SET retrait_lieu='".safemode_injection($_POST['retrait'])."', retrait_college='".safemode_injection($_POST['college_retrait'])."', retrait_commentaire='".safemode_injection($_POST['information'])."', emprunteur_nom='".safemode_injection($_POST['user_name'])."',  emprunteur_prenom='".safemode_injection($_POST['user_surname'])."',  emprunteur_email='".safemode_injection($_POST['user_email'])."' WHERE id_reservation=".$_SESSION['id_reservation']) or die("error 01478".mysqli_error($connection));

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

	<div id="popup_reservation2">

		<form id="form_reservation2" method="post" action="execute.reservation3.php"> 

			<div id="document_title">Retour des documents</div>

			<p class="detail"><?php echo utf8_encode($_SESSION['titre']);?> - <?php echo "P".$_SESSION['id_periode'];?></p>
			<p class="label">Où souhaitez-vous retourner la malle ?</p>

			<p class="radio"><input type="radio" name="retour" id="retour_canope" value="canope" checked="checked"><label for="retour_canope">au Canopé de Moulins</label></p>
			<p class="radio"><input type="radio" name="retour" id="retour_college" value="college"><label for="retour_college">dans le collège de votre choix</label>
			<select name="college_retour" id="college_retour">
				<option value="0">Sélectionnez le collège et la date de passage...</option>
				<?php

				//get tournee
				$req_tournee = mysqli_query($connection, "SELECT id_tournee,college,date_retour,numero_tournee FROM ".PREFIX."tournee WHERE id_periode=".$_SESSION['id_periode']." ORDER BY college") or die("error 9987".mysqli_error($req_document));
				while( $data_tournee = mysqli_fetch_assoc($req_tournee) ){

					echo '<option value="'.$data_tournee['id_tournee'].'#'.$data_tournee['numero_tournee'].'#'.$data_tournee['date_retour'].'#'.utf8_encode($data_tournee['college']).'">'.$data_tournee['date_retour'].' : '.utf8_encode($data_tournee['college']).'</option>';

				}

				?>
			</select></p>
			<p class="radio" style="visibility: hidden;"><input type="radio" name="retour" id="retour_autre" value="autre"><label for="retour_autre">autre</label> <input type="text" placeholder="Précisions libres..." name="information2" class="information" /></p>

			<!-- <p class="information"><textarea placeholder="Précisions libres..." name="information"></textarea></p>-->

			<p class="menu"><span id="button_cancel_reservation" class="button">Annuler la réservation</span><span id="button_back_reservation1" class="button">Retour</span><input type="submit" value="Suivant" id="button_reservation3" class="button" /></p>

		</form>

	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>