<?php

//session
session_start();

//globals
include('../../include/global.php');

//connection
include('../../include/connection.php');


//get the periods
$req_periode = mysqli_query($connection, "SELECT id_periode, nom, date_html FROM ".PREFIX."periode") or die("error 100210");
while( $data_periode = mysqli_fetch_assoc($req_periode) ){
	if( $data_periode['id_periode']==1 ){ $P1 = $data_periode['date_html']; }
	if( $data_periode['id_periode']==2 ){ $P2 = $data_periode['date_html']; }
	if( $data_periode['id_periode']==3 ){ $P3 = $data_periode['date_html']; }
	if( $data_periode['id_periode']==4 ){ $P4 = $data_periode['date_html']; }
	if( $data_periode['id_periode']==5 ){ $P5 = $data_periode['date_html']; }
}

$_POST['search'] = addslashes($_POST['search']);

//get the documents
$req_document = mysqli_query($connection, "SELECT id_malle, jeton_malle, titre, nombre_exemplaire, niveau, disponibilite1, disponibilite2, disponibilite3, disponibilite4, disponibilite5 FROM ".PREFIX."malle WHERE numero_exemplaire LIKE '%".utf8_decode($_POST['search'])."%' OR titre LIKE '%".utf8_decode($_POST['search'])."%' OR cote LIKE '%".utf8_decode($_POST['search'])."%' OR niveau LIKE '%".utf8_decode($_POST['search'])."%' OR resume LIKE '%".utf8_decode($_POST['search'])."%' OR mot_cle LIKE '%".utf8_decode($_POST['search'])."%' OR langue LIKE '%".utf8_decode($_POST['search'])."%' ORDER BY niveau, titre") or die("error 014".mysqli_error($connection));

$response1 = '

<table>
	<tr>
		<td width="10%">Niveau</td>
		<td width="50%">Titre</td>
		<td width="10%">Nb de livres</td>
		<td width="10%">Disponibilité</td>
		<td width="20%">&nbsp;</td>
	</tr>

';

$result = '';
$menu = '';

while( $data_document = mysqli_fetch_assoc($req_document) ){

	
	$result .= '<tr class="document_zone" data_id_malle="'.$data_document['id_malle'].'" data_jeton_malle="'.$data_document['jeton_malle'].'"><td>'.utf8_encode($data_document['niveau']).'</td><td class="title">'.utf8_encode($data_document['titre']).'</td><td>'.$data_document['nombre_exemplaire'].'</td><td><div class="available_zone"><span class="available_flag p1 available_flag_'.$data_document['disponibilite1'].'" title="Période 1 : '.$P1.'"></span><span class="available_flag p2 available_flag_'.$data_document['disponibilite2'].'" title="Période 2 : '.$P2.'"></span><span class="available_flag p3 available_flag_'.$data_document['disponibilite3'].'" title="Période 3 : '.$P3.'"></span><span class="available_flag p4 available_flag_'.$data_document['disponibilite4'].'" title="Période 4 : '.$P4.'"></span><span class="available_flag p5 available_flag_'.$data_document['disponibilite5'].'" title="Période 5 : '.$P5.'"></span></div></td><td><div class="menu">'.$menu.'</div></td></tr>';

}

$response2 = '</tr></table>';

if( $result == "" ){
	echo '<div style="height:200px; padding:20px;">Aucune malle trouvée</div>';
}else{
	echo $response1.$result.$response2;
}

?>

<?php mysqli_close($connection);?>