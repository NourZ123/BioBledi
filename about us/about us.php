<?php
//  Connexion à la base de données
require_once '../PHP/database_connection.php';

// pour paramètres de recherche
$search_val = isset($_GET['recherche-valeur']) ? trim($_GET['recherche-valeur']) : '';
$critere = isset($_GET['recherche-critere']) ? $_GET['recherche-critere'] : 'nom_produit';

if (!empty($search_val)) {
    // Cas Recherche : Insensible à la casse, affiche tout (même offre = 0)
    $sql = "SELECT * FROM produit WHERE LOWER($critere) LIKE LOWER(:valeur) ORDER BY région ASC";
    $params = [':valeur' => '%' . $search_val . '%'];
} else {
    // Par défaut : on affiche uniquement les produits avec offre > 0
    $sql = "SELECT * FROM produit WHERE offre > 0 ORDER BY région ASC";
    $params = [];
}

try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $produits_bruts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // pour qu'on accepte "nabeul" et "Nabeul" comme une même région, on convertit la région de sorte à ce qu'elle soit toujours en majuscule pour la première lettre et le reste en minuscule
    $produits = [];
    foreach ($produits_bruts as $p) {
        $p['région'] = mb_convert_case($p['région'], MB_CASE_TITLE, "UTF-8");
        $produits[] = $p;
    }
    // Calcul des rowspans pour les régions
    $regions_counts = array_count_values(array_column($produits, 'région'));
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BioBladi - À propos</title>
    <link rel="stylesheet" href="about us.css" />
    <link rel="icon" href="image/information-button-svgrepo-com (1).svg" />
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
            <strong>About us</strong>
          </p>
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

    <section class="section-presentation">
      <div class="carte-presentation">
        <div class="entete-carte">
          <img src="image/leaves-svgrepo-com.svg" alt="leaves" class="icone" />
          <div><h2>Qui sommes-nous ?</h2></div>
        </div>

        <p>
          BioBladi est une plateforme tunisienne qui relie directement les
          agriculteurs locaux aux consommateurs. Notre mission est simple :
          offrir des produits frais, authentiques et de saison, tout en
          soutenant l’agriculture locale.
        </p>

        <p>
          Nous croyons en une alimentation saine, transparente et responsable.
          Chaque produit que vous trouvez chez nous provient directement du
          producteur — sans intermédiaire.
        </p>

        <div class="entete-carte">
          <p class="phrase">Du champ à votre assiette, naturellement</p>
          <img src="image/plant-svgrepo-com.svg" alt="plant" class="icone" />
        </div>
        <div class="liste">
          <div class="entete-carte">
            <img src="image/leaves-svgrepo-com.svg" alt="leaves" class="icone" />
            <div><h3>Pourquoi choisir BioBladi ?</h3></div>
          </div>
          <ul>
            <li>Produits 100% tunisiens</li>
            <li>Direct du producteur</li>
            <li>Qualité garantie</li>
            <li>Respect de l’environnement</li>
          </ul>
        </div>
      </div>
      <div class="email">
        <a id="email" href="mailto:contact@biobladi.tn">
          Cliquez ici pour nous contacter
        </a>
      </div>
    </section>

    <section class="section-gestion" id="resultats">
      <div class="conteneur-gestion">
        <div class="formulaire-recherche" style="max-width: 550px; margin: 0 auto;">
          <h3>
            <img src="image/search-svgrepo-com.svg" alt="recherche" class="icone" />
            Rechercher un produit
          </h3>
          <form method="GET" action="about us.php#resultats">
            <div class="form-row">
              <div class="form-group">
                <label for="recherche-critere">Critère :</label>
                <select name="recherche-critere" id="recherche-critere" class="form-select">
                // pour que le critère sélectionné reste sélectionné après la soumission du formulaire, on vérifie si $critere correspond à la valeur de chaque option et si oui, on ajoute l'attribut "selected" à cette option
                  <option value="nom_produit" <?php if($critere == 'nom_produit') echo 'selected'; ?>>Par nom</option>
                  <option value="région" <?php if($critere == 'région') echo 'selected'; ?>>Par région</option>
                </select>
              </div>
              <div class="form-group">
                <label for="recherche-valeur">Valeur :</label>
                <input
                  type="text"
                  name="recherche-valeur"
                  id="recherche-valeur"
                  class="form-input"
                  placeholder="Ex: Sfax"
                  value="<?php echo htmlspecialchars($search_val); ?>"
                />
              </div>
            </div>
            <div class="btn-group-recherche">
              <button type="submit" class="btn-rechercher">
                <img src="image/search-svgrepo-com.svg" alt="recherche" class="icone" />
                Rechercher
              </button>
              <a href="about us.php#resultats" class="btn-tous" style="text-decoration:none;line-height: 1.1; display:flex; align-items:center; justify-content:center; background-color: #fff; border: 2px solid #2e7d32; color: #2e7d32; font-weight: bold; flex: 1; border-radius: 40px; height: 45px;font-size: 0.85rem;">
                📋 Nos promos
              </a>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section class="section-produits">
      <div class="conteneur-tableau">
        <table class="tableau-produits" cellspacing="10" cellpadding="12" id="tableau-produits">
          <thead>
            <tr class="titre-tableau">
              <td colspan="5" class="titre-cellule">
                <img src="image/logo.jpg" class="logo" alt="logo" />
                // Affiche "Nos meilleures offres du moment" si la barre de recherche est vide, sinon affiche "Résultats de votre recherche"
                <?php echo empty($search_val) ? "Nos meilleures offres du moment" : "Résultats de votre recherche"; ?>
              </td>
            </tr>
            <tr class="en-tete-section">
              <td colspan="5">
                <img src="image/star-fall-minimalistic-2-svgrepo-com.svg" alt="étoile" class="icone" />
                Nos produits vedettes par région
              </td>
            </tr>
            <tr class="ligne-entete">
              <th>Produit</th>
              <th>Région</th>
              <th>Catégorie</th>
              <th>Unité</th>
              <th>Note / Prix / Stock</th>
            </tr>
          </thead>
          <tbody id="table-body">
            <?php 
            $displayed_regions = [];
            if (count($produits) > 0): 
                foreach ($produits as $p): ?>
                <tr class="ligne-produit">
                    <td class="nom-produit">
                        <img src="../<?php echo $p['image']; ?>" class="icone-produit" onerror="this.src='image/logo.jpg'">
                        <?php echo htmlspecialchars($p['nom_produit']); ?>
                    </td>
                    
                    <?php if (!isset($displayed_regions[$p['région']])): ?>
                        <td rowspan="<?php echo $regions_counts[$p['région']]; ?>" style="vertical-align: middle; font-weight: bold;">
                            <?php echo htmlspecialchars($p['région']); ?>
                        </td>
                        <?php $displayed_regions[$p['région']] = true; ?>
                    <?php endif; ?>

                    <td><?php echo htmlspecialchars($p['catégorie']); ?></td>
                    <td><?php echo htmlspecialchars($p['unité']); ?></td>
                    <td>
                        <div class="product-details">
                            <div class="product-price"><?php echo $p['prix']; ?> DT</div>
                            <div class="product-stock">Stock: <?php echo $p['quantité']; ?></div>
                            <?php if ($p['offre'] > 0): ?>
                                <div class="offre-badge">-<?php echo $p['offre']; ?>%</div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; 
            else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:40px; color: #666;">
                        Aucun produit trouvé pour cette recherche.
                    </td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

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
          <a href="../about us/about us.php">About Us</a>
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
          <a href="#"><img src="image/facebook-svgrepo-com (1).svg" alt="Facebook" class="social-icon" /></a>
          <a href="#"><img src="image/instagram-167-svgrepo-com.svg" alt="Instagram" class="social-icon" /></a>
          <a href="#"><img src="image/linkedin-svgrepo-com (1).svg" alt="LinkedIn" class="social-icon" /></a>
        </div>
        <p class="footer-copy">
          © 2025 BioBladi — Tous droits réservés — Fièrement tunisien 🇹🇳
        </p>
      </div>
    </footer>
  </body>
</html>