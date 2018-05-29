<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ header("Location:../../index.php"); }

?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Réservations de malles</title>
	<link rel="stylesheet" href="../../css/global.css">
	<link rel="stylesheet" href="../../css/edit_reservation.css">
	<link rel="stylesheet" href="../../css/font_awesome/css/font-awesome.min.css">

</head>
<body>

	<div id="header">
		<div class="left"><img src="../../media/canope.png" /></div>
		<div class="right">Réservations de malles<?php if(isset($_SESSION['client_email'])){echo "<p>".$_SESSION['client_email']."</p>";}?></div>
	</div>

	<div id="menu">
		<span id="button_home">Retour</span> | <span id="button_logout" title="Déconnexion <?php echo $_SESSION['status'];?>">Déconnexion</span>
	</div>

	<div id="search_zone">
		<p><input type="text" id="search" placeholder="Moteur de recherche" class="field"> <button id="button_search">Chercher</button></p>
	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/edit_reservation.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>