<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');

//lock
if( !isset( $_SESSION['status'] ) ){ die("Votre session a expiré, vous devez vous reconnecter."); }

//get reservation
$req_reservation = mysqli_query($connection, "SELECT id_malle, id_periode FROM ".PREFIX."reservation WHERE id_reservation=".$_SESSION['id_reservation']) or die("error 145");
$data_reservation = mysqli_fetch_assoc($req_reservation);

//update malle
mysqli_query($connection, "UPDATE ".PREFIX."malle SET disponibilite".$data_reservation['id_periode']."='yes' WHERE id_malle=".$data_reservation['id_malle']) or die("error 964".mysqli_error($connection));

mysqli_query($connection, "DELETE FROM ".PREFIX."reservation WHERE id_reservation=".$_SESSION['id_reservation']) or die("error 258".mysqli_error($connection));

echo $data_reservation['id_malle'].";".$data_reservation['id_periode'];

?>

<?php mysqli_close($connection);?>