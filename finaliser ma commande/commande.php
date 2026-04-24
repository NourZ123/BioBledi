<?php 
session_start();
require_once '../database_connection.php';
if (isset($_SESSION['user_data']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = $_SESSION['user_data']['ID'];
    $montant = $_SESSION['totalàpayer'];
    $adresse_a_inserer = ""; 
    $status = "impayé";

    $livraison = $_POST['choix_livraison'] ?? '';
    
    if ($livraison === "domicile") { 
        $adresse_a_inserer = $_SESSION['user_data']['Adresse'];
    } elseif ($livraison === "PR") {
        $adresse_a_inserer = $_POST['adress'] ?? 'Non précisé';
    } elseif($livraison === "surplace") {
        $adresse_a_inserer = "Retrait à la ferme";
    }

    $paiement = $_POST['choix_paiement'] ?? '';

    if ($paiement === "cardpayment") {
        $nom = trim($_POST['nom'] ?? '');
        $num = trim($_POST['cardnumber'] ?? '');
        $exp = trim($_POST['dateexp'] ?? '');
        $cvc = trim($_POST['cvc'] ?? '');

        if (!empty($nom) && !empty($num) && !empty($exp) && !empty($cvc)) {
            $status = "payé";
        }
    }

    if (!empty($id) && $montant > 0) {
        $sql = "INSERT INTO commande (montant, adresse, id_client, statut) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$montant, $adresse_a_inserer, $id, $status]);

        $id_commande = $db->lastInsertId();

        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            $sql_panier = "INSERT INTO paniers_commande (id_commande, id_produit, quantite_produit) VALUES (?, ?, ?)";
            $stmt_panier = $db->prepare($sql_panier);

            foreach ($_SESSION['panier'] as $id_produit => $quantite) {
                $stmt_panier->execute([$id_commande, $id_produit, $quantite]);
                $sql_update = "UPDATE produit SET quantité = quantité - ? WHERE ID = ?";
                $stmt_update = $db->prepare($sql_update);
                $stmt_update->execute([$quantite, $id_produit]);
            }
            
            unset($_SESSION['panier']);
        }

        header("Location: ../compte Client/compte.php");
        exit();
    }
}
?>