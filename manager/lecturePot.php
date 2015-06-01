<?php
	////////POST
	//Paramètres: idPot : id dun pot, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Retourne toutes les informations concernant le pot passe en parametre
	//JSON : error : true si lecture des informations du pot est possible, false sinon, code : de retour, montantTotalObjet : montant total des objet du pot, 
	/////montantTotalRembourse : montant total rembourse pour ce pot, objet : informations de tous les objets du pot
	require_once '../persistance/pot.php';
	require_once 'autorisation.php';
	require_once '../persistance/objet.php';
	require_once '../persistance/ami.php';
	require_once '../persistance/transaction.php';


	$idPot = $_POST['idPot'];

	if (empty($idPot)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner l\'id du pot');
	}
	else {
		$pot = new Pot(null, null, null, $idPot);
		$objet = new Objet(null, null, $idPot, null);
		$ami = new Ami(null, $idPot);
		$arrayConstructTransaction = $ami->readAll();
		if (!empty($arrayConstructTransaction) ) {
			for ($i = 0; $i < count($arrayConstructTransaction); $i++) {
				$transaction = new Transaction(null, $arrayConstructTransaction[$i]['nom']);
				$montantTotal = $transaction->prixTotalByAmi($idPot);
				$arrayConstructTransaction[$i]['montantTotal'] = $montantTotal;
			}
		}
		$pot = $pot->read();
		$titre = $pot->getTitre();
		$description = $pot->getDescription();
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
		$description = preg_replace('@<script[^>]*?>.*?</script>@si', '', $description); //Evite les injections de Javascript
		$res = array('montantTotalObjet' => $montantTotalObjet, 'montantTotalRembourse' => $montantTotalObjet - $montantTotalTransaction, 'error' => false, 'titre' => $titre, 'description' => $description, 'objet' => array_values($arrayConstructObjet), 'listAmis' => array_values($arrayConstructTransaction));
	}
	echo json_encode($res);