<?php
	require_once '../persistance/token.php';
	require_once '../persistance/utilisateur.php';

	function optenirToken($pseudo){
		$token_id = null;
		$user = new Utilisateur($pseudo);
		$user = $user->read();
		if ($user->autoriseConnexion()) { //L'utilisateur existe si true
			$token_id = $user->getTokenId();
			$token = new Token($token_id, null, null);
			$token->supprimeTokenCourant($pseudo);
			$token->delete();
			$date_creation = date("Y-m-j H:i:s");
			$token_id = sha1(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'). $date_creation . rand(0,1000000));
			$date_expiration = date('Y-m-j H:i:s', strtotime("+1 week"));//Un token dure une semaine, ensuite l'utilisateur est deconnecte
			$token = new Token($token_id, $date_creation, $date_expiration); ///Créer token
			$token->create(); //Enregistre BD
			$user->setTokenId($token_id);
			$user->updateToken(); //Ajoute le token a l'utilisateur dans la BD
		}
		return $token->getTokenId();//$pseudo;
	}

	function autorisation($pseudo, $token) {
		$autorisation = false;
		$user = new Utilisateur($pseudo);
		$user = $user->read();
		$tokenIdUser = $user->getTokenId(); //Recupere token courant de l'utilisateur
		if (!empty($tokenIdUser)) { //Il y a pas de token
			$token = new Token($token);
			$token = $token->read();
			if ($token->getTokenId() == $tokenIdUser and $token->getDateExpiration() < date("Y-m-j H:i:s")) {
				$autorisation = true;
			}
		}
		return $autorisation;
	}