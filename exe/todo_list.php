<?php

//session
session_start();

if( !isset($_SESSION['status']) ){ die("Fin de session. Veuillez vous reconnecter."); }

//globals
include('include/global.php');

//connection
include('include/connection.php');

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
	<title>Réservations de malles</title>
	<link rel="stylesheet" href="css/font_awesome/css/font-awesome.min.css">

</head>
<body>

	<?php

	$date = $_GET['date'];
	$depot_ou_retour = "";

	//détermine si dépôt ou retour
	$req_tournee = mysqli_query($connection, "SELECT id_tournee FROM ".PREFIX."tournee WHERE date_retour='".$date."'") or die("erreur 002");
	if( mysqli_num_rows($req_tournee) > 0 ){ 
		$depot_ou_retour = "à récupérer";
	}

	$req_tournee = mysqli_query($connection, "SELECT id_tournee FROM ".PREFIX."tournee WHERE date_depot='".$date."'") or die("erreur 001");
	if( mysqli_num_rows($req_tournee) > 0 ){ 
		$depot_ou_retour = "à déposer";
	}

	if( $depot_ou_retour == "" ){ die("Date non valide"); }

	echo "<h1>Feuille de route du ".$date."</h1>";

	echo "<h2>Documents ".$depot_ou_retour."</h2>";

	if( $depot_ou_retour == "à récupérer" ){

		//à récupérer
		$req_reservation = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE finalisation=1 AND retour_college LIKE '%".$date."%' ORDER BY retour_college") or die("erreur 986451");

		while( $data_reservation = mysqli_fetch_assoc($req_reservation)  ){

			//collège
			$temp = explode("#", $data_reservation['retour_college']);
			$college = utf8_encode(strtoupper($temp[3]));

			//malle
			$req_malle = mysqli_query($connection, "SELECT * FROM ".PREFIX."malle WHERE id_malle=".$data_reservation['id_malle']) or die("erreur 02");
			$data_malle = mysqli_fetch_assoc($req_malle);

			//emprunteur
			$req_emprunteur = mysqli_query($connection_cyber, "SELECT * FROM mzp_client WHERE id_client=".$data_reservation['id_emprunteur']) or die("erreur 03");
			$data_emprunteur = mysqli_fetch_assoc($req_emprunteur);

			//divers
			if( $data_reservation['retour_commentaire'] != "" ){ $divers = "(".utf8_encode($data_reservation['retour_commentaire']).")"; }else{ $divers = ""; }

			//vérifie si réservé à la période suivante, auquel cas il n'est pas nécessaire de récupérer la malle
			$req_periode_suivante = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE id_malle=".$data_reservation['id_malle']." AND id_emprunteur=".$data_reservation['id_emprunteur']." AND id_periode=".($data_reservation['id_periode'] + 1)." AND finalisation=1") or die("erreur 00365");

			if( mysqli_num_rows($req_periode_suivante) > 0 ){ 
				$texte_periode_suivante = "<p>>>>>> ATTENTION : cet emprunteur a aussi réservé cette malle pour la période suivante. Il n'est peut-être pas nécessaire de la récupérer.</p>";
			}else{
				$texte_periode_suivante = "";
			}

			echo "<strong>".$college." : </strong>".utf8_encode($data_malle['titre'])." [".$data_malle['cote']."] - emprunté par ".utf8_encode($data_emprunteur['structure'])." ".utf8_encode($data_emprunteur['organisme'])." ".utf8_encode($data_emprunteur['commune'])." ".$divers.$texte_periode_suivante."<hr>";

		}//fin while

	}else{

		//à déposer
		$req_reservation = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE finalisation=1 AND retrait_college LIKE '%".$date."%' ORDER BY retrait_college") or die("erreur 01986451");
		while( $data_reservation = mysqli_fetch_assoc($req_reservation)  ){

			$temp = explode("#", $data_reservation['retrait_college']);
			$college = utf8_encode(strtoupper($temp[3]));

			//malle
			$req_malle = mysqli_query($connection, "SELECT * FROM ".PREFIX."malle WHERE id_malle=".$data_reservation['id_malle']) or die("erreur 02");
			$data_malle = mysqli_fetch_assoc($req_malle);

			//emprunteur
			$req_emprunteur = mysqli_query($connection_cyber, "SELECT * FROM mzp_client WHERE id_client=".$data_reservation['id_emprunteur']) or die("erreur 03");
			$data_emprunteur = mysqli_fetch_assoc($req_emprunteur);

			//divers
			if( $data_reservation['retrait_commentaire'] != "" ){ $divers = "(".utf8_encode($data_reservation['retrait_commentaire']).")"; }else{ $divers = ""; }

			//vérifie si réservé à la période précédente, auquel cas il n'est pas nécessaire de déposer la malle
			$req_periode_suivante = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE id_malle=".$data_reservation['id_malle']." AND id_emprunteur=".$data_reservation['id_emprunteur']." AND id_periode=".($data_reservation['id_periode'] - 1)." AND finalisation=1") or die("erreur 9874");

			if( mysqli_num_rows($req_periode_suivante) > 0 ){ 
				$texte_periode_suivante = "<p>>>>>> ATTENTION : La malle a déjà été réservée par cet emprunteur pour la période précédente. Elle est certainement déjà en sa possession.</p>";
			}else{
				$texte_periode_suivante = "";
			}

			echo "<strong>".$college." : </strong>".utf8_encode($data_malle['titre'])." [".$data_malle['cote']."] - emprunté par ".utf8_encode($data_emprunteur['structure'])." ".utf8_encode($data_emprunteur['organisme'])." ".utf8_encode($data_emprunteur['commune'])." ".$divers.$texte_periode_suivante."<hr>";

		}//fin while

	}

	?>

</body>
</html>

<?php mysqli_close($connection);?>
<?php mysqli_close($connection_cyber);?>