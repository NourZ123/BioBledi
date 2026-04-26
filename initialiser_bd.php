<?php
require_once 'PHP/database_connection.php';

try {
    // 1. CREATION DES TABLES (Ordre logique : Parents d'abord)
    
    // Table Client
    $db->exec("CREATE TABLE IF NOT EXISTS client (
        ID INT(10) AUTO_INCREMENT PRIMARY KEY,
        Nom VARCHAR(255) NOT NULL,
        Prénom VARCHAR(255) NOT NULL,
        Email VARCHAR(255) NOT NULL UNIQUE,
        Telephone VARCHAR(20) NOT NULL,
        Adresse TEXT NOT NULL,
        Password VARCHAR(255) NOT NULL
    )");

    // Table Agriculteur
    $db->exec("CREATE TABLE IF NOT EXISTS agriculteur (
        ID INT(10) AUTO_INCREMENT PRIMARY KEY,
        Nom VARCHAR(255) NOT NULL,
        Prénom VARCHAR(255) NOT NULL,
        Email VARCHAR(255) NOT NULL UNIQUE,
        Telephone INT(8) NOT NULL,
        Adresse VARCHAR(255) NOT NULL,
        Password VARCHAR(255) NOT NULL,
        Type_de_production VARCHAR(255),
        Nom_ferme VARCHAR(255)
    )");

    // Table Produit
    $db->exec("CREATE TABLE IF NOT EXISTS produit (
        ID INT(11) AUTO_INCREMENT PRIMARY KEY,
        image VARCHAR(255) NOT NULL,
        quantité INT(11) NOT NULL,
        nom_produit VARCHAR(255) NOT NULL,
        catégorie VARCHAR(255) NOT NULL,
        offre FLOAT NOT NULL,
        prix FLOAT NOT NULL,
        unité VARCHAR(255) NOT NULL,
        région VARCHAR(255) NOT NULL,
        ID_agriculteur INT(11) NOT NULL,
        description TEXT
    )");

    // Table Commande (id_client sans espace et status inclus directement)
    $db->exec("CREATE TABLE IF NOT EXISTS commande (
        id_commande INT AUTO_INCREMENT PRIMARY KEY,
        montant DECIMAL(10, 2) NOT NULL,
        adresse VARCHAR(255) NOT NULL,
        id_client INT NOT NULL,
        status VARCHAR(50), 
        date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_commande_client FOREIGN KEY (id_client) REFERENCES client(ID) ON DELETE CASCADE
    )");

    // Table Panier
    $db->exec("CREATE TABLE IF NOT EXISTS Paniers_commande (
        id_commande INT NOT NULL,
        id_produit INT NOT NULL,
        quantite_produit INT NOT NULL,
        PRIMARY KEY (id_commande, id_produit),
        CONSTRAINT fk_panier_commande FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
        CONSTRAINT fk_panier_produit FOREIGN KEY (id_produit) REFERENCES produit(ID) ON DELETE CASCADE
    )");

    // 2. INITIALISATION (On vide avant pour éviter les erreurs de doublons d'Email)
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $db->exec("TRUNCATE TABLE Paniers_commande; TRUNCATE TABLE commande; TRUNCATE TABLE produit; TRUNCATE TABLE client; TRUNCATE TABLE agriculteur;");
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // Agriculteurs
    $stmt = $db->prepare("INSERT INTO agriculteur (Nom, Prénom, Email, Telephone, Adresse, Password, Type_de_production, Nom_ferme) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $agriculteurs = [
        ['Aymen', 'Ben Ali', 'aymen@ferme.tn', '12345678', 'Sfax', 'mdpaa', 'Fruits bio', 'Ferme El Khir'],
        ['Ahmed', 'Chaabane', 'ahmed@ferme.tn', '23456789', 'Nabeul', 'mdpbb', 'Légumes', 'Ferme Chaabane']
    ];
    foreach ($agriculteurs as $agri) { $stmt->execute($agri); }

    // Produits
    $stmt = $db->prepare("INSERT INTO produit (image, nom_produit, catégorie, prix, offre, quantité, unité, région, ID_agriculteur, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $produits = [
        ['images/produits/pomme.jpg', 'Pomme', 'Fruits', 15, 10, 50, 'kg', 'Sfax', 1, 'Bio'],
        ['images/produits/carotte.jpg', 'Carotte', 'Légumes', 1.5, 0, 100, 'kg', 'Nabeul', 2, 'Bio']
    ];
    foreach ($produits as $prod) { $stmt->execute($prod); }

    // Clients
    $stmt = $db->prepare("INSERT INTO client (Nom, Prénom, Email, Telephone, Adresse, Password) VALUES (?, ?, ?, ?, ?, ?)");
    $clients = [
        ['Karoui', 'Sami', 'sami@gmail.com', '71234567', 'Tunis', password_hash('client123', PASSWORD_DEFAULT)]
    ];
    foreach ($clients as $c) { $stmt->execute($c); }

    // Commandes (Attention au nom de la colonne : status)
    $stmt = $db->prepare("INSERT INTO commande (montant, adresse, id_client, status) VALUES (?, ?, ?, ?)");
    $commandes = [
        [50.00, 'Tunis', 1, 'Livrée']
    ];
    foreach ($commandes as $cmd) { $stmt->execute($cmd); }

    echo "Base de données initialisée avec succès !";

} catch (PDOException $e) {
    echo "ERREUR : " . $e->getMessage();
}
?>