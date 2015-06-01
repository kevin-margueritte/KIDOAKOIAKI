<?php
	////////POST
	//idPot : id pot pour lequel on veut ajouter l'ami, user: le pseudo de l'utilisateur connecte, token : de l'utilisateur connecte
	//Indique si l'utilisateur a l'autorisation de modification du pot
	//JSON : error : true si l'utilisateur a l'autorisation, false sinon; code : de retour

	require_once '../persistance/pot.php';
	require_once 'autorisation.php';


	$idPot = $_POST['idPot'];
	$user = $_POST['user'];
	$token = $_POST['token'];

	$pot = new Pot(null, null, null, $idPot);
	if (autorisation($user, $token) and $pot->autoriseModification($user)) {
		$res = array('error' => false, 'code' => 'Vous êtes autorisé à faire des modification sur le pot');
	}
	else {
		$res = array('error' => true, 'code' => 'Vous n\'êtes pas autorisé à faire des modification sur le pot');
	}
	echo json_encode($res);