<?php
require_once 'PHP/database_connection.php';
    $sql = "CREATE TABLE IF NOT EXISTS client (
        Nom VARCHAR(255) NOT NULL,
        Prénom VARCHAR(255) NOT NULL,
        Email VARCHAR(255) NOT NULL UNIQUE,
        ID INT(10) AUTO_INCREMENT PRIMARY KEY,
        Telephone VARCHAR(20) NOT NULL,
        Adresse TEXT NOT NULL,
        Password VARCHAR(255) NOT NULL
    )";
    $db->exec($sql);
    $sql1 = "CREATE TABLE IF NOT EXISTS agriculteur (
        Nom VARCHAR(255) NOT NULL,
        Prénom VARCHAR(255) NOT NULL,
        Email VARCHAR(255) NOT NULL UNIQUE,
        ID INT(10) AUTO_INCREMENT PRIMARY KEY,
        Telephone INT(8) NOT NULL,
        Adresse VARCHAR(255) NOT NULL,
        Password VARCHAR(255) NOT NULL,
        Type_de_production VARCHAR(255) DEFAULT NULL,
        Nom_ferme VARCHAR(255) DEFAULT NULL
    );";
    $db->exec($sql1);
    $sql5="CREATE TABLE IF NOT EXISTS produit (
        image VARCHAR(255) NOT NULL,
        ID INT(11) AUTO_INCREMENT PRIMARY KEY,
        quantité INT(11) NOT NULL,
        nom_produit VARCHAR(255) NOT NULL,
        catégorie VARCHAR(255) NOT NULL,
        offre FLOAT NOT NULL,
        prix FLOAT NOT NULL,
        unité VARCHAR(255) NOT NULL,
        région VARCHAR(255) NOT NULL,
        ID_agriculteur INT(11) NOT NULL,
        description TEXT DEFAULT NULL
    );";
    $sql2="CREATE TABLE commande (
        id_commande INT AUTO_INCREMENT PRIMARY KEY,
        montant DECIMAL(10, 2) NOT NULL,
        adresse VARCHAR(255) NOT NULL,
        id client INT NOT NULL,
        date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_commande_client 
            FOREIGN KEY (id_client) 
            REFERENCES client(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    );";

    $sql3="CREATE TABLE Paniers_commande (
        id_commande INT NOT NULL,
        id_produit INT NOT NULL,
        quantite_produit INT NOT NULL,
        PRIMARY KEY (id_commande, id_produit),
        CONSTRAINT fk_panier_commande 
            FOREIGN KEY (id_commande) 
            REFERENCES commande(id_commande) 
            ON DELETE CASCADE,
        CONSTRAINT fk_panier_produit 
            FOREIGN KEY (id_produit) 
            REFERENCES produit(ID) 
            ON DELETE CASCADE
    );";

    $sql4="ALTER TABLE commande
    ADD status VARCHAR(50);";

    
   


?>