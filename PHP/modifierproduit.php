<?php
session_start();
require_once '../PHP/database_connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produit'])) {
    $id = intval($_POST['id_produit']);
    
    $stmt = $db->prepare("SELECT * FROM produit WHERE ID = ?");
    $stmt->execute([$id]);
    $actuel = $stmt->fetch();

    if ($actuel) {
        $nom       = !empty($_POST['nom_produit']) ? $_POST['nom_produit'] : $actuel['nom_produit'];
        $prix      = !empty($_POST['prix'])        ? $_POST['prix']        : $actuel['prix'];
        $quantite  = !empty($_POST['quantite'])    ? $_POST['quantite']    : $actuel['quantité'];
        $offre     = ($_POST['offre'] !== '')      ? $_POST['offre']       : $actuel['offre'];
        
        $cheminImage = $actuel['image'];
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../images/produits/';
            $nomFichier = time() . '_' . $_FILES['photo']['name'];
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $nomFichier)) {
                $cheminImage = 'images/produits/' . $nomFichier;
            }
        }

        $sql = "UPDATE produit SET nom_produit = ?, prix = ?, quantité = ?, offre = ?, image = ? WHERE ID = ?";
        $db->prepare($sql)->execute([$nom, $prix, $quantite, $offre, $cheminImage, $id]);
        header("Location: modifierproduit.php?id=$id&success=1");
        exit();
    }
}

if (!isset($_GET['id'])) { exit("ID manquant"); }
$id_aff = intval($_GET['id']);
$stmt = $db->prepare("SELECT * FROM produit WHERE ID = ?");
$stmt->execute([$id_aff]);
$produit = $stmt->fetch();

