<?php
	class Pot {
		private $id;
		private $titre;
		private $description;
		private $pseudo;
		private $db;

		public function __construct ($titre=null, $description=null, $pseudo=null, $id=null) {
			error_reporting(0);
			try {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX'); //Ouvre une connexion sur la BD
			} catch (PDOException $e) {
				$this->db = new PDO('mysql:host=XXXX; dbname=XXXX', 'XXXX', 'XXXX');
			}
			$this->id = $id;
			$this->titre = $titre;
			$this->description = $description;
			$this->pseudo = $pseudo;
		}

		public function getId() {
			return $this->id;
		}

		public function getTitre() {
			return $this->titre;
		}

		public function getDescription() {
			return $this->description;
		}

		public function autoriseModification($pseudo) {
			$rqt = $this->db->prepare('SELECT id FROM pot WHERE id = :id AND pseudo = :pseudo');
			$rqt->execute(array(
			    'pseudo' => $pseudo,
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			$res = false;
			if (count($row) > 0) {
				$res = true;
			}
			return $res;
		}

		public function exist(){
			$rqt = $this->db->prepare('SELECT * FROM pot WHERE id = :id');
			$rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return count($row) != 0 ;
		}

		public function create(){
			$this->id = $this-> autoIncrementeId();
			$rqt = $this->db->prepare('INSERT INTO pot (id, titre, description, pseudo) ' .
			    'VALUES (:id, :titre, :description, :pseudo)');
			$code = $rqt->execute(array(
			    'titre' => $this->titre,
			    'description' => $this->description,
			    'pseudo' => $this->pseudo,
			    'id' => $this->id
			));
			return $code;
		}

		public function read() {
			$rqt = $this->db->prepare('SELECT id, titre, description, pseudo FROM pot WHERE id = :id');
			$rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			if (count($row) == 0) {
				$res = new Pot(null, null, null, $this->id);
			}
			else {
				$res = new Pot($row[0]['titre'], $row[0]['description'], $row[0]['pseudo'], $row[0]['id']);
			}
			return $res;
		}

		public function update() {
			$rqt = $this->db->prepare('UPDATE pot SET id = :id, titre = :titre, description = :description, pseudo = :pseudo WHERE id = :id');
			$code = $rqt->execute(array(
			    'id' => $this->id,
			    'titre' => $this->titre,
			    'description' => $this->description,
			    'pseudo' => $this->pseudo
			));
			return $code;
		}

		public function autoIncrementeId() { //L'auto increment ne peut pas être géré dans la BD, car une fois un pot inserer on perd l'id du pot, pour celaqu'il est preferable traiter cette auto incrementation directement en php
			$rqt = $this->db->prepare('SELECT max(id) as id FROM pot');
			$rqt->execute();
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			$res = 0;
			if (count($row) != 0) {
				$res = $row[0]['id'];
			}
			return $res + 1;
		}

		public function delete() {
			$rqt = $this->db->prepare('DELETE FROM pot WHERE id = :id');
			$code = $rqt->execute(array(
			    'id' => $this->id
			));
			return $code;
		}

		public function readAllByPseudo() {
			$rqt = $this->db->prepare('SELECT * FROM pot WHERE pseudo = :pseudo');
			$rqt->execute(array(
			    'pseudo' => $this->pseudo
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			$tab = null;
			for ($i = 0; $i < count($row); $i++) {
   				$tab[$i]['id'] = $row[$i]['id'];
   				$tab[$i]['titre'] = $row[$i]['titre'];
   				$tab[$i]['pseudo'] = $row[$i]['pseudo'];
   				$tab[$i]['description'] = $row[$i]['description'];
			}
			return $tab;
		}

		public function montantTotalObjet(){
			$rqt = $this->db->prepare('SELECT IFNULL(ROUND(SUM(prix),2),0) as prixObjet FROM objet WHERE id_pot = :id');
			$code = $rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return $row[0]['prixObjet'];
		}

		public function montantTotalTransaction(){
			$rqt = $this->db->prepare('SELECT IFNULL(ROUND(SUM(montant),2),0) as montantTransaction FROM transaction t, objet o WHERE o.id_pot = :id and o.id = t.id_objet');
			$code = $rqt->execute(array(
			    'id' => $this->id
			));
			$row = $rqt->fetchAll(PDO::FETCH_ASSOC);
			return $row[0]['montantTransaction'];
		}

	}