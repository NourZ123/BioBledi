<?php
session_start();
require_once '../PHP/database_connection.php';
require_once '../PHP/recherche_produits.php';

if (isset($_GET['ajax']) && $_GET['ajax'] === '1' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("SELECT quantité FROM produit WHERE ID = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produit) {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        $qte_panier = isset($_SESSION['panier'][$id]) ? $_SESSION['panier'][$id] : 0;
        $stock_reel = intval($produit['quantité']);

        if ($qte_panier < $stock_reel) {
            $_SESSION['panier'][$id] = $qte_panier + 1;
            echo ($stock_reel - $_SESSION['panier'][$id]);
        } else {
            echo "erreur_stock";
        }
    }
    exit();
}
$donnees = recupererProduitsEtFiltre($db, ['Fruits', 'Légumes']);

$produits = $donnees['items'];
$categorie_filter = $donnees['filtre_actif'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fruits & Légumes</title>
    <link rel="stylesheet" href="../css/fruits et légumes.css" />
    <link rel="icon" href="../images/carrot-svgrepo-com.svg" />
    <link rel="stylesheet" href="../css/code footer.css" />
    <link rel="stylesheet" href="../css/code css commun.css">
    <style>
        .container {
            display: grid ;
            grid-template-columns: repeat(5, 1fr) ;
            gap: 30px ;
            padding: 30px 5% ;
        }

        .btn:disabled {
            background: #ccc ;
            cursor: not-allowed ;
            opacity: 0.6;
        }

        a.btn {
            text-decoration: none ;
        }

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
    </style>
</head>

<body>
    <div class="head">
        <div class="head-left">
            <div><img src="../images/logo.jpg" alt="logo" class="logo" /></div>
            <div class="page-title-container">
                <p class="page-title"><strong>Marché</strong></p>
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
                <a href="../PHP/funpage.php"" class=" menu-item">Fun page</a>
            </nav>
        </div>
        <div class="head-right">
            <div class="head-actions">
                <a href="../PHP/check_compte.php"><img src="../images/person-svgrepo-com.svg" alt="person"
                        class="user-icon" /></a>
                <a href="../PHP/panier.php"><img src="../images/cart-2-svgrepo-com.svg" alt="cart"
                        class="cart-icon" /></a>
            </div>
        </div>
    </div>

    <hr />
    <div class="search-bar">
        <select id="categorieSelect" class="categorie-select"
            onchange="window.location.href='?categorie=' + this.value">
            <option value="tous" <?= ($categorie_filter == '' || $categorie_filter == 'tous') ? 'selected' : '' ?>>Toutes les
                catégories</option>
            <option value="Fruits" <?= ($categorie_filter == 'Fruits') ? 'selected' : '' ?>>Fruits</option>
            <option value="Légumes" <?= ($categorie_filter == 'Légumes') ? 'selected' : '' ?>>Légumes</option>
        </select>
    </div>

    <div class="container">
        <?php if (empty($produits)): ?>
            <div style="grid-column: 1/-1; text-align:center; padding: 60px; color: #64748b;">
                <p style="font-size: 1.2rem;">Aucun produit disponible.</p>
            </div>
        <?php else: ?>
            <?php foreach ($produits as $produit): ?>
                <div class="item" data-id="<?= $produit['ID'] ?>">
                    <img src="<?= htmlspecialchars('../' . $produit['image']) ?>" onerror="this.src='image/default.webp'" />
                    <div class="Row1">
                        <div>
                            <p style="font-size: 20px; margin: 0%"><strong>
                                    <?= htmlspecialchars($produit['nom_produit']) ?>
                                </strong></p>
                        </div>
                        <div>
                            <div style="color: #14532d; font-size: 20px; margin: 0%">
                                <?php if (!empty($produit['offre']) && $produit['offre'] > 0):
                                    $prixReduit = $produit['prix'] * (1 - $produit['offre'] / 100); ?>
                                    <strong style="display:block;">
                                     <?= number_format($prixReduit, 2) ?> dt
                                    </strong>
                                    <span style="display:block; text-decoration:line-through; color:#aaa; font-size:14px;">
                                     <?= number_format($produit['prix'], 2) ?> dt
                                    </span>
                                    
                                <?php else: ?>
                                    <strong>
                                        <?= number_format($produit['prix'], 2) ?> dt
                                    </strong>
                                <?php endif; ?>
                            </div>
                            <div style="color: #777; font-size: 15px">\
                                <?= htmlspecialchars($produit['unité'] ?? 'kg') ?>
                            </div>
                        </div>
                    </div>
                    <div class="Row2">
                        <div class="loc">
                            <img src="../images/location-pin-svgrepo-com (1).svg" alt="loc" class="icon" />
                            <div>
                                <p style="font-size: 16px">
                                    <?= htmlspecialchars($produit['région'] ?? 'Tunisie') ?>
                                </p>
                            </div>
                        </div>
                        <div id="Id">#
                            <?= $produit['ID'] ?>
                        </div>
                    </div>
                    <div class="Row3">
                        <img src="../images/box.svg" alt="stock" class="icon" />
                        <?php
                        $qte_sess = isset($_SESSION['panier'][$produit['ID']]) ? $_SESSION['panier'][$produit['ID']] : 0;
                        $stock_v = intval($produit['quantité']) - $qte_sess;
                        ?>
                        <p class="stock-value <?= ($stock_v < 10) ? 'low-stock' : '' ?>" id="stock-<?= $produit['ID'] ?>">
                        <?= $stock_v ?>
                        </p>                        
                        <?php if ($stock_v > 0): ?>
                            <button class="btn add-to-cart" data-id="<?= $produit['ID'] ?>"><b>+Ajouter</b></button>
                        <?php else: ?>
                            <button class="btn" disabled style="background: #ccc;"><b>Rupture</b></button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <img src="../images/logo.jpg" class="logo" alt="BioBladi logo" />
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
                <p><img src="../images/phone-svgrepo-com (1).svg" alt="Tél" class="footer-icon" /> +216 12 345 678</p>
                <p><img src="../images/mail-check-svgrepo-com.svg" alt="Mail" class="footer-icon" /> contact@biobladi.tn
                </p>
                <p><img src="../images/location-svgrepo-com.svg" alt="Loc" class="footer-icon" /> Tunis, Tunisie</p>
            </div>
            <div class="footer-social">
                <h4>Suivez-nous</h4>
                <a href="#"><img src="../images/facebook-svgrepo-com (1).svg" alt="FB" class="social-icon" /></a>
                <a href="#"><img src="../images/instagram-167-svgrepo-com.svg" alt="IG" class="social-icon" /></a>
            </div>
            <p class="footer-copy">© 2025 BioBladi — Tous droits réservés — Fièrement tunisien 🇹🇳</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.id;
                const stockSpan = document.getElementById(`stock-${productId}`);
                fetch(`?ajax=1&id=${productId}`)
                    .then(r => r.text())
                    .then(data => {
                        if (data === "erreur_stock") { alert('Stock épuisé !'); }
                        else {
                            stockSpan.textContent = data;
                            if (parseInt(data) <= 0) {
                                this.disabled = true; this.style.background = '#ccc'; this.innerHTML = '<b>Rupture</b>';
                            }
                        }
                    });
            });
        });
    </script>
</body>

</html>