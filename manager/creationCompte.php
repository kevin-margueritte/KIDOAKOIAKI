<?php
	////////POST
	//Paramètres: pseudo: le pseudo de l'utilisateur, mdp : mdp de l'utilisateur, email : email de l'utilisateur
	//Creer un compte utilisateur avec le pseudo et le mot de passe passe en parametre
	//JSON : error : true si la creation du compte a ete effectuee, false sinon, code : de retour
	require_once '../persistance/utilisateur.php';	

	$pseudo = htmlspecialchars($_POST['pseudo']);
	$mdp = htmlspecialchars($_POST['mdp']);
	$email = htmlspecialchars($_POST['email']);

	if (empty($pseudo)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un pseudo');
	}
	elseif(empty($mdp)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un mot de passe');
	}
	elseif(empty($email)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un mail');
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un mail valide');
	}
	else {
		$user = new Utilisateur($pseudo, sha1($mdp), $email);
		if ($user->exist()) {
			$res = array('error' => true, 'code' => 'Votre pseudo existe déjà');
		}
		else {
			$user->create();
			$res = array('error' => false, 'code' => 'Votre compte à bien été crée');
		}
	}
	echo json_encode($res);