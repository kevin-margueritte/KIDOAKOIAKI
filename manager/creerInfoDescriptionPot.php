<?php
	////////POST
	//Paramètres: titre : titre du pot, description : description du pot, user: le pseudo de l'utilisateur connecte, token : de l'utilisateur connecte
	//Creer un pot avec la description et le titre passe en parametre
	//JSON : error : le pot a bien ete cree, false sinon, code : de retour
	require_once '../persistance/pot.php';
	require_once 'autorisation.php';

	$titre = htmlspecialchars($_POST['titre']);
	$description = $_POST['description'];
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => $user . $token);//'Erreur, merci de vous reconnecter');
	if (autorisation($user, $token)) {
		if (empty($titre)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner un titre');
		}
		elseif (empty($description)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner une description');
		}
		else {
			$pot = new Pot($titre, $description, $user);
			if ($pot->create()) {
				$res = array('error' => false, 'id' => $pot->getId(), 'code' => 'Le pot est crée');
			}
		}
	}
	echo json_encode($res);
