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

    
// initialiser les tables
// initialiser table agriculteur
$agriculteurs = [
    ['Aymen', 'Ben Ali', 'aymen@ferme.tn', '12345678', 'Sfax', 'mdpaa', 'Fruits bio', 'Ferme El Khir'],
    ['Ahmed', 'Chaabane', 'ahmed@ferme.tn', '23456789', 'Zone agricole, Nabeul', 'mdpbb', 'Légumes', 'Ferme Chaabane'],
    ['Salah', 'Boumiza', 'salah@ferme.tn', '34567890', 'Montagne, Kef', 'mdpcc', 'Miel', 'Miel Boumiza'],
    ['Nouur', 'Zahrouni', 'nour.zahrouni1@ensi-uma.tn', '45678901', 'Nabeul', 'mdp123', 'Huile d\'olive', 'Coopérative Errayhan'],
    ['Eya', 'Smaoui', 'eya.smaoui@ensi-uma.tn', '56789012', 'Tozeur', 'mdp456', 'Dattes', 'Ferme El Bey']
];
foreach ($agriculteurs as $agri) {
    $stmt = $db->prepare("INSERT INTO agriculteur (Nom, Prénom, Email, Telephone, Adresse, Password, Type_de_production, Nom_ferme) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$agri[0], $agri[1], $agri[2], $agri[3], $agri[4], $agri[5], $agri[6], $agri[7]]);
}
//initialiser la table produit
 $produits = [
    ['images/produits/pomme.jpg', 'Pomme', 'Fruits', 15, 10, 50, 'kg', 'Sfax', 1, 'Pommes fraîches bio'],
    ['images/produits/carotte.jpg', 'Carotte', 'Légumes', 1.5, 0, 100, 'kg', 'Nabeul', 2, 'Carottes bio de saison'],
    ['images/produits/miel.jpg', 'Miel', 'Miel', 200, 15, 45, 'pot', 'Kef', 3, 'Miel pur'],
    ['images/produits/huile.jpg', 'Huile d\'olive', 'Huile', 30, 5, 120, 'litre', 'Sfax', 4, 'Huile d\'olive extra vierge'],
    ['images/produits/dattes.jpg', 'Deglet Nour', 'Dattes', 25, 5, 200, 'kg', 'Tozeur', 5, 'Dattes premium']
];

foreach ($produits as $prod) {
    $stmt = $db->prepare("INSERT INTO produit (image, nom_produit, catégorie, prix, offre, quantité, unité, région, ID_agriculteur, description) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$prod[0], $prod[1], $prod[2], $prod[3], $prod[4], $prod[5], $prod[6], $prod[7], $prod[8], $prod[9]]);
}
// initialiser la table client
$clients = [
    ['Karoui', 'Sami', 'sami@gmail.com', '71234567', 'Avenue Habib Bourguiba, Tunis', 'client123'],
    ['Feki', 'Leila', 'leila@yahoo.fr', '72345678', 'Rue de la liberté, Sfax', 'client456'],
    ['Zahrouni', 'Nour', 'nour@gmail.com', '73456789', 'Boulevard du 14 Janvier, Nabeul', 'client789'],
    ['Smaoui', 'Eya', 'smaoui@gmail.com', '74567890', 'Cité Ennasr, Tunis', 'client100'],
    ['Gharbi', 'Sonia', 'sonia@gmail.com', '75678901', 'Route de la Marsa, Tunis', 'client200']
];
foreach ($clients as $client) {
    $stmt = $db->prepare("INSERT INTO client (Nom, Prénom, Email, Telephone, Adresse, Password) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$client[0], $client[1], $client[2], $client[3], $client[4], password_hash($client[5], PASSWORD_DEFAULT)]);
}
// initialiser la table commande
$commandes = [
    [50.00, 'Avenue Habib Bourguiba, Tunis', 1, 'Livrée'],
    [25.50, 'Rue de la liberté, Sfax', 2, 'En cours'],
    [120.00, 'Boulevard du 14 Janvier, Nabeul', 3, 'En attente'],
    [35.75, 'Cité Ennasr, Tunis', 4, 'Livrée'],
    [80.00, 'Route de la Marsa, Tunis', 5, 'Annulée']
];
foreach ($commandes as $commande) {
    $stmt = $db->prepare("INSERT INTO commande (montant, adresse, id_client, statut) VALUES (?, ?, ?, ?)");
    $stmt->execute([$commande[0], $commande[1], $commande[2], $commande[3]]);
}
//initialiser paniers_commande
$paniers = [
    [1, 1, 3],  // Commande 1 Produit 1 quantité 3
    [1, 2, 2],  // Commande 1 Produit 2 quantité 2
    [2, 3, 1],  
    [3, 4, 5],  
    [4, 5, 2],  
    [5, 1, 1],  
    [2, 4, 2]   
];
foreach ($paniers as $panier) {
    $stmt = $db->prepare("INSERT INTO Paniers_commande (id_commande, id_produit, quantite_produit) VALUES (?, ?, ?)");
    $stmt->execute([$panier[0], $panier[1], $panier[2]]);
}


?>