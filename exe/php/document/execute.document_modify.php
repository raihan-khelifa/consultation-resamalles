<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

//update the document
mysqli_query($connection, "UPDATE ".PREFIX."malle SET numero_exemplaire='".safemode_injection($_POST['numero_exemplaire'])."', titre='".safemode_injection($_POST['titre'])."', cote='".safemode_injection($_POST['cote'])."', nombre_exemplaire='".safemode_injection($_POST['nombre_exemplaire'])."', niveau='".safemode_injection($_POST['niveau'])."', type='".safemode_injection($_POST['type'])."', resume='".safemode_injection($_POST['resume'])."', mot_cle='".safemode_injection($_POST['mot_cle'])."', langue='".safemode_injection($_POST['langue'])."', emplacement='".safemode_injection($_POST['emplacement'])."' WHERE id_malle=".$_POST['id_malle']." AND jeton_malle='".$_POST['jeton_malle']."'") or die("error 8897".mysqli_error($connection));

echo '<script type="text/javascript">window.parent.location.href = "../../index.php";</script>';

?>

<?php mysqli_close($connection);?>