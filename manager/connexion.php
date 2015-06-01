<?php
	////////POST
	//Paramètres: user: le pseudo de l'utilisateur, mdp : mdp de l'utilisateur
	//Connecte un utilisateur et attribue un token
	//JSON : error : true si la connexion est autorisee, false sinon, code : de retour, token : si la connexion est autorise retourne le token attribue a l'utilisateur
	require_once '../persistance/utilisateur.php';
	require_once 'autorisation.php';	

	$pseudo = htmlspecialchars($_POST['pseudo']);
	$mdp = htmlspecialchars($_POST['mdp']);

	if (empty($pseudo)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un pseudo');
	}
	elseif(empty($mdp)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un mot de passe');
	}
	else {
		$user = new Utilisateur($pseudo, sha1($mdp));
		if ($user->autoriseConnexion()) {
			$token = optenirToken($pseudo);
			$res = array('error' => false, 'code' => 'Connexion autorisée', 'token' => $token);
		}
		else {
			$res = array('error' => true, 'code' => 'Pseudo ou mot de passe incorrect');
		}
	}
	echo json_encode($res);