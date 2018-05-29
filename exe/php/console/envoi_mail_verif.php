<?php

$message = "Madame, Monsieur,
 
Vous avez réservé une ou plusieurs malle(s) pour cette année scolaire 2016-2017. Un nouvel outil sera mis en ligne très prochainement lequel vous permettra de réserver vos malles en toute autonomie.
 
Afin de nous assurer que toutes vos réservations ont bien été reportées dans cette nouvelle interface, nous vous invitons à bien vouloir suivre la procédure suivante :
 
- allez sur http://resamalles.canope-aura.fr 
- dans le champ Email abonné Canopé, saisissez le mail de votre école.
- dans le champ Mot de passe, saisissez le mot de passe Abonné de l'école (celui utilisé pour vous abonner sur crdp-cyberservices.ac-clermont.fr).
- si vous avez oublié le mot de passe, allez sur crdp-cyberservices.ac-clermont.fr et cliquez sur le lien Codes Perdus en bas à gauche.
- une fois connecté, cliquez sur Réservations en cours dans le bandeau noir sous le logo Canopé : vous devriez retrouver vos réservations de l'année. 
- si vos réservations n'apparaissent pas, nous vous invitons à les saisir à nouveau en cliquant sur les malles de votre choix.
- si une réservation est erronée, nous vous invitons à la supprimer et à la saisir à nouveau. 
 
Pour toute question, n'hésitez pas à nous écrire à canope-resamalles@ac-clermont.fr.

Merci de votre compréhension.
 
Bien cordialement, 
 
L'équipe Canopé

";

$fichier = fopen("mail_verif.txt",'r');

	while (!feof($fichier)) {

		$email = fgets($fichier, 4096);

		mail($email, utf8_decode("réservation de malle"), utf8_decode($message), "FROM:canope-resamalles@ac-clermont.fr");

	}//fin while

fclose ($fichier);

echo date("H:i:s");

?>