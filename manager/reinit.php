<?php
	////////POST
	//Paramètres: token : token de reinitialisation du mot de passe utilisateur
	//Reinitialise le mot de passe de l'utilisateur grace au token passe en parametre, et envoi un nouveau mail a l'utilisateur
	require_once '../persistance/utilisateur.php';
	require_once 'autorisation.php';

	$token = htmlspecialchars($_GET['token']);

	$user = Utilisateur::createUtilisateurByTokenReinit($token);
	$to = $user->getEmail();
	$date_creation = date("Y-m-j H:i:s");
	$mdp = $date_creation . rand(0,1000000);
	$user->setMdp(sha1($mdp));
	$user->update();
	ini_set("SMTP", "smtp.voyageospa.com"); 
	ini_set("auth_username", "kidoakoiaki@voyageospa.fr"); 
	ini_set("auth_password", "speedfight27"); 
	$subject = 'Information de récupération du mot de passe KI DOA KOI A KI';
	$message = 'Bonjour '. $pseudo."<br><br><br>".'Cet email a été envoyé à partir du site http://www.kidoakoiaki.ovh/'. "<br><br>".
	'Vous recevez ce message parce que vous avez demandé une réinitialisation du mot de passe de votre compte utilisateur.'. "<br><br>" .
	'Voici votre nouveau mot de passe : ' . $mdp;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= 'From: kidoakoiaki@noreply.com.fr'."\r\n";
	mail($to, $subject, $message, $headers);