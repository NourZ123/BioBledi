<?php
session_start();
require_once '../PHP/database_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("SELECT image FROM produit WHERE ID = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();
    if ($p && file_exists('../' . $p['image'])) {
        unlink('../' . $p['image']);
    }

    $stmt = $db->prepare("DELETE FROM produit WHERE ID = ?");
    $stmt->execute([$id]);
}

header("Location: ../PHP/agriculteur.php?msg=Produit supprimé");
exit();