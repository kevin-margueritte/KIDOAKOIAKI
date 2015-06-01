<?php
	////////POST
	//Paramètres: idPot : id du pot pour lequelon veut lire les amis, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Retourne une liste de tous les amis du pot passe en parametre contenant toutes les informations les concernants
	//JSON : error : true si la lecture des amis est possible, false sinon, code : de retour
	require_once '../persistance/ami.php';
	require_once '../persistance/transaction.php';
	require_once 'autorisation.php';


	$idPot = htmlspecialchars($_POST['idPot']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if (autorisation($user, $token)) {
		if (empty($idPot)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner l\'id du pot');
		}
		else {
			$ami = new Ami(null, $idPot);
			$arrayConstruct = $ami->readAll();
			if (!empty($arrayConstruct) ) {
				for ($i = 0; $i < count($arrayConstruct); $i++) {
					$transaction = new Transaction(null, $arrayConstruct[$i]['nom']);
					$montantTotal = $transaction->prixTotalByAmi($idPot);
					$arrayConstruct[$i]['montantTotal'] = $montantTotal;
				}
			}
			$res = array('error' => false, 'listAmis' => array_values($arrayConstruct));
		}
	}
	echo json_encode($res);