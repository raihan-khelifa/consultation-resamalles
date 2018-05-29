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

	<div id="popup_document_add">
		<form id="form_document_add" method="post" action="execute.document_add.php"> 

			<div id="document_title">Ajouter une malle</div>

			<?php

			//get last_index
			$req_lastid = mysqli_query($connection, "SELECT numero_exemplaire FROM ".PREFIX."malle ORDER BY numero_exemplaire DESC LIMIT 1") or die("erreur 234167");
			$data_lastid = mysqli_fetch_assoc($req_lastid);

			?>

			<div class="input"><label>Titre *</label><span><input type="text" name="titre" id="titre" required="required"></span></div>
			<div class="input"><label>Numéro d'exemplaire *</label><span><input type="number" name="numero_exemplaire" id="numero_exemplaire" value="<?php echo ($data_lastid['numero_exemplaire'] + 1);?>"></span></div>
			<div class="input"><label>Emplacement</label><span><input type="text" name="emplacement"></span></div>
			<div class="input"><label>Cote</label><span><input type="text" name="cote"></span></div>
			<div class="input"><label>Niveau</label><span><input type="text" name="niveau"></span></div>
			<div class="input"><label>Nombre de documents</label><span><input type="number" name="nombre_exemplaire"></span></div>
			<div class="input"><label>Mots-clés</label><span><input type="text" name="mot_cle"></span></div>
			<div class="input"><label>Résumé</label><span><textarea name="resume"></textarea></span></div>
			<div class="input"><label>Langue</label><span><input type="text" name="langue"></span></div>
			<div class="input"><label>Type</label><span><select name="type"><option>Malle pédagogique</option><option>Malle thématique</option></select></span></div>

			<p><button type="submit" id="button_add_document">Valider</button></p>

		</form>
	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>