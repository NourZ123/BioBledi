
<?php
session_start();
require "../PHP/database_connection.php";

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'plus') {
        $_SESSION['panier'][$id]++;
    } elseif ($action === 'moins') {
        if ($_SESSION['panier'][$id] > 1) {
            $_SESSION['panier'][$id]--;
        } else {
            unset($_SESSION['panier'][$id]);
        }
    } elseif ($action === 'supprimer') {
        unset($_SESSION['panier'][$id]);
    }

    header("Location: panier.php");
    exit();
}

$produits_panier = [];
$total_articles = 0;
$frais_livraison = 3;

if (!empty($_SESSION['panier'])) {
    $ids = array_keys($_SESSION['panier']);
    $stmt = $db->query("SELECT * FROM produit WHERE ID IN (" . implode(',', $ids) . ")");
    
    foreach ($stmt->fetchAll() as $item) {
        $id = $item['ID'];
        $qte = $_SESSION['panier'][$id];
        
        $prix_u = (!empty($item['offre']) && $item['offre'] > 0) 
                  ? $item['prix'] * (1 - $item['offre'] / 100) 
                  : $item['prix'];

        $sous_total = $prix_u * $qte;
        $total_articles += $sous_total;

        $produits_panier[] = [
            'id'         => $id,
            'nom'        => $item['nom_produit'],
            'image'      => $item['image'],
            'prix_u'     => $prix_u,
            'qte'        => $qte,
            'sous_total' => $sous_total
        ];
    }
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mon panier</title>
    <link rel="stylesheet" href="panier.css" />
    <link rel="icon" href="image/cart-2-svgrepo-com.svg" />
    <link rel="stylesheet" href="../code footer.css" />
    <link rel="stylesheet" href="../code css commun.css">
  </head>
  <body>
    <header>
      <div class="head">
        <div class="head-left">
          <div>
            <img src="image/logo.jpg" alt="logo" class="logo" />
          </div>
          <div class="page-title-container">
            <p class="page-title">
              <strong>Panier</strong>
            </p>
          </div>

          <nav class="navigation">
            <a href="../index/index.html" class="menu-item">Accueil</a>
            <a
              href="../fruits et légumes/fruits et légumes.php"
              class="menu-item"
              >Marché</a
            >
            <a href="../Epicerie bio/epicerie bio.php" class="menu-item"
              >Epicerie</a
            >
            <a href="../se connecter/bienvenue.html" class="menu-item"
              >Connexion</a
            >
            <a href="../inscription/inscription.html" class="menu-item"
              >Inscription</a
            >
            <a href="../about us/about us.php" class="menu-item">About US</a>
            <a href="../contact us/contact us.html" class="menu-item"
              >Contact US</a
            >
            <a href="../Questionnaire/questionnaire.html" class="menu-item"
              >Questionnaire</a
            >
            <a href="../funpage/funpage.php" class="menu-item">Fun page</a>
          </nav>
        </div>
        <div class="head-right">
          <div class="head-actions">
            <a href="../check_compte.php">
              <img
                src="image/person-svgrepo-com.svg"
                alt="person"
                class="user-icon"
              />
            </a>
            <a href="../mon panier/panier.php">
              <img
                src="image/cart-2-svgrepo-com.svg"
                alt="cart"
                class="cart-icon"
              />
            </a>
          </div>
        </div>
      </div>
    </header>
    <hr style="color: 14532d" />

    <div class="container">
      <div class="container">
        <div class="leftC">
          <div class="row1">
            <div style="margin-left: 20px">Produits</div>
            <div style="margin-left: 50px">Quantité</div>
            <div style="margin-right: 20px">Total</div>
          </div>

          <?php if (!empty($produits_panier)): ?>
            <?php foreach ($produits_panier as $item): ?>
              <div class="row2">
                <div class="produits">
                  <img src="../<?= htmlspecialchars($item['image']) ?>" class="img_panier" />
                  <div class="info">
                    <p><strong><?= htmlspecialchars($item['nom']) ?></strong></p>
                    <p class="price"><?= number_format($item['prix_u'], 2) ?> DT/unité</p>
                  </div>
                </div>
                <div class="quantité">
                  <a href="?action=moins&id=<?= $item['id'] ?>"><button>-</button></a>
                    <span class="Q"><?= $item['qte'] ?></span>
                     <?php 
                      $stmt_stock = $db->prepare("SELECT quantité FROM produit WHERE ID = ?");
                      $stmt_stock->execute([$item['id']]);
                      $stock_disponible = $stmt_stock->fetchColumn();
                  if ($item['qte'] < $stock_disponible): ?>
                  <a href="?action=plus&id=<?= $item['id'] ?>"><button>+</button></a>
                  <?php else: ?>
                    <button disabled style="background: #ccc; cursor: not-allowed; opacity: 0.6;" title="Stock maximum atteint">+</button>
                  <?php endif; ?>
                </div>
                <div class="Total">
                  <div class="price"><?= number_format($item['sous_total'], 2) ?> DT</div>
                  <div style="margin-right: 20px">
                    <a href="?action=supprimer&id=<?= $item['id'] ?>" style="text-decoration:none; color:inherit;">
                      <img src="image/bin-svgrepo-com.svg" alt="bin" />
                      <span>Retirer</span>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="produits" style="width: 100%; justify-content: center; padding: 50px;">
              <p>Votre panier est vide.</p>
            </div>
          <?php endif; ?>
          
          <div class="row4">
            <div>Total</div>
            <div class="sum"><?= number_format($total_articles, 2) ?> DT</div>
          </div>
        </div>
        <div class="rightC" style="height: 550px !important; width: 1000px !important">
        
          <div class="top">
            <p style="color: #14532d"><b>Récapitulatif</b></p>
            <p><b>Adresse de livraison</b></p>
            <?php if(isset($_SESSION['user_data'])) : ?>
            <p>
              <?php echo $_SESSION['user_data']['Adresse'] ?>
            </p>
            <?php else :?>
              <p> Votre adresse</p>
            <?php endif; ?>
            <hr />
          </div>
          <div class="middle">
    <div>Total des articles:</div>
    <div><?= number_format($total_articles, 2) ?> DT</div>

    <?php 
    $remise = 0;
    if (isset($_SESSION['promo'])) {
        $p = $_SESSION['promo'];
        if ($p == "10") $remise = $total_articles * 0.10;
        if ($p == "15") $remise = $total_articles * 0.15;
        if ($p == "5")  $remise = $total_articles * 0.05;
        if ($p == "FREE") $frais_livraison = 0;

        if ($remise > 0 || $p == "FREE"): ?>
            <div style="color: #28a745;">Cadeau :</div>
            <div style="color: #28a745;">
                <?= ($p == "FREE") ? "Gratuit" : "- " . number_format($remise, 2) . " DT" ?>
            </div>
        <?php endif; 
    } ?>

    <div style="color: #c6c1c1">Livraison:</div>
    <div style="color: #c6c1c1"><?= number_format($frais_livraison, 2) ?> DT</div>

    <div style="font-weight: bold;">Total à payer</div>
    <div style="font-weight: bold; color: #14532d;">
        <?php 
          $net = ($total_articles - $remise) + $frais_livraison;
          echo number_format($net, 2); 
          $_SESSION['totalàpayer'] = $net; 
        ?> DT
    </div>
</div>
          <div class="bottom">
          <a href="../finaliser ma commande/finaliser ma commande.php" 
       onclick="return verifierPanierVide(event)">
        <button class="valider">Valider ma commande</button>
    </a>
            <a href="../fruits et légumes/fruits et légumes.php">
              <button class="continuer">Continuer mes achats</button>
            </a>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-brand">
          <img src="image/logo.jpg" class="logo" alt="BioBladi logo" />
          <span style="color: white; font-weight: bold"
            >BioBladi — Du champ à votre assiette, produits locaux et bio
          </span>
        </div>
        <div class="footer-links">
          <h4>Liens utiles</h4>
          <a href="../about us/about us.php">About Us</a>
          <a href="../fruitsetlegumes//fruits et légumes.html">Marché</a>
        </div>
        <div class="footer-contact">
          <h4>Contactez-nous</h4>
          <p>
            <img
              src="image/phone-svgrepo-com (1).svg"
              alt="Téléphone"
              class="footer-icon"
            />
            +216 12 345 678
          </p>
          <p>
            <img
              src="image/mail-check-svgrepo-com.svg"
              alt="Email"
              class="footer-icon"
            />
            contact@biobladi.tn
          </p>
          <p>
            <img
              src="image/location-svgrepo-com.svg"
              alt="Adresse"
              class="footer-icon"
            />
            Tunis, Tunisie
          </p>
          <p>
            <img
              src="image/time-svgrepo-com.svg"
              alt="Horaires"
              class="footer-icon"
            />
            Lun-Ven: 8h - 18h
          </p>
        </div>
        <div class="footer-social">
          <h4>Suivez-nous</h4>
          <a href="#"
            ><img
              src="image/facebook-svgrepo-com (1).svg"
              alt="Facebook"
              class="social-icon"
          /></a>
          <a href="#"
            ><img
              src="image/instagram-167-svgrepo-com.svg"
              alt="Instagram"
              class="social-icon"
          /></a>
          <a href="#"
            ><img
              src="image/linkedin-svgrepo-com (1).svg"
              alt="LinkedIn"
              class="social-icon"
          /></a>
        </div>
        <p class="footer-copy">
          © 2025 BioBladi — Tous droits réservés — Fièrement tunisien 🇹🇳
        </p>
      </div>
    </footer>
    <script>function verifierPanierVide(event) {
    const produits = document.querySelectorAll('.row2');
    if (produits.length === 0) {
        alert("Votre panier est vide ! Ajoutez des produits avant de valider.");
        event.preventDefault(); 
        return false;
    }
    return true;
}</script>
  </body>
</html>
