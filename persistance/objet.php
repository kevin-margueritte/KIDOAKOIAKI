<?php
	require_once '../persistance/transaction.php';
	
	class Objet {
		private $id;
		private $nom;
		private $prix;
		private $idPot;
		private $db;

		public function __construct ($nom=null, $prix=null, $idPot=null, $id=null) {
			error_reporting(0);
			try {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX'); //Ouvre une connexion sur la BD
			} catch (PDOException $e) {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX');
			}
			$this->nom = $nom;
			$this->idPot = $idPot;
			$this->prix = $prix;
			$this->id = $id;
		}

		public function getId() {
			return $this->id;
		}

		public function getIdPot() {
			return $this->idPot;
		}

		public function getNom() {
			return $this->nom;
		}

		public function getPrix() {
			return $this->prix;
		}

		public function setNom($newNom) {
			$this->nom = $newNom;
		}

		public function setPrix($newPrix) {
			$this->prix = $newPrix;
		}

		public function autoriseAjouterObjet($pseudo) {
			$rqt = $this->db->prepare('SELECT id FROM pot WHERE id = :idPot AND pseudo = :pseudo'); //Verifie si le pot existe pour cet utilisateur, s'il existe il s'agit du bon utilisateur
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

		public function autoIncrementeId() { 
			$rqt = $this->db->prepare('SELECT max(id) as id FROM objet');
			$rqt->execute();
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			$res = 0;
			if (count($row) != 0) {
				$res = $row[0]['id'];
			}
			return $res + 1;
		}

		public function exist(){
			$rqt = $this->db->prepare('SELECT * FROM objet WHERE id = :id');
			$rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return count($row) != 0 ;
		}

		public function create(){
			$this->id = $this->autoIncrementeId();
			$rqt = $this->db->prepare('INSERT INTO objet (nom, prix, id_pot, id) ' .
			    'VALUES (:nom, :prix, :idPot, :id)');
			$code = $rqt->execute(array(
			    'nom' => $this->nom,
			    'prix' => $this->prix,
			    'idPot' => $this->idPot,
			    'id' => $this->id
			));
			return $code;
		}

		public function read() {
			$rqt = $this->db->prepare('SELECT nom, prix, id_pot, id FROM objet WHERE id = :id');
			$rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			if (count($row) == 0) {
				$res = new Objet();
			}
			else {
				$res = new Objet($row[0]['nom'], $row[0]['prix'], $row[0]['id_pot'], $row[0]['id']);
			}
			return $res;
		}

		public function update() {
			$rqt = $this->db->prepare('UPDATE objet SET nom = :nom, prix = :prix, id_pot = :idPot WHERE id = :id');
			$code = $rqt->execute(array(
			    'nom' => $this->nom,
			    'prix' => $this->prix,
			    'idPot' => $this->idPot,
			    'id' => $this->id
			));
			return $code;
		}

		public function delete() {
			$rqt = $this->db->prepare('DELETE FROM objet WHERE id = :id');
			$code = $rqt->execute(array(
			    'id' => $this->id
			));
			return $code;
		}

		public function readAllByIdPot() {
			$rqt = $this->db->prepare('SELECT * FROM objet WHERE id_pot = :idPot');
			$rqt->execute(array(
			    'idPot' => $this->idPot
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			$tab = null;
			for ($i = 0; $i < count($row); $i++) {
   				$tab[$i]['id'] = $row[$i]['id'];
   				$tab[$i]['nom'] = $row[$i]['nom'];
   				$tab[$i]['prix'] = $row[$i]['prix'];
   				$tab[$i]['idPot'] = $row[$i]['id_pot'];
			}
			return $tab;
		}

		public function montantTotalTransaction(){
			$rqt = $this->db->prepare('SELECT IFNULL(ROUND(SUM(montant),2),0) as montantTotal FROM objet o, transaction t WHERE o.id = :id and t.id_objet = o.id');
			$code = $rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return $row[0]['montantTotal'];
		}

		public function deleteAllTransaction() {
			$rqt = $this->db->prepare('SELECT t.id_objet, t.id_ami FROM objet o, transaction t WHERE o.id = :id and t.id_objet = o.id');
			$code = $rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			for ($i = 0; $i < count($row); $i++) {
				$transaction = new Transaction($row[$i]['id_objet'],$row[$i]['id_ami']);
				$transaction->delete();
			}
			return count($row) != 0;
		}
	}