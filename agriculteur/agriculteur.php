<?php
session_start();
require_once '../PHP/database_connection.php';
$id_agriculteur = 1; 
$stmt = $db->prepare("SELECT * FROM agriculteur WHERE ID = ?");
$stmt->execute([$id_agriculteur]);
$agriculteur = $stmt->fetch();

if (!$agriculteur) {
    $agriculteur = [
        'Nom' => 'Inconnu', 'Prénom' => '', 'Email' => '-',
        'Telephone' => '-', 'Adresse' => '-',
        'Nom_ferme' => '-', 'Type_de_production' => '-',
    ];
}

$stmt2 = $db->prepare("SELECT * FROM produit WHERE ID_agriculteur = ?");
$stmt2->execute([$id_agriculteur]);
$produits = $stmt2->fetchAll();

$total_produits = count($produits);
$total_stock    = array_sum(array_column($produits, 'quantité'));
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BioBladi - Espace Agriculteur</title>
    <link rel="stylesheet" href="agriculteur (1).css" />
    <link rel="stylesheet" href="../code footer.css" />
    <style>
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

      #preview-container { display: none; margin-top: 12px; text-align: center; }
      #preview-img {
        width: 120px; height: 120px;
        object-fit: cover; border-radius: 10px;
        border: 2px solid #2E8B57;
      }
      #preview-name { font-size: 0.8rem; color: #555; margin-top: 5px; }
      .remove-photo {
        display: inline-block; margin-top: 6px;
        color: #d32f2f; font-size: 0.8rem;
        cursor: pointer; text-decoration: underline;
      }

      .product-img {
        width: 100%; height: 160px;
        object-fit: cover; border-radius: 10px; margin-bottom: 10px;
      }
      .product-img-placeholder {
        width: 100%; height: 160px; background: #e8f5e9;
        border-radius: 10px; display: flex;
        align-items: center; justify-content: center;
        font-size: 2.5rem; margin-bottom: 10px;
      }
    </style>
  </head>
  <body>
    <div class="head">
      <div class="head-left">
        <div><img src="image/logo.jpg" alt="logo" class="logo" /></div>
        <div class="page-title-container">
          <p class="page-title"><strong>Agriculteur</strong></p>
        </div>
        <nav class="navigation">
          <a href="../index/index.html" class="menu-item">Accueil</a>
          <a href="../fruits et légumes/fruits et légumes.php" class="menu-item">Marché</a>
          <a href="../Epicerie bio/epicerie bio.php" class="menu-item">Epicerie</a>
          <a href="../se connecter/bienvenue.html" class="menu-item">Connexion</a>
          <a href="../inscription/inscription.html" class="menu-item">Inscription</a>
          <a href="../about us/about us.php" class="menu-item">About US</a>
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

    <main class="page-agriculteur">

      <div class="info-section">
        <h2 class="section-title">
          <img src="image/person-svgrepo-com (2).svg" alt="personne" class="icone1" />
          Mes informations personnelles
        </h2>
        <div class="info-grid">
          <div class="info-card">
            <span class="info-label">Nom complet</span>
            <span class="info-value"><?= htmlspecialchars($agriculteur['Nom'] . ' ' . $agriculteur['Prénom']) ?></span>
          </div>
          <div class="info-card">
            <span class="info-label">Email</span>
            <span class="info-value"><?= htmlspecialchars($agriculteur['Email']) ?></span>
          </div>
          <div class="info-card">
            <span class="info-label">Téléphone</span>
            <span class="info-value"><?= htmlspecialchars($agriculteur['Telephone']) ?></span>
          </div>
          <div class="info-card">
            <span class="info-label">Adresse</span>
            <span class="info-value"><?= htmlspecialchars($agriculteur['Adresse']) ?></span>
          </div>
          <div class="info-card">
            <span class="info-label">Nom de la ferme</span>
            <span class="info-value"><?= htmlspecialchars($agriculteur['Nom_ferme'] ?? '-') ?></span>
          </div>
          <div class="info-card">
            <span class="info-label">Type de production</span>
            <span class="info-value"><?= htmlspecialchars($agriculteur['Type_de_production'] ?? '-') ?></span>
          </div>
        </div>
      </div>

      <div class="stats-section">
        <h2 class="section-title">
          <img src="image/increase-stats-svgrepo-com.svg" alt="stat" class="icone1" />
          Statistiques
        </h2>
        <div class="stats-grid">
          <div class="stat-card">
            <span class="stat-number" id="totalProducts"><?= $total_produits ?></span>
            <span class="stat-label">Produits en vente</span>
          </div>
          <div class="stat-card">
            <span class="stat-number" id="totalStock"><?= $total_stock ?></span>
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
          <img src="image/plus-large-svgrepo-com.svg" alt="plus" class="icone1" />
          Ajouter un nouveau produit
        </h2>

        <div id="form-message" style="display:none; padding:12px 16px; border-radius:10px; margin-bottom:15px; font-weight:500;"></div>

        <form action="../PHP/ajoutproduit.php" method="post" class="product-form" id="productForm" enctype="multipart/form-data">
          <input type="hidden" name="id_agriculteur" value="<?= $id_agriculteur ?>" />

          <div class="form-row">
            <div class="form-group">
              <label for="nom-produit">Nom du produit *</label>
              <input type="text" id="nom-produit" name="nom_produit" class="form-input" placeholder="Ex: Pommes Gala" required />
            </div>
            <div class="form-group">
              <label for="prix-produit">Prix (DT) *</label>
              <input type="number" id="prix-produit" name="prix" class="form-input" placeholder="0.00" step="0.1" min="1" required />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="stock-produit">Stock *</label>
              <input type="number" id="stock-produit" name="quantite" class="form-input" placeholder="Quantité" required />
            </div>
            <div class="form-group">
              <label for="categorie-produit">Catégorie*</label>
              <select id="categorie-produit" name="categorie" class="form-input" required>
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
              <input type="number" id="offre-produit" name="offre" class="form-input" placeholder="0" min="0" max="50" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="unite-produit">Unité*</label>
              <input type="text" id="unite-produit" name="unite" class="form-input" placeholder="kg, litre, pièce..." required />
            </div>
          
            
            
            <div class="form-group">
              <label for="region-produit">Région du produit *</label>
              <select id="region-produit" name="region" class="form-input" required>
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
              <input type="file" id="photo-produit" name="photo" accept="image/jpeg,image/png,image/webp" />
              <span class="upload-icon">📷</span>
              <span class="upload-text">Cliquez pour choisir une photo</span>
              <p class="upload-subtext">JPG, PNG, WEBP — max 5 Mo</p>
            </div>
            <div id="preview-container">
              <img id="preview-img" src="" alt="Aperçu" />
              <p id="preview-name"></p>
              <span class="remove-photo" onclick="supprimerPhoto()">✕ Supprimer la photo</span>
            </div>
          </div>

          <button type="submit" class="submit-btn">Mettre en vente</button>
        </form>
      </div>

      <div class="products-section">
        <h2 class="section-title">
          <img src="image/package-svgrepo-com.svg" alt="boite" class="icone1" />
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
                       alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
                       class="product-img" />
                <?php else: ?>
                  <div class="product-img-placeholder">🌿</div>
                <?php endif; ?>
                <div class="product-name"><?= htmlspecialchars($produit['nom_produit']) ?></div>
                <div class="product-price"><?= number_format($produit['prix'], 2) ?> DT / <?= htmlspecialchars($produit['unité'] ?? 'kg') ?></div>
                <div class="product-stock <?= ($produit['quantité'] < 10) ? 'low' : '' ?>">
                  Stock : <?= intval($produit['quantité']) ?>
                </div>
                <div class="product-location"><?= htmlspecialchars($produit['région'] ?? 'Tunisie') ?></div>
                <div class="product-category"><?= htmlspecialchars($produit['catégorie'] ?? '') ?></div>
                <?php if (!empty($produit['offre']) && $produit['offre'] > 0): ?>
                  <div class="product-offer">Offre spéciale -<?= intval($produit['offre']) ?>%</div>
                <?php endif; ?>
                <div class="product-actions">
                <a href="../PHP/modifierproduit.php?id=<?= $produit['ID'] ?>" class="edit-btn">
    Modifier
