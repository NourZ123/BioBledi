<?php
session_start();
require_once '../database_connection.php';

// ✅ Gestion de l'ajout au panier (comme fruits et légumes)
if (isset($_GET['action']) && $_GET['action'] === 'ajouter' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]++;
    } else {
        $_SESSION['panier'][$id] = 1;
    }

    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// ✅ Requête PDO (Miel, Oeufs, Epicerie)
$stmt = $db->prepare("SELECT * FROM `produit` WHERE `catégorie` IN ('Miel', 'Oeufs', 'Epicerie')");
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Epicerie bio</title>
    <link rel="stylesheet" href="epicerie bio.css" />
    <link rel="icon" href="image/honey.svg" />
    <link rel="stylesheet" href="../code footer.css" />

    <style>
  .container {
    display: grid !important;
    grid-template-columns: repeat(5, 1fr) !important;
    gap: 30px !important;
    padding: 30px 5% !important;
  }
  
  
</style>
  </head>
  <body>
    <div class="head">
      <div class="head-left">
        <div>
          <img src="image/logo.jpg" alt="logo" class="logo" />
        </div>
        <div class="page-title-container">
          <p class="page-title"><strong>Epicerie</strong></p>
        </div>
        <nav class="navigation">
          <a href="../index/index.html" class="menu-item">Accueil</a>
          <a href="../fruits et légumes/fruits et légumes.php" class="menu-item">Marché</a>
          <a href="../Epicerie bio/epicerie bio.php" class="menu-item">Epicerie</a>
          <a href="../se connecter/bienvenue.html" class="menu-item">Connexion</a>
          <a href="../inscription/inscription.html" class="menu-item">Inscription</a>
          <a href="../about us/about us.html" class="menu-item">About US</a>
          <a href="../contact us/contact us.html" class="menu-item">Contact US</a>
          <a href="../Questionnaire/questionnaire.html" class="menu-item">Questionnaire</a>
          <a href="../funpage/funpage.html" class="menu-item">Fun page</a>
        </nav>
      </div>
      <div class="head-right">
        <div class="head-actions">
          <a href="../compte Client/compte.php">
            <img src="image/person-svgrepo-com.svg" alt="person" class="user-icon" />
          </a>
          <a href="../mon panier/panier.php">
            <img src="image/cart-2-svgrepo-com.svg" alt="cart" class="cart-icon" />
          </a>
        </div>
      </div>
    </div>

    <hr />

    <div class="container">
      <?php if (empty($produits)): ?>
        <div style="grid-column: 1/-1; text-align:center; padding: 60px; color: #64748b;">
          <p style="font-size: 1.2rem;">Aucun produit disponible pour le moment.</p>
        </div>
      <?php else: ?>
        <?php foreach ($produits as $produit): ?>
          <div class="item">
            <!-- Image -->
            <img
              src="<?= htmlspecialchars('../' . $produit['image']) ?>"
              alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
              onerror="this.src='image/default.webp'"
            />

            <div class="Row1">
              <div>
                <p style="font-size: 20px; margin: 0%">
                  <strong><?= htmlspecialchars($produit['nom_produit']) ?></strong>
                </p>
              </div>
              <div>
                <!-- Prix : toujours le prix initial, même si une offre existe -->
                <div style="color: #14532d; font-size: 20px; margin: 0%">
                  <strong><?= number_format($produit['prix'], 2) ?> dt</strong>
                  <?php if (!empty($produit['offre']) && $produit['offre'] > 0): ?>
                    <span style="background:#ffd700; color:#14532d; border-radius:10px; padding:2px 7px; font-size:12px; margin-left:6px;">
                      -<?= intval($produit['offre']) ?>%
                    </span>
                  <?php endif; ?>
                </div>
                <div style="color: #777; font-size: 15px">\<?= htmlspecialchars($produit['unité'] ?? '') ?></div>
              </div>
            </div>

            <div class="Row2">
              <div class="loc">
                <img src="image/location-pin-svgrepo-com (1).svg" alt="loc" class="icon" />
                <div><p style="font-size: 16px"><?= htmlspecialchars($produit['région'] ?? 'N/A') ?></p></div>
              </div>
              <div id="Id">#<?= (int)$produit['ID'] ?></div>
            </div>

            <div class="Row3">
              <img src="image/box.svg" alt="stock" class="icon" />
              <p><?= (int)$produit['quantité'] ?></p>

              <a href="?action=ajouter&id=<?= $produit['ID'] ?>" class="btn" style="text-decoration: none;">
                <b>+Ajouter</b>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-brand">
          <img src="image/logo.jpg" class="logo" alt="BioBladi logo" />
          <span style="color: white; font-weight: bold">BioBladi — Du champ à votre assiette, produits locaux et bio</span>
        </div>
        <div class="footer-links">
          <h4>Liens utiles</h4>
          <a href="../about us/about us.html">About Us</a>
          <a href="../fruits et légumes/fruits et légumes.php">Marché</a>
        </div>
        <div class="footer-contact">
          <h4>Contactez-nous</h4>
          <p><img src="image/phone-svgrepo-com (1).svg" alt="Téléphone" class="footer-icon" /> +216 12 345 678</p>
          <p><img src="image/mail-check-svgrepo-com.svg" alt="Email" class="footer-icon" /> contact@biobladi.tn</p>
          <p><img src="image/location-svgrepo-com.svg" alt="Adresse" class="footer-icon" /> Tunis, Tunisie</p>
          <p><img src="image/time-svgrepo-com.svg" alt="Horaires" class="footer-icon" /> Lun-Ven: 8h - 18h</p>
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