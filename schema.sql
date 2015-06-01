CREATE TABLE `token` (
  `token_id` varchar(255) NOT NULL DEFAULT '',
  `date_creation` datetime DEFAULT NULL,
  `date_expiration` datetime DEFAULT NULL,

  CONSTRAINT pk_token PRIMARY KEY(token_id)
)ENGINE=InnoDB;

CREATE TABLE `utilisateur` (
  `pseudo` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token_id` varchar(255) DEFAULT NULL,
  `token_reinit` VARCHAR(255) NULL,

  CONSTRAINT pk_utilisateur PRIMARY KEY (`pseudo`),
  CONSTRAINT fk_utilisateur_token FOREIGN KEY (token_id) REFERENCES token(token_id) ON DELETE SET NULL
)ENGINE=InnoDB;

CREATE TABLE pot (
    id INTEGER NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description BLOB NOT NULL,
    pseudo VARCHAR(255) NOT NULL,
    
    CONSTRAINT pk_pot PRIMARY KEY (id),
    CONSTRAINT fk_pot_utilisateur FOREIGN KEY (pseudo) REFERENCES utilisateur(pseudo) ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE ami (
    nom VARCHAR(255) NOT NULL,
    id_pot INTEGER NOT NULL,
    
    CONSTRAINT pk_ami PRIMARY KEY (nom, id_pot),
    CONSTRAINT fk_ami_pot FOREIGN KEY (id_pot) REFERENCES pot(id) ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE objet (
    id INTEGER NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prix INTEGER NOT NULL,
    id_pot INTEGER NOT NULL,
    
    CONSTRAINT pk_objet PRIMARY KEY (id),
    CONSTRAINT fk_objet_pot FOREIGN KEY (id_pot) REFERENCES pot(id) ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE transaction (
    id_objet INTEGER NOT NULL,
    id_ami VARCHAR(255) NOT NULL,
    montant FLOAT NOT NULL,   
    CONSTRAINT pk_transaction PRIMARY KEY (id_objet, id_ami),
    CONSTRAINT fk_transaction_ami FOREIGN KEY (id_ami) REFERENCES ami(nom) ON DELETE CASCADE,
    CONSTRAINT fk_transaction_objet FOREIGN KEY (id_objet) REFERENCES objet(id) ON DELETE CASCADE
)ENGINE=InnoDB;

DELIMITER //
CREATE TRIGGER update_transaction BEFORE UPDATE
ON transaction
FOR EACH ROW
BEGIN
	DECLARE montantTotalTransaction FLOAT;
    DECLARE montantObjet FLOAT;
    DECLARE exist INTEGER;
    DECLARE montantTotalNewTransaction FLOAT;
    
    SET @montantTotalTransaction := (SELECT IFNULL(ROUND(SUM(montant),2),0) FROM objet o, transaction t WHERE o.id = NEW.id_objet and t.id_objet = o.id);
    SET @prixObjet := (SELECT prix FROM objet WHERE id = NEW.id_objet);
    SET @montantTotalNewTransaction := (@montantTotalTransaction - OLD.montant) + NEW.montant;
    
    IF NEW.montant < 0 THEN
      SIGNAL SQLSTATE '45002' SET MESSAGE_TEXT = 'Le montant de la transaction ne peut être négatif';
    END IF;
    
    IF @montantTotalNewTransaction >  @prixObjet THEN
    	SIGNAL SQLSTATE '45003' SET MESSAGE_TEXT = 'La somme de toutes les transactions sur cet objet est plus grande que le prix de l\'objet';
    END IF;
END//

DELIMITER //
CREATE TRIGGER insert_transaction BEFORE INSERT
ON transaction
FOR EACH ROW
BEGIN
	DECLARE montantTotalTransaction FLOAT;
    DECLARE montantObjet FLOAT;
    DECLARE montantTotalNewTransaction FLOAT;
    DECLARE idPotObjet INTEGER;
    DECLARE idPotAmi INTEGER;
    
    SET @montantTotalTransaction := (SELECT IFNULL(ROUND(SUM(montant),2),0) FROM objet o, transaction t WHERE o.id = NEW.id_objet and t.id_objet = o.id);
    SET @prixObjet := (SELECT prix FROM objet WHERE id = NEW.id_objet);
    SET @montantTotalNewTransaction := @montantTotalTransaction + NEW.montant;
    SET @idPotObjet := (SELECT id_pot FROM objet WHERE id = NEW.id_objet);
    SET @idPotAmi := (SELECT id_pot FROM ami WHERE nom = NEW.id_ami);
    
    IF NEW.montant < 0 THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le montant de la transaction ne peut être négatif';
    END IF;
    
    IF @montantTotalNewTransaction >  @prixObjet THEN
    	SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = 'La somme de toutes les transactions sur cet objet est plus grande que le prix de l\'objet';
    END IF;
    
    IF @idPotObjet <> @idPotAmi THEN
    	SIGNAL SQLSTATE '45004' SET MESSAGE_TEXT = 'L\'ami et l\'objet n\'appartiennent pas au même pot';
    END IF;
END;

INSERT INTO transaction VALUES(1,'Toto',100);

UPDATE transaction SET montant = 100 WHERE id_objet = 1 AND id_ami = 'Coco';