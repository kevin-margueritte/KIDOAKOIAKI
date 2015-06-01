<?php
	////////POST
	//Paramètres: idPot : id du pot, nomAmi : nom de l'ami qu'on souhaite supprimer, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Supprime l'ami passe en parametre
	//JSON : error : true si la suppression a ete effectuee, false sinon, code : de retour, nom : de l'ami supprime,
	// montantTotalRemboursePot : montant total rembourse des objets du pot, montantTotalObjetPot : montant total de tous les objets du pot
	require_once '../persistance/ami.php';
	require_once 'autorisation.php';
	require_once '../persistance/pot.php';	

	$idPot = htmlspecialchars($_POST['idPot']);
	$nomAmi = htmlspecialchars($_POST['nomAmi']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if (autorisation($user, $token)) {
		if (empty($nomAmi)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner le nom d\'un ami');
		}
		else {
			$ami = new Ami($nomAmi, $idPot);
			if ($ami->exist()) {
				$ami->delete();
				$pot = new Pot(null, null, null, $idPot);
				$montantTotalTransaction = $pot->montantTotalTransaction();
				$montantTotalObjet = $pot->montantTotalObjet();
				$res = array('montantTotalRemboursePot' => $montantTotalObjet - $montantTotalTransaction , 'montantTotalObjetPot' => $montantTotalObjet, 'error' => false, 'nom' => $nomAmi, 'code' => $nomAmi . ' a été supprimé');
			}
			else {
				$res = array('error' => true, 'nom' => $nomAmi, 'code' => $nomAmi . ' ne peut pas être supprimé');
			}
		}
	}
	echo json_encode($res);