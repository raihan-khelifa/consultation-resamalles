<?php

//session
session_start();

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

//check if user is admin or client

if( ($_POST['client_email']=="superadmin" && md5($_POST['client_pwd'])=="ea702ba4205cb37a88cc84851690a7a5") || ($_POST['client_email']=="admin" && md5($_POST['client_pwd'])=="18b71dcb847864d49de2b32cd30ca2af") ){

	//get superadmin
	$_SESSION['id_client'] = 0;
	$_SESSION['client_email'] = $_POST['client_email'];
	$_SESSION['status'] = $_POST['client_email'];
	echo "admin";

}else{

	//user is client

	//get_client
	$req_client = mysqli_query($connection_cyber, "SELECT id_client, mdp, structure, organisme, commune FROM mzp_client WHERE email='".$_POST['client_email']."'") or die("error 95");

	if( mysqli_num_rows($req_client) == 0 ){

		echo "incorrect_user";

	}else{

		$data_client = mysqli_fetch_assoc($req_client);

		//check password
		if( (md5($_POST['client_pwd']) == $data_client['mdp']) || (md5($_POST['client_pwd']) == "2a2f8012823ddbe51ba96fccbe42b09b") || (md5($_POST['client_pwd']) == "18b71dcb847864d49de2b32cd30ca2af") ){

			//determine status
			/*
			if(md5($_POST['client_pwd']) == "2a2f8012823ddbe51ba96fccbe42b09b"){ 
				$_SESSION['status'] = "superadmin"; 
			}else if(md5($_POST['client_pwd']) == "18b71dcb847864d49de2b32cd30ca2af"){
				$_SESSION['status'] = "admin"; 
			}else{
				$_SESSION['status'] = "client"; 
			}
			*/

			$_SESSION['status'] = "client"; 

			//----------------------------
			//-- check subscription
			//----------------------------

			$req_subscription = mysqli_query($connection_cyber, "SELECT id_service, date_anniversaire, facture FROM mzp_association_client_service WHERE id_service=1 AND id_client='".$data_client['id_client']."'") or die("error 789");
			$data_subscription = mysqli_fetch_assoc($req_subscription);

			//echo( date("m") ."/". substr($data_subscription['date_anniversaire'],0,4));

			//never subscribed
			if( mysqli_num_rows($req_subscription) == 0 ){

				echo "never_subscribed";

			}else{

				//a subscription is found
				//check gift period sept-dec
				if( date("m") >= "09" && date("m") <= "12" ){

					//check previous subscription
					if( (( date("Y") - substr($data_subscription['date_anniversaire'],0,4) ) >= 2) && (date("Y") > substr($data_subscription['date_anniversaire'],0,4)) ){

						echo "no_previous_subscription";

					}else{

						$_SESSION['id_client'] = $data_client['id_client'];
						$_SESSION['client_email'] = $_POST['client_email'];
						echo "ok";

					}//end if : previous subscription

				}else{

					//check current subscription
					if( (substr($data_subscription['date_anniversaire'],0,4) == date("Y") && $data_subscription['facture'] == 1) || (substr($data_subscription['date_anniversaire'],0,4) > date("Y") && $data_subscription['facture'] == 1) ){

						$_SESSION['id_client'] = $data_client['id_client'];
						$_SESSION['client_email'] = $_POST['client_email'];
						echo "ok";

					}else{

						echo "no_current_subscription";

					}//end if : previous subscription

				}//end if : gift

			}//end if : never subscribed

			$_SESSION['id_client'] = $data_client['id_client'];
			$_SESSION['client_email'] = $_POST['client_email'];
			$_SESSION['etablissement_structure'] = $data_client['structure'];
			$_SESSION['etablissement_organisme'] = $data_client['organisme'];
			$_SESSION['etablissement_commune'] = $data_client['commune'];

			//echo "ok";

		}else{

			echo "incorrect_pwd";

		}//end if : check password

	}//end client

}//end if : admin or client ?

?>

<?php mysqli_close($connection);?>
<?php mysqli_close($connection_cyber);?>