if (!$produit) { exit("Produit introuvable"); }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="agriculteur (1).css">
    <title>Modifier <?= htmlspecialchars($produit['nom_produit']) ?></title>
    <link rel="stylesheet" href="../agriculteur/agriculteur (1).css">
    <link rel="stylesheet" href="../code footer.css">
    <link rel="stylesheet" href="../code css commun.css">
    <style>
        .form-section { max-width: 600px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .current-val { font-size: 0.85rem; color: #28a745; margin-bottom: 5px; display: block; }
        .success-banner { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb; }
        .upload-zone {
        border: 2px dashed #2E8B57;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8faf8;
        position: relative;
      }
      .upload-zone:hover { background: #e8f5e9; border-color: #14532d; }
      .upload-zone input[type="file"] {
        position: absolute; inset: 0;
        opacity: 0; cursor: pointer; width: 100%; height: 100%;
      }
      .upload-icon { font-size: 2rem; display: block; margin-bottom: 8px; }
      .upload-text { color: #2E8B57; font-weight: 500; font-size: 0.9rem; }
      .upload-subtext { color: #888; font-size: 0.8rem; margin-top: 4px; }
    </style>
</head>
<body>
<div class="head">
      <div class="head-left">
        <div><img src="image/logo.jpg" alt="logo" class="logo" /></div>
        <div class="page-title-container">
          <p class="page-title"><strong>Mise à Jour</strong></p>
        </div>
        <nav class="navigation">
          <a href="../index/index.html" class="menu-item">Accueil</a>
          <a href="../fruits et légumes/fruits et légumes.php" class="menu-item">Marché</a>
          <a href="../Epicerie bio/epicerie bio.php" class="menu-item">Epicerie</a>
          <a href="../se connecter/bienvenue.html" class="menu-item">Connexion</a>
          <a href="../inscription/inscription.php" class="menu-item">Inscription</a>
          <a href="../about us/about us.php" class="menu-item">About US</a>
          <a href="../contact us/contact us.html" class="menu-item">Contact US</a>
          <a href="../Questionnaire/questionnaire.html" class="menu-item">Questionnaire</a>
          <a href="../funpage/funpage.php" class="menu-item">Fun page</a>
        </nav>
      </div>
      <div class="head-right">
        <div class="head-actions">
          <a href="../check_compte.php">
            <img src="image/person-svgrepo-com.svg" alt="person" class="user-icon" />
          </a>
          <a href="../mon panier/panier.php">
            <img src="image/cart-2-svgrepo-com.svg" alt="cart" class="cart-icon" />
          </a>
        </div>
      </div>
    </div>

    <hr />
<div class="form-section">
    <h2 class="section-title">
        Modifier le produit
    </h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-banner">Modification enregistrée avec succès !</div>
    <?php endif; ?>

    <form action="modifierproduit.php?id=<?= $produit['ID'] ?>" method="post" class="product-form" enctype="multipart/form-data">
        <input type="hidden" name="id_produit" value="<?= $produit['ID'] ?>" />

        <div class="form-row">
            <div class="form-group">
                <label>Nom du produit</label>
                <span class="current-val">Actuel : <?= htmlspecialchars($produit['nom_produit']) ?></span>
                <input type="text" name="nom_produit" class="form-input" placeholder="nouveau nom" />
            </div>
            <div class="form-group">
                <label>Prix (DT)</label>
                <span class="current-val">Actuel : <?= $produit['prix'] ?> DT</span>
                <input type="number" name="prix" class="form-input" step="0.1" placeholder="Nouveau prix..." />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Stock disponible</label>
                <span class="current-val">Actuel : <?= $produit['quantité'] ?></span>
                <input type="number" name="quantite" class="form-input" placeholder="Nouvelle quantité..." />
            </div>
            <div class="form-group">
                <label>Remise (%)</label>
                <span class="current-val">Actuelle : <?= $produit['offre'] ?? 0 ?> %</span>
                <input type="number" name="offre" class="form-input" placeholder="Ex: 10" />
            </div>
        </div>

        <div class="form-group">
            <label>Image du produit</label>
            <div style="margin-bottom: 10px;">
                <img src="../<?= $produit['image'] ?>" alt="actuelle" style="width: 80px; border-radius: 8px; border: 1px solid #ddd;">
            </div>
            <div class="upload-zone" id="uploadZone">
              <input type="file" id="photo-produit" name="photo" accept="image/jpeg,image/png,image/webp" />
              <span class="upload-icon">📷</span>
              <span class="upload-text">Cliquez pour choisir une photo</span>
              <p class="upload-subtext">JPG, PNG, WEBP — max 5 Mo</p>
            </div>
        </div>

        <div style="margin-top: 25px; display: flex; align-items: center; gap: 20px;">
           
            <a href="../agriculteur/agriculteur.php" style="flex: 1; text-decoration: none; background-color: #f8faf8; text-align:center ; color:#14532d"   class="submit-btn">Annuler</a>
            <button type="submit" class="submit-btn" style="flex: 2;"> Mettre à Jour</button>

        </div>
    </form>
</div>
<footer class="footer">
      <div class="footer-container">
        <div class="footer-brand">
          <img src="image/logo.jpg" class="logo" alt="Logo BioBladi" />
          <span style="color: white; font-weight: bold">BioBladi — Du champ à votre assiette, produits locaux et bio</span>
        </div>
        <div class="footer-links">
          <h4>Liens utiles</h4>
          <a href="../about us/about us.php">About Us</a>
          <a href="../fruits et légumes/fruits et légumes.php">Marché</a>
        </div>
        <div class="footer-contact">
          <h4>Contactez-nous</h4>
          <p><img src="image/phone-svgrepo-com (1).svg" alt="" class="footer-icon" /> +216 12 345 678</p>
          <p><img src="image/mail-check-svgrepo-com.svg" alt="" class="footer-icon" /> contact@biobladi.tn</p>
          <p><img src="image/location-svgrepo-com.svg" alt="" class="footer-icon" /> Tunis, Tunisie</p>
          <p><img src="image/time-svgrepo-com.svg" alt="" class="footer-icon" /> Lun-Ven: 8h - 18h</p>
        </div>
        <div class="footer-social">
          <h4>Suivez-nous</h4>
          <a href="#"><img src="image/facebook-svgrepo-com (1).svg" alt="Facebook" class="social-icon" /></a>
          <a href="#"><img src="image/instagram-167-svgrepo-com.svg" alt="Instagram" class="social-icon" /></a>
          <a href="#"><img src="image/linkedin-svgrepo-com (1).svg" alt="LinkedIn" class="social-icon" /></a>
        </div>
        <p class="footer-copy">© 2025 BioBladi — Tous droits réservés — Fièrement tunisien 🇹🇳</p>
      </div>
    </footer>
    
</body>
</html>