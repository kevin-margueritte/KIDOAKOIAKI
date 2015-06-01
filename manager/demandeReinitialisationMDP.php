<?php
	////////POST
	//Paramètre: pseudo: le pseudo de l'utilisateur
	//Envoi un mail a l'utilisateur passe en parametre contenant un URL pour reinitialiser sont mot de passe
	////Meme si le pseudo est invalide on previent l'utilisateur qu'un mail a ete envoye dans sa boite mail
	//JSON : error : false si l'utilisateur a entre un pseudo, false sinon. code : de retour
	require_once '../persistance/utilisateur.php';
	require_once 'autorisation.php';	

	$pseudo = htmlspecialchars($_POST['pseudo']);

	if (empty($pseudo)) {
		$res = array('error' => true, 'code' => 'Veuillez renseigner un pseudo');
	}
	else {
		$user = new Utilisateur($pseudo);
		if ($user->exist()) {
			$date_creation = date("Y-m-j H:i:s");
			$token = sha1(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'). $date_creation . rand(0,1000000));
			$user = $user->read();
			$to = $user->getEmail();
			$user->ajouteTokenReinitialisation($token);
			ini_set("SMTP", "smtp.voyageospa.com"); 
			ini_set("auth_username", "kidoakoiaki@voyageospa.fr"); 
			ini_set("auth_password", "speedfight27"); 
			$subject = 'Information de récupération du mot de passe KI DOA KOI A KI';
			$message = 'Bonjour '. $pseudo."<br><br><br>".'Cet email a été envoyé à partir du site http://www.kidoakoiaki.ovh/'. "<br><br>".
			'Vous recevez ce message parce que vous avez demandé une réinitialisation du mot de passe de votre compte utilisateur.'. "<br><br>" .
			"Cliquez simplement sur le lien pour réinitialiser votre mot de passe, un nouveau mot de passe vous sera ensuite envoyé par mail : http://www.kidoakoiaki.ovh/connexion/reinit.php/?token=" .$token;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
		    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$headers .= 'From: kidoakoiaki@noreply.com.fr'."\r\n";
			mail($to, $subject, $message, $headers);
		}
		$res = array('error' => false, 'code' => 'Un email vient d\'être envoyé pour reinitialiser votre mot de passe');
	}
	echo json_encode($res);