<?php
require_once '../PHP/database_connection.php';
function recupererProduitsEtFiltre($db, $categories_par_defaut) {
    $filtre = isset($_GET['categorie']) ? $_GET['categorie'] : 'tous';

    if (!empty($filtre) && $filtre !== 'tous') {
        $stmt = $db->prepare("SELECT * FROM `produit` WHERE `catégorie` = ?");
        $stmt->execute([$filtre]);
    } else {
        $placeholders = implode(',', array_fill(0, count($categories_par_defaut), '?'));
        $stmt = $db->prepare("SELECT * FROM `produit` WHERE `catégorie` IN ($placeholders)");
        $stmt->execute($categories_par_defaut);
    }
    
    return [
        'items' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        'filtre_actif' => $filtre
    ];
}
?>