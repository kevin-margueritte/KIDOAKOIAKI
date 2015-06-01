<?php
	////////POST
	//Paramètres: newMdp : nouveau mot de passe de l'utilisateur, ancienMdp : ancien mot de passe de l'utilisateur, mail : nouveau mail de l'utilisateur, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Effectue les modifications sur l'utilisateur passe en parametre
	//JSON : error : true si la modification a ete effectuee, false sinon, code : de retour
	require_once '../persistance/utilisateur.php';
	require_once 'autorisation.php';	

	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$newMdp = htmlspecialchars($_POST['newMdp']);
	$ancienMdp = htmlspecialchars($_POST['ancienMdp']);
	$email = htmlspecialchars($_POST['mail']);

	if (empty($newMdp)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner votre nouveau mot de passe');
	}
	elseif(empty($ancienMdp)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner votre mot de passe actuel');
	}
	elseif(empty($email)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner votre email');
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un mail valide');
	}
	else if (autorisation($user, $token)) {
		$user = new Utilisateur($user, sha1($ancienMdp), $email, $token);
		if ($user->autoriseConnexion()) {
			$user->setMdp(sha1($newMdp));
			$user->update();
			$res = array('error' => false, 'code' => 'La modification a bien été effectuée');
		}
		else {
			$res = array('error' => true, 'code' => 'Votre mot de passe actuel est incorrect');
		}
	}
	else {
		$res = array('error' => true, 'code' => 'Vous n\'êtes pas autorisé à aller sur cette page');
	}
	echo json_encode($res);