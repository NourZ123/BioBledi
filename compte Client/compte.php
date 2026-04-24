<?php
session_start();
require "../PHP/database_connection.php"; 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Vérifier la connexion
if (!$conn) {
    echo json_encode(["error" => "Erreur de connexion à la base de données"]);
    exit();
}

// Définir le jeu de caractères
$conn->set_charset("utf8mb4");

// Requête SQL pour récupérer tous les produits
$sql = "SELECT 
            p.id,
            p.nom_produit as nom,
            p.categorie,
            p.quantite as stock,
            p.prix,
            p.offre,
            p.unite,
            p.region,
            p.ID_agriculteur,
            COALESCE(a.nom_agriculteur, 'Producteur local') as agriculteur,
            p.description,
            CASE 
                WHEN p.categorie = 'Fruits' THEN 'Été/Automne'
                WHEN p.categorie = 'Légumes' THEN 'Printemps/Été'
                WHEN p.categorie = 'Miel' THEN 'Toute l\'année'
                WHEN p.categorie = 'Epicerie' THEN 'Toute l\'année'
                WHEN p.categorie = 'Oeufs' THEN 'Toute l\'année'
                ELSE 'Toute l\'année'
            END as saison,
            4 as note
        FROM produit p
        LEFT JOIN agriculteur a ON p.ID_agriculteur = a.id
        WHERE p.quantite > 0
        ORDER BY p.region, p.nom_produit";

$result = $conn->query($sql);

$produits = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Convertir l'offre en entier ou false
        $offre = ($row['offre'] > 0) ? (int)$row['offre'] : false;
        
        $produits[] = [
            'id' => (int)$row['id'],
            'nom' => $row['nom'],
            'region' => !empty($row['region']) ? $row['region'] : 'Tunisie',
            'saison' => $row['saison'],
            'agriculteur' => $row['agriculteur'],
            'note' => (int)$row['note'],
            'stock' => (int)$row['stock'],
            'prix' => (float)$row['prix'],
            'offre' => $offre,
            'categorie' => $row['categorie'],
            'unite' => !empty($row['unite']) ? $row['unite'] : 'pièce',
            'description' => $row['description']
        ];
    }
}

// Fermer la connexion (optionnel car require gère déjà)
// $conn->close();

// Retourner les produits en JSON
echo json_encode($produits, JSON_UNESCAPED_UNICODE);
?>