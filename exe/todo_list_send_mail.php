<?php

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

$eol = PHP_EOL;

//----------------------------------------------
//-- relance retour documents
//----------------------------------------------

//vérifie si une date de tournée approche
$req_tournee = mysqli_query($connection, "SELECT DISTINCT date_retour FROM ".PREFIX."tournee") or die("erreur 002");
while( $data_tournee = mysqli_fetch_assoc($req_tournee)  ){

	//s'il y a une tournée dans les 4 jours :
	if( nombre_jour($data_tournee['date_retour']) > 0 && nombre_jour($data_tournee['date_retour']) <= 4 ){

		$date = $data_tournee['date_retour'];

		//rapport_retour
		$rapport_retour = "Retour des malles - Relance envoyée le ".date("d/m/Y")." pour la tournée du ".$date.$eol.$eol;

		//on sélectionne les emprunteurs un par un
		$req_emprunteur = mysqli_query($connection, "SELECT DISTINCT id_emprunteur, retour_college, emprunteur_prenom, emprunteur_nom, emprunteur_email FROM ".PREFIX."reservation WHERE finalisation=1 AND retour_college LIKE '%".$date."%'") or die("erreur 01");

		while( $data_emprunteur = mysqli_fetch_assoc($req_emprunteur)  ){

			//collège
			$temp = explode("#", $data_emprunteur['retour_college']);
			$college = utf8_encode(strtoupper($temp[3]));

			//message
			$message = "A l'attention de ".$data_emprunteur['emprunteur_prenom']." ".$data_emprunteur['emprunteur_nom']." :".$eol.$eol;
			$message .= "Madame, Monsieur,".$eol.$eol;
			$message .= "Vous avez emprunté une ou plusieurs malles que vous devez rapporter impérativement AVANT le ".$date." au collège de ".$college." :".$eol.$eol;

			//emprunteur
			$req_cyber = mysqli_query($connection_cyber, "SELECT * FROM mzp_client WHERE id_client=".$data_emprunteur['id_emprunteur']) or die("erreur 03655");
			$data_cyber = mysqli_fetch_assoc($req_cyber);

			//rapport
			$rapport_retour .= "Emprunteur : ".$data_emprunteur['emprunteur_prenom']." ".$data_emprunteur['emprunteur_nom']." (".$data_emprunteur['emprunteur_email'].")".$eol;

			//on chercher les réservations correspondantes
			$req_reservation = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE finalisation=1 AND id_emprunteur=".$data_emprunteur['id_emprunteur']." AND retour_college LIKE '%".$date."%' ORDER BY retour_college") or die("erreur 986451");

			while( $data_reservation = mysqli_fetch_assoc($req_reservation)  ){

				//malle
				$req_malle = mysqli_query($connection, "SELECT * FROM ".PREFIX."malle WHERE id_malle=".$data_reservation['id_malle']) or die("erreur 02");
				$data_malle = mysqli_fetch_assoc($req_malle);

				//vérifie si réservé à la période suivante, auquel cas il n'est pas nécessaire de récupérer la malle
				$req_periode_suivante = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE id_malle=".$data_reservation['id_malle']." AND id_emprunteur=".$data_reservation['id_emprunteur']." AND id_periode=".($data_reservation['id_periode'] + 1)." AND finalisation=1") or die("erreur 00365");

				if( mysqli_num_rows($req_periode_suivante) == 0 ){

					//message
					$message .= "- ".utf8_encode($data_malle['titre'])." [".utf8_encode($data_malle['cote'])."]".$eol;

					//rapport
					$rapport_retour .= "- ".utf8_encode($data_malle['titre'])." [".utf8_encode($data_malle['cote'])."]".$eol;

				}//fin if

			}//fin while reservation

			$message .= $eol."En cas de problème, veuillez nous contacter au plus vite au 04 70 46 07 66.".$eol.$eol;
			$message .= "Merci de votre compréhension. ".$eol.$eol;
			$message .= "L'équipe RésaMalles".$eol;

			//rapport
			$rapport_retour .= $eol;

			//echo utf8_decode($message)."<hr>";

			//envoi des relances
			mail($data_cyber['email'], utf8_decode("Malles à rapporter"), utf8_decode($message), "FROM:canope-resamalles@ac-clermont.fr");
			mail($data_emprunteur['emprunteur_email'], utf8_decode("Malles à rapporter"), utf8_decode($message), "FROM:canope-resamalles@ac-clermont.fr");

		}//fin while emprunteur

		//envoi du rapport
		mail("contact.atelier03@reseau-canope.fr", utf8_decode("Relance Retour Malles"), utf8_decode($rapport_retour), "FROM:canope-resamalles@ac-clermont.fr");

	}//fin if date <

}//fin while tournées retour

//----------------------------------------------
//-- relance dépôt documents
//----------------------------------------------

