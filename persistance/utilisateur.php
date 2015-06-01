<?php
	require_once 'token.php';
	class Utilisateur {
		private $pseudo;
		private $mdp;
		private $email;
		private $db;
		private $token_id;

		public function __construct ($pseudo, $mdp=null, $email=null, $token=null) {
			error_reporting(0);
			try {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX'); //Ouvre une connexion sur la BD
			} catch (PDOException $e) {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX');
			}
			$this->pseudo = $pseudo;
			$this->mdp = $mdp;
			$this->email = $email;
			$this->token_id = $token;
		}

		public function setTokenId($token_id) {
			$this->token_id = $token_id;
		}

		public function getTokenId() {
			return $this->token_id;
		}

		public function setMdp($mdp) {
			return $this->mdp = $mdp;
		}

		public function getMdp() {
			return $this->mdp;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getToken() {
			$rqt = $this->db->prepare('SELECT t.token_id, t.date_creation, t.date_expiration FROM utilisateur u, token t WHERE pseudo = :pseudo AND t.token_id = :token');
			$rqt->execute(array(
			    'pseudo' => $this->pseudo,
			    'token' => $this->token_id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return new Token($row[0]['token_id'], $row[0]['date_creation'], $row[0]['date_expiration']);
		}

		//True si la connexion est possible (bon compte utilisateur et mot de passe)
		public function autoriseConnexion() {
			$rqt = $this->db->prepare('SELECT * FROM utilisateur WHERE pseudo = :pseudo AND mdp = :mdp');
			$rqt->execute(array(
			    'pseudo' => $this->pseudo,
			    'mdp' => $this->mdp
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return count($row) != 0;
		}

		//Retourne true si l'utilisateur existe, false sinon
		public function exist(){
			$rqt = $this->db->prepare('SELECT * FROM utilisateur WHERE pseudo = :pseudo');
			$rqt->execute(array(
			    'pseudo' => $this->pseudo
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return count($row) != 0 ;
		}

		public function create(){
			$rqt = $this->db->prepare('INSERT INTO utilisateur (pseudo, mdp, email, token_id) ' .
			    'VALUES (:pseudo, :mdp, :email, :token)');
			$code = $rqt->execute(array(
			    'pseudo' => $this->pseudo,
			    'mdp' => $this->mdp,
			    'email' => $this->email,
			    'token' => $this->token_id
			));
			return $code;
		}

		public function read() {
			$rqt = $this->db->prepare('SELECT pseudo, mdp, email, token_id FROM utilisateur WHERE pseudo = :pseudo');
			$rqt->execute(array(
			    'pseudo' => $this->pseudo
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			if (count($row) == 0) {
				$res = new Utilisateur($this->pseudo);
			}
			else {
				$res = new Utilisateur($row[0]['pseudo'], $row[0]['mdp'], $row[0]['email'], $row[0]['token_id']);
			}
			return $res;
		}

		public function update() {
			$rqt = $this->db->prepare('UPDATE utilisateur SET mdp = :mdp, email = :email, token_id = :token WHERE pseudo = :pseudo');
			$code = $rqt->execute(array(
			    'pseudo' => $this->pseudo,
			    'mdp' => $this->mdp,
			    'email' => $this->email,
			    'token' => $this->token_id
			));
			return $code;
		}

		public function delete() {
			$rqt = $this->db->prepare('DELETE FROM utilisateur WHERE pseudo = :pseudo');
			$code = $rqt->execute(array(
			    'pseudo' => $this->pseudo
			));
			return $code;
		}

		public function updateToken() {
			$rqt = $this->db->prepare('UPDATE utilisateur SET token_id = :token WHERE pseudo = :pseudo');
			$code = $rqt->execute(array(
			    'token' => $this->token_id,
			    'pseudo' => $this->pseudo
			));
			return $code;
		}

		public function ajouteTokenReinitialisation($tokenReinit) {
			$rqt = $this->db->prepare('UPDATE utilisateur SET token_reinit = :tokenReinit WHERE pseudo = :pseudo');
			$code = $rqt->execute(array(
			    'tokenReinit' => $tokenReinit,
			    'pseudo' => $this->pseudo
			));
			return $code;
		}

		public function getUtilisateurByTokenReinit($tokenReinit) {
			$rqt = $this->db->prepare('SELECT pseudo, mdp, email, token_id FROM utilisateur WHERE token_reinit = :tokenReinit');
			$rqt->execute(array(
			    'tokenReinit' => $tokenReinit
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			if (count($row) == 0) {
				$res = new Utilisateur($this->pseudo);
			}
			else {
				$res = new Utilisateur($row[0]['pseudo'], $row[0]['mdp'], $row[0]['email'], $row[0]['token_id']);
			}
			return $res;
		}
	}