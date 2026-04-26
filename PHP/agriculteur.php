<?php
session_start();
require_once '../PHP/database_connection.php';
$err = $_SESSION['erreurs_produit'] ?? [];
unset($_SESSION['erreurs_produit']);
if (!isset($_SESSION['user_data']) || $_SESSION['type'] !== 'agriculteur') {
  header('Location: ../html/bienvenue.html?msg=auth');
  exit();
}
$id_agriculteur = $_SESSION['user_data']['id_agriculteur']
  ?? $_SESSION['user_data']['ID']
  ?? $_SESSION['user_data']['id'];
$stmt = $db->prepare("SELECT * FROM agriculteur WHERE ID = ?");
$stmt->execute([$id_agriculteur]);
$agriculteur = $stmt->fetch();

if (!$agriculteur) {
  $agriculteur = [
    'Nom' => 'Inconnu',
    'Prénom' => '',
    'Email' => '-',
    'Telephone' => '-',
    'Adresse' => '-',
    'Nom_ferme' => '-',
    'Type_de_production' => '-',
  ];
}

$stmt2 = $db->prepare("SELECT * FROM produit WHERE ID_agriculteur = ?");
$stmt2->execute([$id_agriculteur]);
$produits = $stmt2->fetchAll();

$total_produits = count($produits);
$total_stock = array_sum(array_column($produits, 'quantité'));
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BioBladi - Tableau de bord</title>
  <link rel="stylesheet" href="../css/agriculteur (1).css" />
  <link rel="icon" href="../images/screen-alt-2-svgrepo-com.svg" />
  <link rel="stylesheet" href="../css/code footer.css" />
  <link rel="stylesheet" href="../css/code css commun.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Poppins", sans-serif;
      background-color: #f3f5f8;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      width: 100%;
      overflow-x: hidden;
    }

    hr {
      border: none;
      border-top: 2px solid #cbd5c0;
      width: 100%;
      margin: 0;
    }

    .option-menu {
      cursor: pointer;
    }

    .option-menu:hover {
      background-color: #e2e8f0;
      border-radius: 8px;
    }

    .lien_nav {
      text-decoration: none;
      color: #2c3e2f;
      font-size: 16px;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .zone-commandes {
      max-height: 300px;
      overflow-y: auto;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      background: white;
    }

    .tableau-commandes thead {
      position: sticky;
      top: 0;
      background-color: #f8fafc;
      z-index: 10;
    }

    .zone-commandes::-webkit-scrollbar {
      width: 6px;
    }

    .zone-commandes::-webkit-scrollbar-thumb {
      background: #14532d;
      border-radius: 10px;
    }

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

    .upload-zone:hover {
      background: #e8f5e9;
      border-color: #14532d;
    }

    .upload-zone input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }

    .upload-icon {
      font-size: 2rem;
      display: block;
      margin-bottom: 8px;
    }

    .upload-text {
      color: #2E8B57;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .upload-subtext {
      color: #888;
      font-size: 0.8rem;
      margin-top: 4px;
    }

    #preview-container {
      display: none;
      margin-top: 12px;
      text-align: center;
    }

    #preview-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 10px;
      border: 2px solid #2E8B57;
    }

    #preview-name {
      font-size: 0.8rem;
      color: #555;
      margin-top: 5px;
    }

    .remove-photo {
      display: inline-block;
      margin-top: 6px;
      color: #d32f2f;
      font-size: 0.8rem;
      cursor: pointer;
      text-decoration: underline;
    }

    .product-img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .product-img-placeholder {
      width: 100%;
      height: 160px;
      background: #e8f5e9;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <div class="head">
    <div class="head-left">
      <div><img src="../images/logo.jpg" alt="logo" class="logo" /></div>
      <div class="page-title-container">
        <p class="page-title"><strong>Agriculteur</strong></p>
      </div>
      <nav class="navigation">
        <a href="../index.html" class="menu-item">Accueil</a>
        <a href="../PHP/fruits et légumes.php" class="menu-item">Marché</a>
        <a href="../PHP/epicerie bio.php" class="menu-item">Epicerie</a>
        <a href="../html/bienvenue.html" class="menu-item">Connexion</a>
        <a href="../PHP/inscription.php" class="menu-item">Inscription</a>
        <a href="../PHP/about us.php" class="menu-item">About US</a>
        <a href="../html/contact us.html" class="menu-item">Contact US</a>
        <a href="../html/questionnaire.html" class="menu-item">Questionnaire</a>
        <a href="../PHP/funpage.php" class="menu-item">Fun page</a>
      </nav>
    </div>
    <div class="head-right">
      <div class="head-actions">
        <a href="../PHP/check_compte.php">
          <img src="../images/person-svgrepo-com.svg" alt="person" class="user-icon" />
        </a>
        <a href="../PHP/panier.php">
          <img src="../images/cart-2-svgrepo-com.svg" alt="cart" class="cart-icon" />
        </a>
      </div>
    </div>
  </div>

  <div class="page-compte">
    <div class="menu-lateral">
      <div class="titre-section"><img src="../images/person-svgrepo-com.svg" class="icone-titre" /> Compte Agriculteur
      </div>
      <div class="liste-options">

        <div id="btn-infos" class="option-menu" style="padding: 10px"><img
            src="../images/information-point-svgrepo-com.svg" class="icone-menu" /> Mes informations</div>
        <div id="btn-dashboard" class="option-menu" style="padding: 10px"><img
            src="../images/screen-alt-2-svgrepo-com (1).svg" class="icone-menu" /> Statistiques</div>
        <div id="btn-Commande" class="option-menu" style="padding: 10px"><img
            src="../images/cart-large-2-svgrepo-com.svg" class="icone-menu" /> Mes produits</div>
        <div id="btn-ajout" class="option-menu" style="padding: 10px">
          <img src="../images/plus-large-svgrepo-com.svg" class="icone-menu" /> Ajouter un produit
        </div>
        <div class="option-menu" style="padding: 10px"><a href="logout.php" class="lien_nav">Déconnexion</a></div>
      </div>
    </div>

    <div class="page-agriculteur">

      <div class="info-section">
        <h2 class="section-title">
          <img src="../images/person-svgrepo-com (2).svg" alt="personne" class="icone1" />
          Mes informations personnelles
        </h2>
        <div class="info-grid">
          <div class="info-card">
            <span class="info-label">Nom complet</span>
            <span class="info-value">
              <?= htmlspecialchars($agriculteur['Nom'] . ' ' . $agriculteur['Prénom']) ?>
            </span>
          </div>
          <div class="info-card">
            <span class="info-label">Email</span>
            <span class="info-value">
              <?= htmlspecialchars($agriculteur['Email']) ?>
            </span>
          </div>
          <div class="info-card">
            <span class="info-label">Téléphone</span>
            <span class="info-value">
              <?= htmlspecialchars($agriculteur['Telephone']) ?>
            </span>
          </div>
          <div class="info-card">
            <span class="info-label">Adresse</span>
            <span class="info-value">
              <?= htmlspecialchars($agriculteur['Adresse']) ?>
            </span>
          </div>
          <div class="info-card">
            <span class="info-label">Nom de la ferme</span>
            <span class="info-value">
              <?= htmlspecialchars($agriculteur['Nom_ferme'] ?? '-') ?>
            </span>
          </div>
          <div class="info-card">
            <span class="info-label">Type de production</span>
            <span class="info-value">
              <?= htmlspecialchars($agriculteur['Type_de_production'] ?? '-') ?>
            </span>
          </div>
        </div>
      </div>

      <div class="stats-section">
        <h2 class="section-title">
          <img src="../images/increase-stats-svgrepo-com.svg" alt="stat" class="icone1" />
          Statistiques
        </h2>
        <div class="stats-grid">
          <div class="stat-card">
            <span class="stat-number" id="totalProducts">
              <?= $total_produits ?>
            </span>
            <span class="stat-label">Produits en vente</span>
          </div>
          <div class="stat-card">
            <span class="stat-number" id="totalStock">
              <?= $total_stock ?>
            </span>
            <span class="stat-label">Unités en stock</span>
          </div>
          <div class="stat-card">
            <span class="stat-number">0</span>
            <span class="stat-label">Commandes reçues</span>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2 class="section-title">
          <img src="../images/plus-large-svgrepo-com.svg" alt="plus" class="icone1" />
          Ajouter un nouveau produit
        </h2>

        <div id="form-message"
          style="display:none; padding:12px 16px; border-radius:10px; margin-bottom:15px; font-weight:500;"></div>

        <form action="../PHP/ajoutproduit.php" method="post" class="product-form" id="productForm"
          enctype="multipart/form-data">
          <input type="hidden" name="id_agriculteur" value="<?= $id_agriculteur ?>" />

          <div class="form-row">
            <div class="form-group">
              <label for="nom-produit">Nom du produit *</label>
              <input type="text" id="nom-produit" name="nom_produit" class="form-input" placeholder="Ex: Pommes Gala" />
              <span id="errNom" class="error">
                <?php echo $err['nom_produit'] ?? ''; ?>
              </span>

            </div>
            <div class="form-group">
              <label for="prix-produit">Prix (DT) *</label>
              <input type="number" id="prix-produit" name="prix" class="form-input" placeholder="0.00" step="0.1"
                min="1" />
              <span id="errPix" class="error">
                <?php echo $err['prix'] ?? ''; ?>
              </span>

            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="stock-produit">Stock *</label>
              <input type="number" id="stock-produit" name="quantite" class="form-input" placeholder="Quantité" />
              <span id="errStock" class="error">
                <?php echo $err['quantite'] ?? ''; ?>
              </span>

            </div>
            <div class="form-group">
              <label for="categorie-produit">Catégorie*</label>
              <select id="categorie-produit" name="categorie" class="form-input">
                <option value="Fruits">Fruits</option>
                <option value="Légumes">Légumes</option>
                <option value="Epicerie">Épicerie</option>
                <option value="Produits Laitiers">Produits laitiers</option>
                <option value="Oeufs">Œufs</option>
                <option value="Miel">Miel</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="saison-produit">Saison</label>
              <select id="saison-produit" name="saison" class="form-input">
                <option value="Printemps">Printemps</option>
                <option value="Été">Été</option>
                <option value="Automne">Automne</option>
                <option value="Hiver">Hiver</option>
                <option value="Toute l'année">Toute l'année</option>
              </select>
            </div>
            <div class="form-group">
              <label for="offre-produit">Offre spéciale (%)</label>
              <input type="number" id="offre-produit" name="offre" class="form-input" placeholder="0" min="0" />
              <span id="errOffre" class="error">
                <?php echo $err['offre'] ?? ''; ?>
              </span>

            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="unite-produit">Unité*</label>
              <input type="text" id="unite-produit" name="unite" class="form-input" placeholder="kg, litre, pièce..." />
              <span id="errUnite" class="error">
                <?php echo $err['unite'] ?? ''; ?>
              </span>

            </div>



            <div class="form-group">
              <label for="region-produit">Région du produit *</label>
              <select id="region-produit" name="region" class="form-input">
                <option value="">-- Sélectionnez un gouvernorat --</option>
                <option value="Tunis">Tunis</option>
                <option value="Ariana">Ariana</option>
                <option value="Ben Arous">Ben Arous</option>
                <option value="Manouba">Manouba</option>
                <option value="Nabeul">Nabeul</option>
                <option value="Zaghouan">Zaghouan</option>
                <option value="Bizerte">Bizerte</option>
                <option value="Béja">Béja</option>
                <option value="Jendouba">Jendouba</option>
                <option value="Le Kef">Le Kef</option>
                <option value="Siliana">Siliana</option>
                <option value="Kairouan">Kairouan</option>
                <option value="Kasserine">Kasserine</option>
                <option value="Sidi Bouzid">Sidi Bouzid</option>
                <option value="Sousse">Sousse</option>
                <option value="Monastir">Monastir</option>
                <option value="Mahdia">Mahdia</option>
                <option value="Sfax">Sfax</option>
                <option value="Gabès">Gabès</option>
                <option value="Médenine">Médenine</option>
                <option value="Tataouine">Tataouine</option>
                <option value="Gafsa">Gafsa</option>
                <option value="Tozeur">Tozeur</option>
                <option value="Kébili">Kébili</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Photo du produit *</label>
            <div class="upload-zone" id="uploadZone">
              <input type="file" id="photo-produit" name="photo" accept="../images/jpeg,image/png,image/webp" />
              <span class="upload-icon">📷</span>
              <span class="upload-text">Cliquez pour choisir une photo</span>
              <p class="upload-subtext">JPG, PNG, WEBP — max 5 Mo</p>
            </div>
            <div id="preview-container">
              <img id="preview-img" src="" alt="Aperçu" />
              <p id="preview-name"></p>
              <span class="remove-photo" onclick="supprimerPhoto()"> Supprimer la photo</span>
              <span id="errIMage" class="error">
                <?php echo $err['image'] ?? ''; ?>
              </span>

            </div>
          </div>

          <button type="submit" class="submit-btn">Mettre en vente</button>
        </form>
      </div>

      <div class="products-section">
        <h2 class="section-title">
          <img src="../images/package-svgrepo-com.svg" alt="boite" class="icone1" />
          Mes produits
        </h2>
        <div id="productsContainer" class="products-grid">
          <?php if (empty($produits)): ?>
            <div class="empty-state">
              <p>Aucun produit ajouté pour le moment.</p>
              <p>Utilisez le formulaire ci-dessus pour ajouter vos produits.</p>
            </div>
          <?php else: ?>
            <?php foreach ($produits as $produit): ?>
              <div class="product-card" data-id="<?= $produit['ID'] ?>">
                <?php if (!empty($produit['image'])): ?>
                  <img src="<?= htmlspecialchars('../' . $produit['image']) ?>"
                    alt="<?= htmlspecialchars($produit['nom_produit']) ?>" class="product-img" />
                <?php else: ?>
                  <div class="product-img-placeholder">🌿</div>
                <?php endif; ?>
                <div class="product-name">
                  <?= htmlspecialchars($produit['nom_produit']) ?>
                </div>
                <div class="product-price">
                  <?= number_format($produit['prix'], 2) ?> DT /
                  <?= htmlspecialchars($produit['unité'] ?? 'kg') ?>
                </div>
                <div class="product-stock <?= ($produit['quantité'] < 10) ? 'low' : '' ?>">
                  Stock : <?= intval($produit['quantité']) ?>
                </div>
                <div class="product-location">
                  <?= htmlspecialchars($produit['région'] ?? 'Tunisie') ?>
                </div>
                <div class="product-category">
                  <?= htmlspecialchars($produit['catégorie'] ?? '') ?>
                </div>
                <?php if (!empty($produit['offre']) && $produit['offre'] > 0): ?>
                  <div class="product-offer">Offre spéciale -
                    <?= intval($produit['offre']) ?>%
                  </div>
                <?php endif; ?>
                <div class="product-actions">
                  <a href="../PHP/modifierproduit.php?id=<?= $produit['ID'] ?>" class="edit-btn">
                    Modifier
                  </a>
                  <a href="../PHP/supprimerproduit.php?id=<?= $produit['ID'] ?>" class="delete-btn"
                    onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                    Supprimer
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-brand">
        <img src="../images/logo.jpg" class="logo" alt="Logo BioBladi" />
        <span style="color: white; font-weight: bold">BioBladi — Du champ à votre assiette, produits locaux et
          bio</span>
      </div>
      <div class="footer-links">
        <h4>Liens utiles</h4>
        <a href="../PHP/about us.php">About Us</a>
        <a href="../PHP/fruits et légumes.php">Marché</a>
      </div>
      <div class="footer-contact">
        <h4>Contactez-nous</h4>
        <p><img src="../images/phone-svgrepo-com (1).svg" alt="" class="footer-icon" /> +216 12 345 678</p>
        <p><img src="../images/mail-check-svgrepo-com.svg" alt="" class="footer-icon" /> contact@biobladi.tn</p>
        <p><img src="../images/location-svgrepo-com.svg" alt="" class="footer-icon" /> Tunis, Tunisie</p>
        <p><img src="../images/time-svgrepo-com.svg" alt="" class="footer-icon" /> Lun-Ven: 8h - 18h</p>
      </div>
      <div class="footer-social">
        <h4>Suivez-nous</h4>
        <a href="#"><img src="../images/facebook-svgrepo-com (1).svg" alt="Facebook" class="social-icon" /></a>
        <a href="#"><img src="../images/instagram-167-svgrepo-com.svg" alt="Instagram" class="social-icon" /></a>
        <a href="#"><img src="../images/linkedin-svgrepo-com (1).svg" alt="LinkedIn" class="social-icon" /></a>
      </div>
      <p class="footer-copy">© 2025 BioBladi — Tous droits réservés — Fièrement tunisien 🇹🇳</p>
    </div>
  </footer>

  <script>
    const ID_AGRICULTEUR = <?= $id_agriculteur ?>;

    document.getElementById('photo-produit').addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      if (file.size > 5 * 1024 * 1024) {
        afficherMessage('La photo ne doit pas dépasser 5 Mo', false);
        this.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-name').textContent = file.name;
        document.getElementById('preview-container').style.display = 'block';
        document.getElementById('uploadZone').style.borderColor = '#14532d';
        document.getElementById('uploadZone').style.background = '#e8f5e9';
      };
      reader.readAsDataURL(file);
    });

    function supprimerPhoto() {
      document.getElementById('photo-produit').value = '';
      document.getElementById('preview-container').style.display = 'none';
      document.getElementById('uploadZone').style.borderColor = '#2E8B57';
      document.getElementById('uploadZone').style.background = '#f8faf8';
    }

    function afficherMessage(texte, succes = true) {
      const el = document.getElementById('form-message');
      el.textContent = texte;
      el.style.display = 'block';
      el.style.background = succes ? '#d4edda' : '#f8d7da';
      el.style.color = succes ? '#155724' : '#721c24';
      setTimeout(() => { el.style.display = 'none'; }, 4000);
    }
    document.getElementById('btn-Commande').addEventListener('click', function () {
      document.querySelector('.products-section').scrollIntoView({ behavior: 'smooth' });
      document.getElementById('btn-dashboard').addEventListener('click', function () {
        document.querySelector('.stats-section').scrollIntoView({ behavior: 'smooth' });
      });

      document.getElementById('btn-infos').addEventListener('click', function () {
        document.querySelector('.info-section').scrollIntoView({ behavior: 'smooth' });
      });
    });
    document.getElementById('btn-ajout').addEventListener('click', function () {
      document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
    });
  </script>
</body>

</html>