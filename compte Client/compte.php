<?php
session_start();
require "../PHP/database_connection.php"; 

$connexion = $_SESSION['user_data'] ?? null;
$name = $prename = $adress = $phone = $email = '';
$total_commandes = 0;
$derniere_commande = null;
$commandesObjets = [];

class CommandeObjet {
    public $id_commande;
    public $montant;
    public $date_commande;
    public $statut;
    public $adresse;

    public function getDateFormat() {
        return date('d/m/Y', strtotime($this->date_commande));
    }
}

function afficherTableauCommandes($liste) {
    if (empty($liste)) {
        echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Aucune commande passée</td></tr>';
        return;
    }
    foreach ($liste as $cmd) {
        $classeStatut = ($cmd->statut == 'Livré') ? 'statut-livre' : 'statut-encours';
        echo "<tr class='ligne-table'>";
        echo "<td class='cellule-commande'>CMD{$cmd->id_commande}</td>";
        echo "<td class='cellule-date'>{$cmd->getDateFormat()}</td>";
        echo "<td class='cellule-montant'>" . number_format($cmd->montant, 2) . " DT</td>";
        echo "<td class='cellule-statut {$classeStatut}'>" . htmlspecialchars($cmd->statut ?? 'En attente') . "</td>";
        echo "</tr>";
    }
}

