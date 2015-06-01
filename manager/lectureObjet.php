<?php
	////////POST
	//Paramètres: idPot : id dun pot
	//Retourne toutes les informations concernant les objets du pot passe en parametre
	//JSON : error : true si lecture des informations du pot est possible, false sinon, code : de retour, montantTotalObjet : montant total des objet du pot, 
	/////montantTotalRembourse : montant total rembourse pour ce pot, objet : informations de tous les objets du pot, listeAmis : informations sur tous les amis ansi que leurs transactions
	/////titre : titre du pot, description : description du pot
	require_once '../persistance/pot.php';
	require_once '../persistance/objet.php';
	require_once 'autorisation.php';


	$idPot = htmlspecialchars($_POST['idPot']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	if (empty($idPot)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner l\'id du pot');
	}
	else {
		$pot = new Pot(null, null, null, $idPot);
		$objet = new Objet(null, null, $idPot, null);
		$pot = $pot->read();

		$arrayConstructObjet = $objet->readAllByIdPot();
		if (!empty($arrayConstructObjet) ) {
			for ($i = 0; $i < count($arrayConstructObjet); $i++) {
				$objet = new Objet(null, null, null, $arrayConstructObjet[$i]['id']);
				$montantTotalTransaction = $objet->montantTotalTransaction();
				$montantObjet = $arrayConstructObjet[$i]['prix'];
				$arrayConstructObjet[$i]['montantTotalRembourse'] = $montantObjet - $montantTotalTransaction;
			}
		}

		if (!empty($listObjet)) {
			$listObjet = array_values($listObjet);
		}
		$montantTotalObjet = $pot->montantTotalObjet();
		$montantTotalTransaction = $pot->montantTotalTransaction();
		$res = array('montantTotalObjet' => $montantTotalObjet, 'montantTotalRembourse' => $montantTotalObjet - $montantTotalTransaction, 'error' => false,'objet' => array_values($arrayConstructObjet));
	}
	echo json_encode($res);