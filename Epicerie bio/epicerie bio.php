<?php
session_start();
require_once '../database_connection.php';
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
  </head>
  <body>
    <div class="head">
      <div class="head-left">
        <div>
          <img src="image/logo.jpg" alt="logo" class="logo" />
        </div>
        <div class="page-title-container">
          <p class="page-title">
            <strong>Epicerie</strong>
          </p>
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
        <p style="text-align:center; color:#777; grid-column: 1/-1; padding: 50px 0;">
          Aucun produit disponible pour le moment.
        </p>
      <?php else: ?>
        <?php foreach ($produits as $produit): ?>
          <div class="item" style="position:relative;">

            <img
              src="<?= htmlspecialchars('../' . $produit['image']) ?>"
              alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
              onerror="this.src='image/default.webp'"
            />

            <?php if (!empty($produit['offre']) && $produit['offre'] > 0): ?>
              <div style="
                position:absolute;
                top:15px; right:15px;
                background:#e74c3c;
                color:white;
                padding:4px 10px;
                border-radius:20px;
                font-size:13px;
                font-weight:bold;
              ">
                -<?= (int)$produit['offre'] ?>%
              </div>
            <?php endif; ?>

            <div class="Row1">
              <div>
                <p style="font-size: 20px; margin: 0%">
                  <strong><?= htmlspecialchars($produit['nom_produit']) ?></strong>
                </p>
              </div>
              <div>
                <div style="color: #14532d; font-size: 20px; margin: 0%">
                  <strong>
                    <?php if (!empty($produit['offre']) && $produit['offre'] > 0):
                        $prix_reduit = $produit['prix'] - ($produit['prix'] * $produit['offre'] / 100);
                    ?>
                      <span style="text-decoration:line-through; color:#aaa; font-size:15px;">
                        <?= number_format($produit['prix'], 2) ?> dt
                      </span><br>
                      <?= number_format($prix_reduit, 2) ?> dt
                    <?php else: ?>
                      <?= number_format($produit['prix'], 2) ?> dt
                    <?php endif; ?>
                  </strong>
                </div>
                <div style="color: #777; font-size: 15px">
                  \<?= htmlspecialchars($produit['unité'] ?? '') ?>
                </div>
              </div>
            </div>

            <div class="Row2">
              <div class="loc">
                <img src="image/location-pin-svgrepo-com (1).svg" alt="loc" class="icon" />
                <div>
                  <p style="font-size: 16px;">
                    <?= htmlspecialchars($produit['région'] ?? 'N/A') ?>
                  </p>
                </div>
              </div>
              <div id="Id">#<?= (int)$produit['ID'] ?></div>
            </div>

            <div class="Row3">
              <img src="image/box.svg" alt="stock" class="icon" />
              <p><?= (int)$produit['quantité'] ?></p>
              <button class="btn">
                <b>+Ajouter</b>
              </button>
            </div>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-brand">
          <img src="image/logo.jpg" class="logo" alt="BioBladi logo" />
          <span style="color: white; font-weight: bold">
            BioBladi — Du champ à votre assiette, produits locaux et bio
          </span>
        </div>
        <div class="footer-links">
          <h4>Liens utiles</h4>
          <a href="../about us/about us.html">About Us</a>
          <a href="../fruits et légumes/fruits et légumes.php">Marché</a>
        </div>
        <div class="footer-contact">
          <h4>Contactez-nous</h4>
          <p>
            <img src="image/phone-svgrepo-com (1).svg" alt="Téléphone" class="footer-icon" />
            +216 12 345 678
          </p>
          <p>
            <img src="image/mail-check-svgrepo-com.svg" alt="Email" class="footer-icon" />
            contact@biobladi.tn
          </p>
          <p>
            <img src="image/location-svgrepo-com.svg" alt="Adresse" class="footer-icon" />
            Tunis, Tunisie
          </p>
          <p>
            <img src="image/time-svgrepo-com.svg" alt="Horaires" class="footer-icon" />
            Lun-Ven: 8h - 18h
          </p>
        </div>
        <div class="footer-social">
          <h4>Suivez-nous</h4>
          <a href="#">
            <img src="image/facebook-svgrepo-com (1).svg" alt="Facebook" class="social-icon" />
          </a>
          <a href="#">
            <img src="image/instagram-167-svgrepo-com.svg" alt="Instagram" class="social-icon" />
          </a>
          <a href="#">
            <img src="image/linkedin-svgrepo-com (1).svg" alt="LinkedIn" class="social-icon" />
          </a>
        </div>
        <p class="footer-copy">
          © 2025 BioBladi — Tous droits réservés — Fièrement tunisien 🇹🇳
        </p>
      </div>
    </footer>

  </body>
</html>
