<?php
	////////POST
	//Paramètres: nomObjet: nom de l'objet, prixObjet : prix de l'objet a ajouter, idPot : id du pot pour lequel l'objet sera ajoute, user : pseudo de l'utilisateur connecte, token : token de l'utilisateur 
	//Creer un objet associe au pot passe en parametre
	//JSON : error : true si la creation de l'objet a ete effectuee, false sinon, code : de retour, montantTotalRembourse : affiche le montant de l'objet a rembourse par defaut le prix de l'objet
	require_once '../persistance/objet.php';
	require_once 'autorisation.php';	

	$nomObjet = htmlspecialchars($_POST['nomObjet']);
	$prixObjet = htmlspecialchars($_POST['prixObjet']);
	$idPot = htmlspecialchars($_POST['idPot']);
	$user = htmlspecialchars($_POST['user']);
	$token = htmlspecialchars($_POST['token']);

	$res = array('error' => true, 'code' => 'Erreur, merci de vous reconnecter');
	if ( autorisation($user, $token) ) {
		if (empty($nomObjet)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner le nom d\'un objet');
		}
		elseif (empty($prixObjet)) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner le prix de l\'objet');
		}
		else if ($prixObjet < 0) {
			$res = array('error' => true, 'code' => 'Veuillez renseigner un prix positif');
		}
		else {
			$objet = new Objet($nomObjet, $prixObjet, $idPot);

			if ($objet->autoriseAjouterObjet($user)) {
				if ( $objet->create() ) {
					$res = array('error' => false, 'code' => $nomObjet . ' a été ajouté', 'idObjet' => $objet->getId(), 'montantTotalRembourse' => $prixObjet);
				}
				else {
					$res = array('error' => true, 'code' => $nomObjet . ' a déjà été ajouté');
				}
			}
			else {
				$res = array('error' => true, 'code' => 'Vous n\'avez pas l\'autorisation pour ajouter un objet');
			}
		}
	}
	echo json_encode($res);