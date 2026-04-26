<?php
session_start();
require_once '../PHP/database_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom_produit']) ?? '';
    $prix      = floatval($_POST['prix']) ?? 0;
    $quantite  = intval($_POST['quantite']) ?? 0;
    $categorie = $_POST['categorie'] ?? '';
    $id_agri   = intval($_POST['id_agriculteur']) ?? 0;
    $region    = $_POST['region'] ?? '';
    $offre     = floatval($_POST['offre'] ?? 0);
    $unite     = $_POST['unite'] ?? '';

    $erreurs = [];

    if (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-]{2,255}$/", $nom)) {
        $erreurs['nom_produit'] = "Nom invalide (max 255).";
    }

    if ($quantite < 0) {
        $erreurs['quantite'] = "Quantité entière positive requise.";
    }

    if ($prix <= 0) {
        $erreurs['prix'] = "Prix numérique positif requis.";
    }

    if ($offre < 0) {
        $erreurs['offre'] = "Offre numérique positive requise.";
    }

    if (!preg_match("/^[\\[a-zA-ZÀ-ÿ\s]{1,255}$/", $unite)) {
        $erreurs['unite'] = "Unité invalide.";
    }
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $erreurs['image'] = "Format JPG, PNG ou WEBP uniquement.";
        }
    } else {
        $erreurs['image'] = "Image obligatoire.";
    }

    if (!empty($erreurs)) {
        $_SESSION['erreurs_produit'] = $erreurs;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $uploadDir = '../images/produits/';
        $nomFichier = time() . '_' . basename($_FILES['photo']['name']);
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $nomFichier)) {
            $cheminBDD = 'images/produits/' . $nomFichier;

            $sql = "INSERT INTO produit (nom_produit, prix, quantité, catégorie, ID_agriculteur, image, région, offre, unité) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $nom, 
                $prix, 
                $quantite, 
                $categorie, 
                $id_agri, 
                $cheminBDD, 
                $region, 
                $offre, 
                $unite
            ]);

            header("Location: ../PHP/agriculteur.php?msg=Produit ajouté");
            exit();
        }
    }
}