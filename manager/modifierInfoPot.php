<?php
	////////POST
	//Paramètres: idPot : id du pot, titre : nouveau titre du pot, description : nouvelle description du pot, mail : nouveau mail de l'utilisateur, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Effectue les modifications sur le pot passe en parametre
	//JSON : error : true si la modification a ete effectuee, false sinon, code : de retour
	require_once '../persistance/pot.php';
	require_once 'autorisation.php';	

	$titre = htmlspecialchars($_POST['titre']);
	$idPot = htmlspecialchars($_POST['idPot']);
	$description = $_POST['description'];
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if ( autorisation($user, $token) ) {
		if (empty($titre)){
			$res = array('error' => true, 'code' => "Veuillez renseigner un titre");
		}
		else if (empty($description)){
			$res = array('error' => true, 'code' => "Veuillez renseigner une description");
		}
		else if (empty($idPot)){
			$res = array('error' => true, 'code' => "Veuillez renseigner l\'id du pot");
		}
		else {
			$pot = new Pot($titre, $description, $user, $idPot);
			if ($pot->exist()) {
				if ($pot->update()) {
					$res = array('error' => false, 'code' => "La mise à jour du pot a été effectuée");
				}
				else {
					$res = array('error' => true, 'code' => "La mise à jour du pot n\'a pas été effectuée");
				}
			}
			else {
				$res = array('error' => false, 'code' => "Le pot n\'existe pas");
			}
		}
	}
	echo json_encode($res);