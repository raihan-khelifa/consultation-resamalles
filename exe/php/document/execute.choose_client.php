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

	<div id="document_title">Liste des emprunteurs - Académie de Clermont-Ferrand</div>

	<div id="client_zone">
		<select id="id_client" size="15">
		<?php

		//get clients with valid subscription

		//---------------------------------------
		//-- connection_cyber
		//---------------------------------------

		
		$server_cyber = "212.227.20.146"; 
		$user_cyber = "cyber_hibou"; 
		$pwd_cyber = "wz4Rczjm8yUQ8e6y"; 
		$db_cyber = "cyber_service";

		$connection_cyber = mysqli_connect($server_cyber, $user_cyber, $pwd_cyber, $db_cyber);

		$req_client = mysqli_query($connection_cyber, "SELECT 

			mzp_client.id_client,
			mzp_client.code_postal,
			mzp_client.structure,
			mzp_client.organisme,
			mzp_client.commune,
			mzp_client.rne,

			mzp_association_client_service.id_service,
			mzp_association_client_service.date_anniversaire,
			mzp_association_client_service.validite,
			mzp_association_client_service.facture

			FROM mzp_client 

			LEFT join mzp_association_client_service

			ON mzp_association_client_service.id_client = mzp_client.id_client

			WHERE (code_postal LIKE '03%' || code_postal LIKE '15%' || code_postal LIKE '43%' || code_postal LIKE '63%') AND rne!='' 

			ORDER BY code_postal, commune") or die("error 95");
		
		while( $data_client = mysqli_fetch_assoc($req_client) ){

			//determine subscription color
			//determine subscription color
			$date_anniversaire = substr($data_client['date_anniversaire'], 0, 4);

			if( (( date("m") >= "09" && date("m") <= "12" ) && $date_anniversaire == date("Y")) || (( date("m") >= "01" && date("m") <= "08" ) && $date_anniversaire < date("Y")) ){
				$color = 'red';
			}else{
				$color = 'black';
			}

			echo '<option style="color:'.$color.';" value="'.$data_client['id_client'].'">'.$data_client['code_postal'].' '.utf8_encode($data_client['commune']).' : '.utf8_encode($data_client['structure']).' - '.utf8_encode($data_client['organisme']).' - '.$data_client['rne'].'</option>';

		}

		?>			
		</select>

		<p><button id="button_back_to_document">Retour</button></p>

	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection_cyber);?>
<?php mysqli_close($connection);?>