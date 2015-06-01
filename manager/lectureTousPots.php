<?php
	////////POST
	//Paramètres: user : pseudo de l'utilisateur connecte, token : token de l'utilisateur
	//Retourne la liste des pots que l'utilisateur a crees
	//JSON : error : true si lecture des pots est possible, false sinon, listePots : informations sur tous les pots
	require_once '../persistance/pot.php';
	require_once 'autorisation.php';


	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Vous n\'avez pas l\'autorisation pour acceder à cette page');
	if(autorisation($user, $token)) {
		$pot = new Pot(null, null, $user);
		$listePots = $pot->readAllByPseudo();
		$listePots = array_values($listePots);
		$res = array('error' => false, 'listePots' => $listePots);
	}
	echo json_encode($res);