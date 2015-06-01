<?php
	////////POST
	//Paramètres: user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Permet de savoir si un utilisateur est connecte et si sa session est valide (cad pseudo et token)
	//JSON : error : true si l'utilisateur est connecte, false sinon, code : de retour
	require_once '../persistance/pot.php';
	require_once 'autorisation.php';

	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	if (autorisation($user, $token)) {
		$res = array('error' => false, 'connecte' => true);
	}
	else {
		$res = array('error' => false, 'connecte' => false);
	}
	echo json_encode($res);