if ($connexion && isset($_SESSION['type']) && $_SESSION['type'] === "client") {
    $user = $_SESSION['user_data'];
    $name = $user['Nom'] ?? '';
    $prename = $user['Prénom'] ?? '';
    $adress = $user['Adresse'] ?? '';
    $phone = $user['Telephone'] ?? '';
    $email = $user['Email'] ?? '';
    $id_client = $user['id_client'] ?? $user['ID'] ?? $user['id'] ?? null;
    
    if ($id_client) {
        $stmtCount = $db->prepare("SELECT COUNT(*) as total FROM commande WHERE id_client = ?");
        $stmtCount->execute([$id_client]);
        $total_commandes = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $db->prepare("SELECT id_commande, montant, date_commande, statut, adresse FROM commande WHERE id_client = ? ORDER BY date_commande DESC");
        $stmt->execute([$id_client]);
        
        while ($obj = $stmt->fetchObject('CommandeObjet')) {
            $commandesObjets[] = $obj;
        }
        
        if (!empty($commandesObjets)) {
            $derniere_commande = $commandesObjets[0];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BioBladi - Tableau de bord</title>
    <link rel="stylesheet" href="compte.css" />
    <link rel="icon" href="image/screen-alt-2-svgrepo-com.svg" />
    <link rel="stylesheet" href="../code footer.css" />
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font-family: "Poppins", sans-serif; background-color: #f3f5f8; min-height: 100vh; display: flex; flex-direction: column; width: 100%; overflow-x: hidden; }
      hr { border: none; border-top: 2px solid #cbd5c0; width: 100%; margin: 0; }
      .option-menu { cursor: pointer; }
      .option-menu:hover { background-color: #e2e8f0; border-radius: 8px; }
      .lien_nav { text-decoration: none; color: #2c3e2f; font-size: 16px; font-weight: 500; transition: color 0.3s ease; }
      
      .zone-commandes { max-height: 300px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; background: white; }
      .tableau-commandes thead { position: sticky; top: 0; background-color: #f8fafc; z-index: 10; }
      .zone-commandes::-webkit-scrollbar { width: 6px; }
      .zone-commandes::-webkit-scrollbar-thumb { background: #14532d; border-radius: 10px; }
    </style>
  </head>
  <body>
    <div class="head">
      <div class="head-left">
        <img src="image/logo.jpg" alt="logo" class="logo" />
        <div class="page-title-container"><p class="page-title"><strong>Mon Compte</strong></p></div>
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
          <a href="../compte Client/compte.php"><img src="image/person-svgrepo-com.svg" class="user-icon" /></a>
          <a href="../mon panier/panier.php"><img src="image/cart-2-svgrepo-com.svg" class="cart-icon" /></a>
        </div>
      </div>
    </div>

    <div class="page-compte">
      <div class="menu-lateral">
        <div class="titre-section"><img src="image/person-svgrepo-com.svg" class="icone-titre" /> Compte client</div>
        <div class="liste-options">
          <div id="btn-dashboard" class="option-menu" style="padding: 10px"><img src="image/screen-alt-2-svgrepo-com (1).svg" class="icone-menu" /> Tableau de bord</div>
          <div id="btn-infos" class="option-menu" style="padding: 10px"><img src="image/information-point-svgrepo-com.svg" class="icone-menu" /> Mes informations</div>
          <div id="btn-Commande" class="option-menu" style="padding: 10px"><img src="image/cart-large-2-svgrepo-com.svg" class="icone-menu" /> Mes commandes</div>
          <div class="option-menu" style="padding: 10px"><a href="logout.php" class="lien_nav">Déconnexion</a></div>
        </div>
      </div>

      <div class="zone-principale">
        <div id="vue-dashboard">
          <div class="bloc-bienvenue">
            <h1 class="titre-bienvenue">Bonjour <?php echo htmlspecialchars($prename ?: 'Visiteur') ?> !</h1>
            <p class="sous-titre-bienvenue">Bienvenue dans votre espace client.</p>
          </div>
          <div class="grille-trois-cartes">
            <div class="carte-info carte-total">
              <h4 class="titre-carte">Total des commandes</h4>
              <p class="nombre-total"><?= $total_commandes ?> commande<?= $total_commandes > 1 ? 's' : '' ?></p>
            </div>
            <div class="carte-info carte-derniere">
              <h4 class="titre-carte">Dernière commande</h4>
              <?php if($derniere_commande): ?>
                <p class="ref-derniere">CMD<?= $derniere_commande->id_commande ?> - <?= number_format($derniere_commande->montant, 2) ?> dt</p>
                <p class="adresse-derniere"><?= htmlspecialchars($derniere_commande->adresse) ?></p>
              <?php else: ?>
                <p class="ref-derniere">Aucune commande</p>
              <?php endif; ?>
            </div>
            <div class="carte-info carte-adresse">
              <h4 class="titre-carte">Adresse de livraison</h4>
              <p class="details-adresse"><?php echo htmlspecialchars($adress ?: 'Votre Adresse')?></p>
            </div>
          </div>
          <div class="grille-deux-colonnes">
            <div class="carte-info carte-personnelles">
              <h4 class="titre-carte">Informations personnelles</h4>
              <span id="modif" style="color:darkgrey;"></span> 
              <div class="grille-personnelles">
                <p class="element-personnel"><span class="label-personnel">Nom :</span> <?= htmlspecialchars($name ?: 'Fouleni') ?></p>
                <p class="element-personnel"><span class="label-personnel">Prénom :</span> <?= htmlspecialchars($prename ?: 'Foulen') ?></p>
                <p class="element-personnel"><span class="label-personnel">Email :</span> <?= htmlspecialchars($email ?: 'Foulen.Fouleni@gmail.com') ?></p>
                <p class="element-personnel"><span class="label-personnel">Téléphone :</span> <?= htmlspecialchars($phone ?: '12 345 678') ?></p>
                <p class="element-personnel"><span class="label-personnel">Adresse :</span> <?= htmlspecialchars($adress ?: 'Tunis, El Manar, 2092') ?></p>
              </div>
              <div class="conteneur-modifier"><a href="../modifier mes informations/modifier.html" class="lien-modifier">Modifier mes informations</a></div>
            </div>
            <div class="zone-commandes">
              <h3 class="titre-commandes" style="padding:15px;">Mes Commandes Récentes</h3>
              <table class="tableau-commandes">
                <thead><tr><th>N°Commande</th><th>Date</th><th>Montant</th><th>Statut</th></tr></thead>
                <tbody><?php afficherTableauCommandes($commandesObjets); ?></tbody>
              </table>
            </div>
          </div>
        </div>

        <div id="vue-infos" style="display: none">
          <div class="bloc-bienvenue">
            <h1 class="titre-bienvenue">Mes Informations Personnelles</h1>
            <p class="sous-titre-bienvenue">Consultez et gérez vos données personnelles ici.</p>
          </div>
          <div class="carte-info" style="width: 100%; max-width: 100%; margin-top: 20px; padding: 30px;">
            <h4 class="titre-carte" style="font-size: 24px; margin-bottom: 20px">Mes coordonnées</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 18px;">
              <p class="element-personnel"><span class="label-personnel">Nom :</span> <?= htmlspecialchars($name ?: 'Fouleni') ?></p>
              <p class="element-personnel"><span class="label-personnel">Prénom :</span> <?= htmlspecialchars($prename ?: 'Foulen') ?></p>
              <p class="element-personnel"><span class="label-personnel">Email :</span> <?= htmlspecialchars($email ?: 'Foulen.Fouleni@gmail.com') ?></p>
              <p class="element-personnel"><span class="label-personnel">Téléphone :</span> <?= htmlspecialchars($phone ?: '12 345 678') ?></p>
              <p class="element-personnel" style="grid-column: span 2"><span class="label-personnel">Adresse :</span> <?= htmlspecialchars($adress ?: 'Tunis, El Manar, 2092') ?></p>
            </div>
            <div class="conteneur-modifier" style="margin-top: 30px">
              <a href="../modifier mes informations/modifier.html" class="lien-modifier" style="padding: 10px 20px; background-color: #14532d; color: white; border-radius: 5px; text-decoration: none;">Modifier mes informations</a>
            </div>
          </div>
        </div>

        <div id="vue-commandes" style="display: none">
          <div class="bloc-bienvenue">
            <h1 class="titre-bienvenue">Historique de mes commandes</h1>
            <p class="sous-titre-bienvenue">Retrouvez ici toutes vos commandes passées et en cours.</p>
          </div>
          <div class="carte-info" style="width: 100%; max-width: 100%; margin-top: 20px; padding: 30px;">
            <table class="tableau-commandes" style="width: 100%">
              <thead><tr class="ligne-en-tete"><th>N°Commande</th><th>Date</th><th>Montant</th><th>Statut</th></tr></thead>
              <tbody><?php afficherTableauCommandes($commandesObjets); ?></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-brand">
          <img
            src="image/logo.jpg"
            class="logo"
            alt="Logo de BioBladi en pied de page"
          />
          <span style="color: white; font-weight: bold"
            >BioBladi — Du champ à votre assiette, produits locaux et bio
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
            <img
              src="image/phone-svgrepo-com (1).svg"
              alt="Icône"
              class="footer-icon"
            />
            +216 12 345 678
          </p>
          <p>
            <img
              src="image/mail-check-svgrepo-com.svg"
              alt="Icône "
              class="footer-icon"
            />
            contact@biobladi.tn
          </p>
          <p>
            <img
              src="image/location-svgrepo-com.svg"
              alt="I"
              class="footer-icon"
            />
            Tunis, Tunisie
          </p>
          <p>
            <img
              src="image/time-svgrepo-com.svg"
              alt="Icône d'horloge"
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
              alt="Lien"
              class="social-icon"
          /></a>
          <a href="#"
            ><img
              src="image/instagram-167-svgrepo-com.svg"
              alt="Lien "
              class="social-icon"
          /></a>
          <a href="#"
            ><img
              src="image/linkedin-svgrepo-com (1).svg"
              alt="Lien "
              class="social-icon"
          /></a>
        </div>
        <p class="footer-copy">
          © 2025 BioBladi — Tous droits réservés — Fièrement tunisien 🇹🇳
        </p>
      </div>
    </footer>

    <script>
     const btnDashboard = document.getElementById("btn-dashboard");
      const btnInfos = document.getElementById("btn-infos");
      const btnCommande = document.getElementById("btn-Commande");

      const vueDashboard = document.getElementById("vue-dashboard");
      const vueInfos = document.getElementById("vue-infos");
      const vueCommandes = document.getElementById("vue-commandes");
      document.getElementById("modif").innerHTML = "";
      
      function cacherToutesLesVues() {
        vueDashboard.style.display = "none";
        vueInfos.style.display = "none";
        vueCommandes.style.display = "none";
      }

      btnDashboard.addEventListener("click", function () {
        cacherToutesLesVues();
        vueDashboard.style.display = "block";
      });

      btnInfos.addEventListener("click", function () {
        cacherToutesLesVues();
        vueInfos.style.display = "block";
        vueInfos.style.width = "70%";
        vueInfos.style.margin = "0 auto";
      });

      btnCommande.addEventListener("click", function () {
        cacherToutesLesVues();
        vueCommandes.style.display = "block";
      });
      
      const params = new URLSearchParams(window.location.search);
      if (params.has("modif")) {
        document.getElementById("modif").innerHTML = "modification réussie !";
      }
    </script>
  </body>
</html>