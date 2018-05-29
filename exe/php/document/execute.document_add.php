<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

//insert the document
mysqli_query($connection, "INSERT INTO ".PREFIX."malle(jeton_malle,numero_exemplaire,titre,cote,nombre_exemplaire,niveau,type,resume,mot_cle,langue,emplacement) VALUES('".md5(date("YmdHis"))."','".safemode_injection($_POST['numero_exemplaire'])."','".safemode_injection($_POST['titre'])."','".safemode_injection($_POST['cote'])."','".safemode_injection($_POST['nombre_exemplaire'])."','".safemode_injection($_POST['niveau'])."','".safemode_injection($_POST['type'])."','".safemode_injection($_POST['resume'])."','".safemode_injection($_POST['mot_cle'])."','".safemode_injection($_POST['langue'])."','".safemode_injection($_POST['emplacement'])."')") or die("error 9974".mysqli_error($connection));

echo "<script type='text/javascript'>window.parent.location.reload()</script>";

?>

<?php mysqli_close($connection);?>