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
		<form id="form_document_modify" method="post" action="execute.document_modify.php"> 

			<div id="document_title">Modifier une malle</div>

			<?php

			//get document
			$req_document = mysqli_query($connection, "SELECT * FROM ".PREFIX."malle WHERE id_malle=".$_GET['id_malle']) or die("erreur 7878");
			$data_document = mysqli_fetch_assoc($req_document);

			?>

			<input type="hidden" name="id_malle" value="<?php echo safemode_affichage_html($data_document['id_malle']);?>">
			<input type="hidden" name="jeton_malle" value="<?php echo safemode_affichage_html($data_document['jeton_malle']);?>">

			<div class="input"><label>Titre *</label><span><input type="text" name="titre" id="titre" value="<?php echo safemode_affichage_html($data_document['titre']);?>"></span></div>
			<div class="input"><label>Numéro d'exemplaire *</label><span><input type="number" name="numero_exemplaire" id="numero_exemplaire" value="<?php echo safemode_affichage_html($data_document['numero_exemplaire']);?>"></span></div>
			<div class="input"><label>Emplacement</label><span><input type="text" name="emplacement" value="<?php echo safemode_affichage_html($data_document['emplacement']);?>"></span></div>
			<div class="input"><label>Cote</label><span><input type="text" name="cote" value="<?php echo safemode_affichage_html($data_document['cote']);?>"></span></div>
			<div class="input"><label>Niveau</label><span><input type="text" name="niveau" value="<?php echo safemode_affichage_html($data_document['niveau']);?>"></span></div>
			<div class="input"><label>Nombre de documents</label><span><input type="number" name="nombre_exemplaire" value="<?php echo safemode_affichage_html($data_document['nombre_exemplaire']);?>"></span></div>
			<div class="input"><label>Mots-clés</label><span><input type="text" name="mot_cle" value="<?php echo safemode_affichage_html($data_document['mot_cle']);?>"></span></div>
			<div class="input"><label>Résumé</label><span><textarea name="resume"> <?php echo safemode_affichage_html($data_document['resume']);?></textarea></span></div>
			<div class="input"><label>Langue</label><span><input type="text" name="langue" value="<?php echo safemode_affichage_html($data_document['langue']);?>"></span></div>
			<div class="input"><label>Type</label><span><select name="type"><option>Malle pédagogique</option><option>Malle thématique</option></select></span></div>

			<p><button id="button_modify_document">Valider</button></p>

		</form>
	</div>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/document_add.js"></script>

</body>
</html>

<?php mysqli_close($connection);?>