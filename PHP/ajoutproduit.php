<?php
session_start();
require_once '../PHP/database_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom_produit']);
    $prix = floatval($_POST['prix']);
    $quantite = intval($_POST['quantite']);
    $categorie = $_POST['categorie'];
    $id_agri = intval($_POST['id_agriculteur']);
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../images/produits/';
        $nomFichier = time() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $nomFichier);
        $cheminBDD = 'images/produits/' . $nomFichier;

        $stmt = $db->prepare("INSERT INTO produit (nom_produit, prix, quantité, catégorie, ID_agriculteur, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prix, $quantite, $categorie, $id_agri, $cheminBDD]);
    }
}

header("Location: ../agriculteur/agriculteur.php?msg=Produit ajouté");
exit();