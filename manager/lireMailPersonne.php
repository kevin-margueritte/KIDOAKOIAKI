<?php
	////////POST
	//Paramètres: user : pseudo de l'utilisateur connecte, token : token de l'utilisateur
	//Retourne l'email de l'utilisateur
	//JSON : error : true si la lecture du mail est possible, false sinon, code : de retour en cas d'erreur, mail : mail de l'utilisateur en cas de succes
	require_once '../persistance/utilisateur.php';
	require_once 'autorisation.php';


	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	if (autorisation($user, $token)) {
		$user = new Utilisateur($user);
		$user = $user->read();
		$res = array('error' => false, 'mail' => $user->getEmail() );
	}
	else {
		$res = array('error' => true, 'code' => 'Vous n\'êtes pas autorisé à aller sur cette page');
	}
	echo json_encode($res);