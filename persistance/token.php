<?php
	require_once '../persistance/utilisateur.php';

	class Token {
		private $token_id;
		private $date_creation;
		private $date_expiration;
		private $db;

		public function __construct ($token_id = null, $date_creation = null, $date_expiration = null) {
			error_reporting(0);
			try {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX'); //Ouvre une connexion sur la BD
			} catch (PDOException $e) {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX');
			}
			$this->date_creation = $date_creation;
			$this->token_id = $token_id;
			$this->date_expiration = $date_expiration;
		}

		public function getTokenId() {
			return $this->token_id;
		}

		public function getDateExpiration() {
			return $this->date_expiration;
		}

		public function getDateCreation() {
			return $this->date_creation;
		}


		public function create(){
			$rqt = $this->db->prepare('INSERT INTO token (token_id, date_creation, date_expiration) ' .
			    'VALUES (:token, :date_creat, :date_exp)');
			$code = $rqt->execute(array(
			    'token' => $this->token_id,
			    'date_creat' => $this->date_creation,
			    'date_exp' => $this->date_expiration
			));
			return $code;
		}

		public function read() {
			$rqt = $this->db->prepare('SELECT token_id, date_creation, date_expiration FROM token WHERE token_id = :token');
			$rqt->execute(array(
			    'token' => $this->token_id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			if (count($row)==0) {
				$res = new Token($this->token_id);
			}
			else {
				$res = new Token($row[0]['token_id'], $row[0]['date_creation'], $row[0]['date_expiration']);
			}
			return $res;
		}

		public function update() {
			$rqt = $this->db->prepare('UPDATE token SET token_id = :token, date_creation = :date_creat, date_expiration = date_exp WHERE token_id = :token');
			$code = $rqt->execute(array(
			    'token' => $this->token_id,
			    'date_creat' => $this->date_expiration,
			    'date_exp' => $this->email
			));
			return $code;
		}

		public function delete() {
			$rqt = $this->db->prepare('DELETE FROM token WHERE token_id = :token');
			$code = $rqt->execute(array(
			    'token' => $this->token_id
			));
			return $code;
		}

		public function supprimeTokenCourant($pseudo) {
			$rqt = $this->db->prepare('DELETE FROM token WHERE token_id = (SELECT u.token_id FROM utilisateur u, token t WHERE u.pseudo = :pseudo AND u.token_id = t.token_id) ');
			$rqt->execute(array(
			    'pseudo' => $pseudo
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return count($row) != 0;
		}
	}