<?php
	////////POST
	//Paramètres: idObjet: id de l'objet pour lequel on souhaite connaitre toutes les transactions, idPot : id du pot pour lequel l'objet appartient, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Retournes toutes les informations concernants les transaction effectuees aux amis sur l'objet passe en parametre
	//JSON : error : true si la lecture des informations est possible, false sinon, code : de retour
	require_once '../persistance/ami.php';
	require_once '../persistance/transaction.php';
	require_once 'autorisation.php';	

	$idPot = htmlspecialchars($_POST['idPot']);
	$idObjet = htmlspecialchars($_POST['idObjet']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if ( autorisation($user, $token) ) {
		$ami = new Ami('', $idPot);

		if ($ami->autoriseAjouterAmi($user)) {
			$arrayConstruct = $ami->readAll();
			for ($i = 0; $i < count($arrayConstruct); $i++) {
				$affected = false;
				$transaction = new Transaction($idObjet, $arrayConstruct[$i]['nom']);
				$transaction = $transaction->read();
				$montant = $transaction->getMontant();
				if ($montant != 0) {
					$affected = true; //S'il existe un montant, la transaction existe
				}
				else {
					$montant = 0;
				}
   				$arrayConstruct[$i]['montant'] = $montant;
   				$arrayConstruct[$i]['affecte'] = $affected;
			}
			$res = array('error' => false, 'listAmis' => array_values($arrayConstruct));
		}
		else {
			$res = array('error' => true, 'code' => 'Vous n\'avez pas l\'autorisation pour consulter les amis');
		}
	}
	echo json_encode($res);