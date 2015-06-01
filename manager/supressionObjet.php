<?php
	////////POST
	//Paramètres: idPot : id du pot, idObjet : id de l'objet qu'on souhaite supprimer, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Supprime l'objet passe en parametre
	//JSON : error : true si la suppression a ete effectuee, false sinon, code : de retour, montantTotalRemboursePot : montant total rembourse des objets du pot, montantTotalObjetPot : montant total de tous les objets du pot
	require_once '../persistance/objet.php';
	require_once 'autorisation.php';
	require_once '../persistance/pot.php';	

	$idPot = htmlspecialchars($_POST['idPot']);
	$idObjet = htmlspecialchars($_POST['idObjet']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if (autorisation($user, $token)) {
		if (empty($idPot)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner l\'id du pot');
		}
		else if (empty($idPot)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner l\'id de l\'objet');
		}
		else {
			$objet = new Objet(null,null, $idPot, $idObjet);
			if ($objet->autoriseAjouterObjet($user) ) {
				if ($objet->exist()) {
					$objet->delete();
					$pot = new Pot(null, null, null, $idPot);
					$montantTotalTransaction = $pot->montantTotalTransaction();
					$montantTotalObjet = $pot->montantTotalObjet();
					$res = array('montantTotalRemboursePot' => $montantTotalObjet - $montantTotalTransaction, 'montantTotalObjetPot' => $montantTotalObjet, 'error' => false, 'code' => 'L\'objet a été supprimé');
				}
				else {
					$res = array('error' => true, 'code' => 'Supression impossible');
				}
			}
			else {
				$res = array('error' => true, 'code' => 'Vous n\'aver pas l\'autorisation pour supprimer cet objet');
			}
		}
	}
	echo json_encode($res);