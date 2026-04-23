<?php
session_start();
require_once '../database_connection.php';

// ✅ Gestion de l'ajout au panier + diminution du stock (API AJAX)
if (isset($_GET['ajax']) && $_GET['ajax'] === '1' && isset($_GET['action']) && $_GET['action'] === 'ajouter' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Vérifier le stock actuel
    $stmt = $db->prepare("SELECT quantité FROM produit WHERE ID = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($produit && $produit['quantité'] > 0) {
        // Diminuer le stock dans la base de données
        $stmt = $db->prepare("UPDATE produit SET quantité = quantité - 1 WHERE ID = ?");
        $stmt->execute([$id]);
        
        // Gérer le panier en session
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        
        if (isset($_SESSION['panier'][$id])) {
            $_SESSION['panier'][$id]++;
        } else {
            $_SESSION['panier'][$id] = 1;
        }
        
        echo json_encode(['success' => true, 'new_stock' => $produit['quantité'] - 1]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Stock épuisé']);
    }
    exit();
}

// Récupérer la catégorie sélectionnée (par défaut : toutes)
$categorie_filter = isset($_GET['categorie']) ? $_GET['categorie'] : '';

// Requête avec filtre
if (!empty($categorie_filter) && $categorie_filter != 'tous') {
    $stmt = $db->prepare("SELECT * FROM `produit` WHERE `catégorie` = ?");
    $stmt->execute([$categorie_filter]);
} else {
    $stmt = $db->prepare("SELECT * FROM `produit` WHERE `catégorie` IN ('Miel', 'Oeufs', 'Epicerie', 'Produits laitiers')");
    $stmt->execute();
}
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
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
        .btn:disabled {
            background: #ccc !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }
        a.btn {
            text-decoration: none !important;
        }
        
        /* Barre de recherche */
        .search-bar {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            padding: 0 5%;
        }
        .categorie-select {
            padding: 12px 24px;
            font-size: 16px;
            font-family: "Poppins", sans-serif;
            border: 2px solid #2E8B57;
            border-radius: 40px;
            background-color: white;
            color: #14532d;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            outline: none;
        }
        .categorie-select:hover {
            background-color: #e8f5e9;
            border-color: #14532d;
        }
        .categorie-select:focus {
            border-color: #14532d;
            box-shadow: 0 0 5px rgba(46, 139, 86, 0.3);
        }
    </style>
</head>
<body>
    <div class="head">
        <div class="head-left">
            <div><img src="image/logo.jpg" alt="logo" class="logo" /></div>
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
                <a href="../compte Client/compte.php"><img src="image/person-svgrepo-com.svg" alt="person" class="user-icon" /></a>
                <a href="../mon panier/panier.php"><img src="image/cart-2-svgrepo-com.svg" alt="cart" class="cart-icon" /></a>
            </div>
        </div>
    </div>

    <hr />

    <!-- Barre de recherche par catégorie -->
    <div class="search-bar">
        <select id="categorieSelect" class="categorie-select" onchange="window.location.href='?categorie=' + this.value">
            <option value="tous" <?= ($categorie_filter == '' || $categorie_filter == 'tous') ? 'selected' : '' ?>>Toutes les catégories</option>
            <option value="Epicerie" <?= ($categorie_filter == 'Epicerie') ? 'selected' : '' ?>>Épicerie</option>
            <option value="Oeufs" <?= ($categorie_filter == 'Oeufs') ? 'selected' : '' ?>>Œufs</option>
            <option value="Miel" <?= ($categorie_filter == 'Miel') ? 'selected' : '' ?>>Miel</option>
            <option value="Produits laitiers" <?= ($categorie_filter == 'Produits laitiers') ? 'selected' : '' ?>>Produits laitiers</option>
        </select>
    </div>

    <div class="container">
        <?php if (empty($produits)): ?>
            <div style="grid-column: 1/-1; text-align:center; padding: 60px; color: #64748b;">
                <p style="font-size: 1.2rem;">Aucun produit disponible dans cette catégorie.</p>
            </div>
        <?php else: ?>
            <?php foreach ($produits as $produit): ?>
                <div class="item" data-id="<?= $produit['ID'] ?>">
                    <img src="<?= htmlspecialchars('../' . $produit['image']) ?>"
                         alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
                         onerror="this.src='image/default.webp'" />

                    <div class="Row1">
                        <div>
                            <p style="font-size: 20px; margin: 0%">
                                <strong><?= htmlspecialchars($produit['nom_produit']) ?></strong>
                            </p>
                        </div>
                        <div>
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
                        <p class="stock-value" id="stock-<?= $produit['ID'] ?>"><?= (int)$produit['quantité'] ?></p>

                        <?php if ($produit['quantité'] > 0): ?>
                            <button class="btn add-to-cart" data-id="<?= $produit['ID'] ?>" style="text-decoration: none;">
                                <b>+Ajouter</b>
                            </button>
                        <?php else: ?>
                            <button class="btn" disabled style="background: #ccc; cursor: not-allowed;">
                                <b>Rupture</b>
                            </button>
                        <?php endif; ?>
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

    <script>
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const stockSpan = document.getElementById(`stock-${productId}`);
            
            fetch(`?ajax=1&action=ajouter&id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        stockSpan.textContent = data.new_stock;
                        if (data.new_stock === 0) {
                            const newButton = document.createElement('button');
                            newButton.className = 'btn';
                            newButton.disabled = true;
                            newButton.style.background = '#ccc';
                            newButton.style.cursor = 'not-allowed';
                            newButton.innerHTML = '<b>Rupture</b>';
                            this.replaceWith(newButton);
                        }
                    } else {
                        alert('Stock épuisé !');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        });
    });
    </script>
</body>
</html>