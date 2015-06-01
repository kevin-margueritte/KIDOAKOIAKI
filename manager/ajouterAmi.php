<?php
	////////POST
	//Paramètres: nomAmi : nom l'ami a ajoute, idPot : id pot pour lequel on veut ajouter l'ami, user: le pseudo de l'utilisateur connecte, token : de l'utilisateur connecte
	//Creer un ami sur le pot passé en parametre
	//JSON : error : true si l'ami a ete ajoute, false sinon, code : de retour
	require_once '../persistance/ami.php';
	require_once 'autorisation.php';	

	$nomAmi = $_POST['nomAmi'];
	$idPot = $_POST['idPot'];
	$user = $_POST['user'];
	$token = $_POST['token'];

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if (autorisation($user, $token)) {
		if (empty($nomAmi)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner le nom d\'un ami');
		}
		else {
			$ami = new Ami($nomAmi, $idPot);
			if ($ami->autoriseAjouterAmi($user)) {
				if ($ami->create()) {
					$res = array('error' => false, 'code' => $nomAmi . ' a été ajouté');
				}
				else {
					$res = array('error' => true, 'code' => $nomAmi . ' a déjà été ajouté');
				}
			}
			else {
				$res = array('error' => true, 'code' => $idPot .'Vous n\'avez pas l\'autorisation pour ajouter un ami');
			}
		}
	}
	echo json_encode($res);