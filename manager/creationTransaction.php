<?php
	////////POST
	//Paramètres: idObjet: id de l'objet, idAmi : nom de l'ami, montant : montant de la transaction, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Crer une transaction entre un ami et un objet. Si la transaction existe on effectue une MAJ
	//JSON : error : true si la MAJ ou la creation a ete effectuee, false sinon, code : de retour, montantTotalDuPot : montant total rembourse pour le pot passe en parametre, 
	//////montantTotalObjetPot : montant total de tous les objets du pot, montantTotalObjetRembourse : montant total du pour l'objet passe en parametre
	require_once '../persistance/transaction.php';
	require_once '../persistance/objet.php';
	require_once 'autorisation.php';
	require_once '../persistance/pot.php';	

	$idObjet = htmlspecialchars($_POST['idObjet']);
	$idAmi = htmlspecialchars($_POST['idAmi']);
	$montant = htmlspecialchars($_POST['montant']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if ( autorisation($user, $token) ) {
		if (empty($idObjet)) {
			$res = array('error' => true, 'code' => 'Erreur, objet inexistant');
		}
		elseif (empty($idAmi)) {
			$res = array('error' => true, 'code' => 'Erreur, amis inexistant');
		}
		else if(is_float($montant)){
			$res = array('error' => true, 'code' => 'Veillez renseigner un montant valide');
		}
		else if($montant<0){
			$res = array('error' => true, 'code' => 'Veillez renseigner un montant positif');
		}
		else {
			$transaction = new Transaction($idObjet, $idAmi);
			$objet = new Objet(null,null,null,$idObjet);
			$objet = $objet->read();

			if ($transaction->autoriseAjouterTransaction($user)) {
				$montantTotal = $objet->montantTotalTransaction();
				$prixObjet = $objet->getPrix();
				$pot = new Pot(null, null, null, $objet->getIdPot());
				if ($transaction->exist()) {
					$transaction = $transaction->read();
					$ancienMontant = $transaction->getMontant();
					$montantTotal = $montantTotal - $ancienMontant;
					if ( ($montant <= $ancienMontant) or ($montantTotal + $montant <= $prixObjet) ) {
						$transaction->setMontant($montant);
						$transaction->update();
						$montantTotalTransaction = $pot->montantTotalTransaction();
						$montantTotalObjet = $pot->montantTotalObjet();
						$res = array('montantTotalDuPot' => $montantTotalObjet - $montantTotalTransaction, 'montantTotalObjetPot' => $montantTotalObjet, 'error' => false, 'code' => $objet->getNom() . ' a bien été affecté à vos amis'.$ancienMontant.' '.$montant, 'montantTotalObjetRembourse' => $objet->getPrix() - $objet->montantTotalTransaction());
					}
					else {
						$res = array('error' => true, 'code' => 'Le montant total affecté à vos amis est plus grand que le prix de l\'objet '.$objet->getNom());
					}
				}
				else if ( ($montant <= $prixObjet) and ($montantTotal + $montant <= $prixObjet) ) {
					if ( $transaction->create() ) {
						$montantTotalTransaction = $pot->montantTotalTransaction();
						$montantTotalObjet = $pot->montantTotalObjet();
						$res = array('montantTotalDuPot' => $montantTotalObjet - $montantTotalTransaction, 'montantTotalObjetPot' => $montantTotalObjet, 'error' => false, 'code' => $objet->getNom() . ' a bien été affecté à vos amis', 'montantTotalObjetRembourse' => $objet->getPrix() - $objet->montantTotalTransaction());
					}
					else {
						$res = array('error' => true, 'code' => 'La transaction existe déjà');
					}
				}
				else {
					$res = array('error' => true, 'code' => $objet->getNom() . ' ne peut pas être affecté à ' . $idAmi);
				}
			}
			else {
				$res = array('error' => true, 'code' => 'Vous n\'avez pas l\'autorisation pour affecter des objets à vos amis');
			}
		}
	}
	echo json_encode($res);