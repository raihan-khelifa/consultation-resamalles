<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');


//---------------------------------------
//-- connection_cyber
//---------------------------------------

$server_cyber = "localhost"; 
$user_cyber = "root"; 
$pwd_cyber = ""; 
$db_cyber = "cyber_service";
$connection_cyber = mysqli_connect($server_cyber, $user_cyber, $pwd_cyber, $db_cyber);

?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Popup</title>
	<link rel="stylesheet" href="../../css/global_read_only.css">
	<link rel="stylesheet" href="../../css/popup_document_read_only.css">
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

	

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>
<?php mysqli_close($connection_cyber);?>