//vérifie si une date de tournée approche
$req_tournee = mysqli_query($connection, "SELECT DISTINCT date_depot FROM ".PREFIX."tournee") or die("erreur 2002");
while( $data_tournee = mysqli_fetch_assoc($req_tournee)  ){

	//s'il y a une tournée dans les 4 jours :
	if( nombre_jour($data_tournee['date_depot']) > 0 && nombre_jour($data_tournee['date_depot']) <= 4 ){

		$date = $data_tournee['date_depot'];

		//rapport_depot
		$rapport_depot = "Dépôt des malles - Rappel envoyé le ".date("d/m/Y")." pour la tournée du ".$date.$eol.$eol;

		//on sélectionne les emprunteurs un par un
		$req_emprunteur = mysqli_query($connection, "SELECT DISTINCT id_emprunteur, retrait_college, emprunteur_prenom, emprunteur_nom, emprunteur_email FROM ".PREFIX."reservation WHERE finalisation=1 AND retrait_college LIKE '%".$date."%'") or die("erreur 201");

		while( $data_emprunteur = mysqli_fetch_assoc($req_emprunteur)  ){

			//collège
			$temp = explode("#", $data_emprunteur['retrait_college']);
			$college = utf8_encode(strtoupper($temp[3]));

			//message
			$message = "A l'attention de ".$data_emprunteur['emprunteur_prenom']." ".$data_emprunteur['emprunteur_nom']." :".$eol.$eol;
			$message .= "Madame, Monsieur,".$eol.$eol;
			$message .= "Vous avez emprunté une ou plusieurs malles qui seront à votre disposition au collège de ".$college." le ".$date." en fin de journée :".$eol.$eol;

			//emprunteur
			$req_cyber = mysqli_query($connection_cyber, "SELECT * FROM mzp_client WHERE id_client=".$data_emprunteur['id_emprunteur']) or die("erreur 203655");
			$data_cyber = mysqli_fetch_assoc($req_cyber);

			//rapport
			$rapport_depot .= "Emprunteur : ".$data_emprunteur['emprunteur_prenom']." ".$data_emprunteur['emprunteur_nom']." (".$data_emprunteur['emprunteur_email'].")".$eol;

			//on chercher les réservations correspondantes
			$req_reservation = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE finalisation=1 AND id_emprunteur=".$data_emprunteur['id_emprunteur']." AND retrait_college LIKE '%".$date."%' ORDER BY retrait_college") or die("erreur 2986451");

			while( $data_reservation = mysqli_fetch_assoc($req_reservation)  ){

				//malle
				$req_malle = mysqli_query($connection, "SELECT * FROM ".PREFIX."malle WHERE id_malle=".$data_reservation['id_malle']) or die("erreur 202");
				$data_malle = mysqli_fetch_assoc($req_malle);

				//vérifie si réservé à la période précédente, auquel cas il n'est pas nécessaire de déposer la malle
				$req_periode_suivante = mysqli_query($connection, "SELECT * FROM ".PREFIX."reservation WHERE id_malle=".$data_reservation['id_malle']." AND id_emprunteur=".$data_reservation['id_emprunteur']." AND id_periode=".($data_reservation['id_periode'] - 1)." AND finalisation=1") or die("erreur 200365");

				if( mysqli_num_rows($req_periode_suivante) == 0 ){

					//message
					$message .= "- ".utf8_encode($data_malle['titre'])." [".utf8_encode($data_malle['cote'])."]".$eol;

					//rapport
					$rapport_depot .= "- ".utf8_encode($data_malle['titre'])." [".utf8_encode($data_malle['cote'])."]".$eol;

				}//fin if

			}//fin while reservation

			$message .= $eol."En cas de problème, veuillez nous contacter au plus vite au 04 70 46 07 66.".$eol.$eol;
			$message .= "Merci de votre compréhension. ".$eol.$eol;
			$message .= "L'équipe RésaMalles".$eol;

			//rapport
			$rapport_depot .= $eol;

			//envoi des relances
			mail($data_cyber['email'], utf8_decode("Dépôt de malles"), utf8_decode($message), "FROM:canope-resamalles@ac-clermont.fr");
			mail($data_emprunteur['emprunteur_email'], utf8_decode("Dépôt de malles"), utf8_decode($message), "FROM:canope-resamalles@ac-clermont.fr");

		}//fin while emprunteur

		//envoi du rapport
		mail("contact.atelier03@reseau-canope.fr", utf8_decode("Rappel Dépôt Malles"), utf8_decode($rapport_depot), "FROM:canope-resamalles@ac-clermont.fr");

	}//fin if date <

}//fin while tournées retour

function nombre_jour($date){

	list($jour1, $mois1, $annee1) = explode('/', date("d/m/Y"));
	list($jour2, $mois2, $annee2) = explode('/', $date);
	 
	$timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1);
	$timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2);

	$nb_jour = round(($timestamp2 - $timestamp1) / (60*60*24));

	return $nb_jour;

}


?>

<?php mysqli_close($connection);?>
<?php mysqli_close($connection_cyber);?>