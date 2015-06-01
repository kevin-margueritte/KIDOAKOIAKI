<?php
	////////POST
	//Paramètres: idObjet: id de l'objet, montantObjet : nouveau montant de l'objet, idPot : id du pot où appartient l'objet, nomObjet : nouveau nom de l'objet, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Modifie l'objet passe en parametre
	//JSON : error : true si lecture des informations du pot est possible, false sinon, code : de retour, montantTotalObjet : montant total des objet du pot, 
	/////montantTotalTransactionPot : montant total de toutes les transactions du pot, montantTotalObjetPot : montant total de tous les objets du pot, montantTotal : montant dû pour cet objet
	require_once '../persistance/objet.php';
	require_once '../persistance/pot.php';
	require_once 'autorisation.php';	

	$idObjet = htmlspecialchars($_POST['idObjet']);
	$idPot = htmlspecialchars($_POST['idPot']);
	$nomObjet = htmlspecialchars($_POST['nomObjet']);
	$montantObjet = htmlspecialchars($_POST['montantObjet']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if ( autorisation($user, $token) ) {
		if (empty($idObjet)){
			$res = array('error' => true, 'code' => "Veuillez renseigner l\'id de l\'objet");
		}
		else if (empty($idPot)){
			$res = array('error' => true, 'code' => "Veuillez renseigner l\'id du pot");
		}
		else if (empty($nomObjet)){
			$res = array('error' => true, 'code' => "Veuillez renseigner le nom de l\'objet");
		}
		else if (empty($montantObjet)){
			$res = array('error' => true, 'code' => "Veuillez renseigner le montant de l\'objet");
		}
		else if ($montantObjet < 0) {
			$res = array('error' => true, 'code' => "Veuillez renseigner un montant positif");
		}
		else {
			//$objet = new Objet($nomObjet, $montantObjet, $idPot, $idObjet);
			$objet = new Objet(null, null, $idPot, $idObjet);
			if ($objet->exist()) {
				$objet = $objet->read();
				$montantTotal = $objet->montantTotalTransaction();
				$pot = new Pot(null, null, null, $idPot);
				if ($objet->getPrix() != $montantObjet) { //On fait une modification de prix
					$objet->deleteAllTransaction();
					$objet->setPrix($montantObjet);
					if ($objet->update()){
						$res = array('montantTotalTransactionPot' => $pot->montantTotalTransaction(), 'montantTotalObjetPot' => $pot->montantTotalObjet(), 'error' => false, 'code' => "La mise à jour de l'objet a été effectuée", 'montantTotal' => $objet->montantTotalTransaction());
					}
					else {
						$res = array('error' => true, 'code' => "Erreur, la mise à jour de l'objet n\'a pas été effectuée");
					}
				}
				else if ($objet->getNom() != $nomObjet) { //Modification du nom
					$objet->setNom($nomObjet);
					if ($objet->update()){
						$res = array('montantTotalTransactionPot' => $pot->montantTotalTransaction(), 'montantTotalObjetPot' => $pot->montantTotalObjet(), 'error' => false, 'code' => "La mise à jour de l'objet a été effectuée", 'montantTotal' => $objet->montantTotalTransaction());
					}
					else {
						$res = array('error' => true, 'code' => "Erreur, la mise à jour de l'objet n\'a pas été effectuée");
					}
				}
				else {
					$res = array('montantTotalTransactionPot' => $pot->montantTotalTransaction(), 'montantTotalObjetPot' => $pot->montantTotalObjet(), 'error' => false, 'code' => "Aucune mise à jour effectuée", 'montantTotal' => $objet->montantTotalTransaction());
				}				
			}
			else {
				$res = array('error' => true, 'code' => 'Erreur, objet inexistant');
			}
		}
	}
	echo json_encode($res);