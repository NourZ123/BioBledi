<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

require_once '../database_connection.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {

    case 'get_agriculteur':
        $id   = intval($_GET['id'] ?? 1);
        $stmt = $db->prepare("SELECT * FROM agriculteur WHERE ID = ?");
        $stmt->execute([$id]);
        $agri = $stmt->fetch();
        echo json_encode($agri
            ? ['succes' => true,  'agriculteur' => $agri]
            : ['succes' => false, 'erreur' => 'Agriculteur non trouvé']
        );
        break;

    case 'get_produits':
        $id   = intval($_GET['id_agriculteur'] ?? 1);
        $stmt = $db->prepare("SELECT * FROM produit WHERE ID_agriculteur = ?");
        $stmt->execute([$id]);
        echo json_encode(['succes' => true, 'produits' => $stmt->fetchAll()]);
        break;

    case 'ajouter_produit':
        $nom         = trim($_POST['nom_produit']     ?? '');
        $prix        = floatval($_POST['prix']         ?? 0);
        $quantite    = intval($_POST['quantite']       ?? 0);
        $categorie   = trim($_POST['categorie']        ?? 'Fruits');
        $offre       = intval($_POST['offre']          ?? 0);
        $region      = trim($_POST['region']           ?? '');
        $description = trim($_POST['description']      ?? '');
        $unite       = trim($_POST['unite']            ?? 'kg');
        $id_agri     = intval($_POST['id_agriculteur'] ?? 1);

        if (!$nom || !$prix || !$quantite) {
            echo json_encode(['succes' => false, 'erreur' => 'Champs obligatoires manquants (nom, prix, stock)']);
            break;
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['succes' => false, 'erreur' => 'La photo est obligatoire']);
            break;
        }

        $file    = $_FILES['photo'];
        $maxSize = 5 * 1024 * 1024; // 5 Mo

        if ($file['size'] > $maxSize) {
            echo json_encode(['succes' => false, 'erreur' => 'La photo ne doit pas dépasser 5 Mo']);
            break;
        }

        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mimeType, $allowedTypes)) {
            echo json_encode(['succes' => false, 'erreur' => 'Format accepté : JPG, PNG, WEBP uniquement']);
            break;
        }

        $uploadDir = dirname(__DIR__) . '/images/produits/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = match($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg'
        };
        $nomFichier = 'produit_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        $cheminFull = $uploadDir . $nomFichier;
        $cheminBDD  = 'images/produits/' . $nomFichier; 

        if (!move_uploaded_file($file['tmp_name'], $cheminFull)) {
            echo json_encode(['succes' => false, 'erreur' => 'Erreur lors de l\'enregistrement de la photo']);
            break;
        }

        $stmt = $db->prepare("
            INSERT INTO produit
                (nom_produit, prix, `quantité`, `catégorie`, offre, `région`, description, `unité`, ID_agriculteur, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nom, $prix, $quantite, $categorie, $offre, $region, $description, $unite, $id_agri, $cheminBDD]);

        echo json_encode([
            'succes'  => true,
            'message' => "\"$nom\" a été ajouté avec succès !",
            'id'      => $db->lastInsertId(),
            'image'   => $cheminBDD
        ]);
        break;

    // ─── MODIFIER UN PRODUIT ──────────────────────────────────────────────────
    case 'modifier_produit':
        $id       = intval($_POST['id']          ?? 0);
        $nom      = trim($_POST['nom_produit']   ?? '');
        $prix     = floatval($_POST['prix']      ?? 0);
        $quantite = intval($_POST['quantite']    ?? 0);

        if (!$id || !$nom || !$prix || !$quantite) {
            echo json_encode(['succes' => false, 'erreur' => 'Données invalides']);
            break;
        }

        $stmt = $db->prepare("
            UPDATE produit SET nom_produit = ?, prix = ?, `quantité` = ?
            WHERE ID = ?
        ");
        $stmt->execute([$nom, $prix, $quantite, $id]);

        echo json_encode(['succes' => true, 'message' => "\"$nom\" modifié avec succès !"]);
        break;

    // ─── SUPPRIMER UN PRODUIT ─────────────────────────────────────────────────
    case 'supprimer_produit':
        $id = intval($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['succes' => false, 'erreur' => 'ID invalide']);
            break;
        }

        $stmt = $db->prepare("SELECT image FROM produit WHERE ID = ?");
        $stmt->execute([$id]);
        $produit = $stmt->fetch();

        if ($produit && !empty($produit['image'])) {
            $cheminImage = dirname(__DIR__) . '/' . $produit['image'];
            if (file_exists($cheminImage)) {
                unlink($cheminImage);
            }
        }

        $stmt = $db->prepare("DELETE FROM produit WHERE ID = ?");
        $stmt->execute([$id]);

        echo json_encode(['succes' => true, 'message' => 'Produit supprimé avec succès !']);
        break;

    default:
        echo json_encode(['succes' => false, 'erreur' => 'Action inconnue']);
        break;
}
?>
