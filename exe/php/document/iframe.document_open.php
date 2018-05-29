<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

//---------------------------------------
//-- connection_cyber
//---------------------------------------

$server_cyber = "212.227.20.146"; 
$user_cyber = "cyber_hibou"; 
$pwd_cyber = "wz4Rczjm8yUQ8e6y"; 
$db_cyber = "cyber_service";
$connection_cyber = mysqli_connect($server_cyber, $user_cyber, $pwd_cyber, $db_cyber);

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

	<?php

	$req_document = mysqli_query($connection, "SELECT jeton_malle, numero_exemplaire, titre, nombre_exemplaire, niveau, emplacement, resume, langue, mot_cle, cote FROM ".PREFIX."malle WHERE id_malle=".$_GET['id_malle']) or die("error 014".mysqli_error($connection));

	$data_document = mysqli_fetch_assoc($req_document);

	?>

	<div id="document_title">
		
		<?php echo utf8_encode($data_document['titre']).' ['.$data_document['numero_exemplaire'].']';?>

	</div>

	<div id="document_detail">

		<p>Emplacement : <span><?php echo utf8_encode($data_document['emplacement']);?></span></p>

		<p>Cote : <span><?php echo utf8_encode($data_document['cote']);?></span></p>

		<p>Niveau : <span><?php echo utf8_encode($data_document['niveau']);?></span></p>

		<p>Nombre de documents : <span><?php echo utf8_encode($data_document['nombre_exemplaire']);?></span></p>

		<p>Résumé :</p>

		<div id="resume"><span><?php echo utf8_encode($data_document['resume']);?></span></div>

		<p>Mots clés :</p>
		
		<div id="mot_cle"><span><?php echo utf8_encode($data_document['mot_cle']);?></span></div>

	</div>

	<div id="document_reservation" data_id_malle="<?php echo $_GET['id_malle'];?>" data_jeton_malle="<?php echo $_GET['jeton_malle'];?>" data_user_status="<?php echo $_SESSION['status'];?>">

		<?php

		//empty uncomplete reservations (30 minutes)
		$req_uncomplete = mysqli_query($connection, "SELECT id_reservation, timestamp, id_malle, id_periode FROM ".PREFIX."reservation WHERE finalisation=0") or die("error 00235");
		while( $data_uncomplete = mysqli_fetch_assoc($req_uncomplete) ){

			$timeFirst  = strtotime( $data_uncomplete['timestamp'] );
			$timeSecond = strtotime( date("Y-m-d H:i:s") );
			$differenceInSeconds = $timeSecond - $timeFirst;

			if( $differenceInSeconds > 180 ){

				//update malle
				mysqli_query($connection, "UPDATE ".PREFIX."malle SET disponibilite".$data_uncomplete['id_periode']."='yes' WHERE id_malle=".$data_uncomplete['id_malle']) or die("error 78788".mysqli_error($connection));
				mysqli_query($connection, "DELETE FROM ".PREFIX."reservation WHERE id_reservation=".$data_uncomplete['id_reservation']) or die("error 44587");
			}

		}

		//get periode
		$req_periode = mysqli_query($connection, "SELECT id_periode, nom, date_html FROM ".PREFIX."periode") or die("error 100210");
		while( $data_periode = mysqli_fetch_assoc($req_periode) ){

			//get reservation
			$req_reservation = mysqli_query($connection, "SELECT id_reservation, id_emprunteur, emprunteur_nom, emprunteur_prenom FROM ".PREFIX."reservation WHERE id_malle=".$_GET['id_malle']." AND id_periode=".$data_periode['id_periode']) or die("error 00245");

			if( mysqli_num_rows($req_reservation) == 0 ){

				$disponible = '<button class="button_reservation">Réserver</button>';
				$style_span = "";

			}else{

				if( $_SESSION['status']=="superadmin" || $_SESSION['status']=="admin" ){

					$data_reservation = mysqli_fetch_assoc($req_reservation);

					//get cyber user
					$req_user = mysqli_query($connection_cyber, "SELECT email,organisme,structure,commune FROM mzp_client WHERE id_client=".$data_reservation['id_emprunteur']) or die("error 669858");
					$data_user = mysqli_fetch_assoc($req_user);

					if($data_reservation['emprunteur_prenom']!="" || $data_reservation['emprunteur_nom']!=""){
						$emprunteur = " (".$data_reservation['emprunteur_prenom']." ".$data_reservation['emprunteur_nom'].")";
					}else{
						$emprunteur = "";
					}

					$user = utf8_encode($data_user['structure']." ".$data_user['organisme']." ".$data_user['commune'].$emprunteur);

					$disponible = "<a href='a' title='".stripslashes($user)."'>".$user."</a>";
					$style_span = ' style="line-height:12px;"';

				}else{

					$disponible = 'non disponible';
					$style_span = "";

				}//fin if

			}

			echo '<p data_id_periode="'.$data_periode['id_periode'].'" title="Cette période débute le '.$data_periode['date_html'].'"><label>'.$data_periode['nom'].'</label><span'.$style_span.'>'.$disponible.'</span></p>';

		}

		?>
		
	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>
<?php mysqli_close($connection_cyber);?>