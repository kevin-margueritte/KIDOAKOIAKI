<?php
	class Transaction {
		private $idObjet;
		private $idAmi;
		private $montant;
		private $db;

		public function __construct ($idObjet=null, $idAmi=null, $montant=0) {
			error_reporting(0);
			try {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX'); //Ouvre une connexion sur la BD
			} catch (PDOException $e) {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX');
			}
			$this->idObjet = $idObjet;
			$this->idAmi = $idAmi;
			$this->montant = $montant;
		}

		public function getMontant() {
			return $this->montant;
		}

		public function setMontant($newMontant) {
			$this->montant = $newMontant;
		}

		public function autoriseAjouterTransaction($pseudo) {
			$rqt = $this->db->prepare('SELECT o.id FROM objet o, pot p WHERE o.id = :idObjet AND o.id_pot = p.id AND p.pseudo = :pseudo');
			$rqt->execute(array(
			    'pseudo' => $pseudo,
			    'idObjet' => $this->idObjet
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			$res = false;
			if (count($row) > 0) {
				$res = true;
			}
			return $res;
		}

		public function exist(){
			$rqt = $this->db->prepare('SELECT * FROM transaction WHERE id_objet = :idObjet AND id_ami = :idAmi');
			$rqt->execute(array(
			    'idObjet' => $this->idObjet,
			    'idAmi' => $this->idAmi
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return count($row) != 0 ;
		}

		public function create(){
			$rqt = $this->db->prepare('INSERT INTO transaction (id_objet, id_ami, montant) ' .
			    'VALUES (:idObjet, :idAmi, :montant)');
			$code = $rqt->execute(array(
			    'idObjet' => $this->idObjet,
			    'idAmi' => $this->idAmi,
			    'montant' => $this->montant
			));
			return $code;
		}

		public function read() {
			$rqt = $this->db->prepare('SELECT id_objet, id_ami, montant FROM transaction WHERE id_objet = :idObjet AND id_ami = :idAmi');
			$rqt->execute(array(
			    'idObjet' => $this->idObjet,
			    'idAmi' => $this->idAmi
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			if (count($row) == 0) {
				$res = new Transaction($this->idObjet, $this->idAmi);
			}
			else {
				$res = new Transaction($row[0]['id_objet'], $row[0]['id_ami'], $row[0]['montant']);
			}
			return $res;
		}

		public function update() {
			$rqt = $this->db->prepare('UPDATE transaction SET montant = :montant WHERE id_objet = :idObjet AND id_ami = :idAmi');
			$code = $rqt->execute(array(
			    'montant' => $this->montant,
			    'idObjet' => $this->idObjet,
			    'idAmi' => $this->idAmi
			));
			return $code;
		}

		public function delete() {
			$rqt = $this->db->prepare('DELETE FROM transaction WHERE id_objet = :idObjet AND id_ami = :idAmi');
			$code = $rqt->execute(array(
			    'idObjet' => $this->idObjet,
			    'idAmi' => $this->idAmi
			));
			return $code;
		}

		public function prixTotalByAmi($idPot){
			$rqt = $this->db->prepare('SELECT IFNULL(ROUND(SUM(montant),2),0) as montantTotal FROM transaction, objet WHERE id_ami = :idAmi AND id_objet = id AND id_pot = :idPot');
			$code = $rqt->execute(array(
			    'idAmi' => $this->idAmi,
			    'idPot' => $idPot
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return $row[0]['montantTotal'];
		}
	}