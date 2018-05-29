<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

mysqli_query($connection, "DELETE FROM ".PREFIX."malle WHERE id_malle=".$_POST['id_malle']." AND jeton_malle='".$_POST['jeton_malle']."'") or die("error 7888798".mysqli_error($connection));

?>

<?php mysqli_close($connection);?>