</a>
                  <a href="../PHP/supprimerproduit.php?id=<?= $produit['ID'] ?>" 
   class="delete-btn" 
   onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
   Supprimer
</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </main>

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

    <script>
      const ID_AGRICULTEUR = <?= $id_agriculteur ?>;

      document.getElementById('photo-produit').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
          afficherMessage('La photo ne doit pas dépasser 5 Mo', false);
          this.value = '';
          return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('preview-img').src = e.target.result;
          document.getElementById('preview-name').textContent = file.name;
          document.getElementById('preview-container').style.display = 'block';
          document.getElementById('uploadZone').style.borderColor = '#14532d';
          document.getElementById('uploadZone').style.background  = '#e8f5e9';
        };
        reader.readAsDataURL(file);
      });

      function supprimerPhoto() {
        document.getElementById('photo-produit').value = '';
        document.getElementById('preview-container').style.display = 'none';
        document.getElementById('uploadZone').style.borderColor = '#2E8B57';
        document.getElementById('uploadZone').style.background  = '#f8faf8';
      }

      function afficherMessage(texte, succes = true) {
        const el = document.getElementById('form-message');
        el.textContent    = texte;
        el.style.display  = 'block';
        el.style.background = succes ? '#d4edda' : '#f8d7da';
        el.style.color      = succes ? '#155724' : '#721c24';
        setTimeout(() => { el.style.display = 'none'; }, 4000);
      }

      document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const photoInput = document.getElementById('photo-produit');
        if (!photoInput.files || photoInput.files.length === 0) {
          afficherMessage('Veuillez choisir une photo pour le produit', false);
          return;
        }
        const formData = new FormData(this);
        formData.append('action', 'ajouter_produit');

        fetch('agriculteur_api.php', { method: 'POST', body: formData })
          .then(r => r.json())
          .then(data => {
            if (data.succes) {
              afficherMessage(data.message, true);
              document.getElementById('productForm').reset();
              supprimerPhoto();
              rechargerProduits();
            } else {
              afficherMessage(data.erreur || 'Erreur inconnue', false);
            }
          })
          .catch(() => afficherMessage('Erreur réseau', false));
      });

      function rechargerProduits() {
        fetch(`agriculteur_api.php?action=get_produits&id_agriculteur=${ID_AGRICULTEUR}`)
          .then(r => r.json())
          .then(data => {
            if (!data.succes) return;
            const container = document.getElementById('productsContainer');
            const produits  = data.produits;

            if (produits.length === 0) {
              container.innerHTML = `<div class="empty-state"><p>Aucun produit ajouté.</p></div>`;
              document.getElementById('totalProducts').textContent = 0;
              document.getElementById('totalStock').textContent    = 0;
              return;
            }

            container.innerHTML = produits.map(p => `
              <div class="product-card" data-id="${p.ID}">
                ${p.image
                  ? `<img src="../${p.image}" alt="${p.nom_produit}" class="product-img" />`
                  : `<div class="product-img-placeholder">🌿</div>`
                }
                <div class="product-name">${p.nom_produit}</div>
                <div class="product-price">${parseFloat(p.prix).toFixed(2)} DT / ${p['unité'] || 'kg'}</div>
                <div class="product-stock ${p['quantité'] < 10 ? 'low' : ''}">Stock : ${p['quantité']}</div>
                <div class="product-location">${p['région'] || 'Tunisie'}</div>
                <div class="product-category">${p['catégorie'] || ''}</div>
                ${p.offre > 0 ? `<div class="product-offer">Offre spéciale -${p.offre}%</div>` : ''}
                <div class="product-actions">
                  <button class="edit-btn" onclick="modifierProduit(${p.ID})">Modifier</button>
                  <button class="delete-btn" onclick="supprimerProduit(${p.ID})">Supprimer</button>
                </div>
              </div>
            `).join('');

            document.getElementById('totalProducts').textContent = produits.length;
            document.getElementById('totalStock').textContent =
              produits.reduce((s, p) => s + parseInt(p['quantité'] || 0), 0);
          });
      }

      function modifierProduit(id) {
        const nom   = prompt('Nouveau nom :');   if (nom   === null) return;
        const prix  = prompt('Nouveau prix :');  if (prix  === null) return;
        const stock = prompt('Nouveau stock :'); if (stock === null) return;

        const fd = new FormData();
        fd.append('action', 'modifier_produit');
        fd.append('id', id);
        fd.append('nom_produit', nom);
        fd.append('prix', prix);
        fd.append('quantite', stock);

        fetch('agriculteur_api.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(data => {
            afficherMessage(data.message || data.erreur, data.succes);
            if (data.succes) rechargerProduits();
          });
      }

      function supprimerProduit(id) {
        if (!confirm('Voulez-vous vraiment supprimer ce produit ?')) return;

        const fd = new FormData();
        fd.append('action', 'supprimer_produit');
        fd.append('id', id);

        fetch('agriculteur_api.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(data => {
            afficherMessage(data.message || data.erreur, data.succes);
            if (data.succes) rechargerProduits();
          });
      }
    </script>
  </body>
</html>