<?php
	class Ami {
		private $nom;
		private $idPot;
		private $db;

		public function __construct ($nom=null, $idPot=null) {
			error_reporting(0);
			try {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX'); //Ouvre une connexion sur la BD
			} catch (PDOException $e) {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX');
			}
			$this->nom = $nom;
			$this->idPot = $idPot;
		}

		public function getNom() {
			return $this->nom;
		}

		public function getidPot() {
			return $this->idPot;
		}


		public function exist(){
			$rqt = $this->db->prepare('SELECT * FROM ami WHERE nom = :nom AND id_pot = :idPot');
			$rqt->execute(array(
			    'nom' => $this->nom,
			    'idPot' => $this->idPot
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return count($row) != 0 ;
		}

		public function autoriseAjouterAmi($pseudo) { //Permet aux utilisateurs de modifier les amis de leurs pots, permet d'eviter le changement des parametres dans l'URL
			$rqt = $this->db->prepare('SELECT id FROM pot WHERE id = :idPot AND pseudo = :pseudo');
			$rqt->execute(array(
			    'pseudo' => $pseudo,
			    'idPot' => $this->idPot
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			$res = false;
			if (count($row) > 0) {
				$res = true;
			}
			return $res;
		}

		public function create(){
			$rqt = $this->db->prepare('INSERT INTO ami (nom, id_pot) ' .
			    'VALUES (:nom, :idPot)');
			$code = $rqt->execute(array(
			    'nom' => $this->nom,
			    'idPot' => $this->idPot,
			));
			return $code;
		}

		public function read() {
			$rqt = $this->db->prepare('SELECT nom, id_pot FROM ami WHERE nom = :nom AND id_pot = :idPot');
			$rqt->execute(array(
			    'nom' => $this->nom,
			    'idPot' => $this->idPot
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			if (count($row) == 0) {
				$res = new Ami($this->nom);
			}
			else {
				$res = new Ami($row[0]['nom'], $row[0]['idPot']);
			}
			return $res;
		}

		public function update() {
			$rqt = $this->db->prepare('UPDATE ami SET nom = :nom, id_pot = :idPot WHERE nom = :nom AND id_pot = :idPot');
			$code = $rqt->execute(array(
			    'nom' => $this->nom,
			    'idPot' => $this->idPot,
			));
			return $code;
		}

		public function delete() {
			$rqt = $this->db->prepare('DELETE FROM ami WHERE nom = :nom AND id_pot = :idPot');
			$code = $rqt->execute(array(
			    'nom' => $this->nom,
			    'idPot' => $this->idPot,
			));
			return $code;
		}

		public function readAll() {
			$rqt = $this->db->prepare('SELECT nom, id_pot FROM ami WHERE id_pot = :idPot');
			$rqt->execute(array(
			    'idPot' => $this->idPot
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			for ($i = 0; $i < count($row); $i++) {
   				$tab[$i]['nom'] = $row[$i]['nom'];
   				$tab[$i]['idPot'] = $row[$i]['id_pot'];
			}
			return $tab;
		}

	}