// ============================================
// 1. DÉFINITION DU TYPE D'OBJET PRODUIT
// ============================================

function Produit(nom, region, saison, agriculteur, note, stock, prix, offre) {
    this.nom = nom;
    this.region = region;
    this.saison = saison;
    this.agriculteur = agriculteur;
    this.note = parseFloat(note);
    this.stock = parseInt(stock);
    this.prix = parseFloat(prix);
    this.offre = offre || false;

    this.getEtoiles = function() {
        let etoiles = "";
        let plein = Math.floor(this.note);
        let demi = (this.note - plein) >= 0.5;

        for (let i = 0; i < plein; i++) etoiles += "★";
        if (demi) etoiles += "½";
        for (let i = Math.ceil(this.note); i < 5; i++) etoiles += "☆";

        return etoiles;
    };

    this.getOffreTexte = function() {
        if (this.offre) {
            return `🎉 Offre spéciale -${this.offre}% sur 2kg`;
        }
        return "";
    };
}

// ============================================
// 2. DONNÉES PAR DÉFAUT
// ============================================

const produitsParDefaut = [
    new Produit("Miel", "Kef", "Printemps/Été", "Miel Boumiza", 4.2, 45, 25, false),
    new Produit("Huile d'olive", "Sfax", "Automne/Hiver", "Coopérative Errayhan", 5, 120, 18, false),
    new Produit("Harissa", "Sfax", "Toute l'année", "Ferme Châabane", 4.5, 80, 12, false),
    new Produit("Deglet Nour", "Tozeur", "Automne", "Ferme El Bey", 4.8, 200, 15, 25),
    new Produit("Figues de barbarie", "Zaghouan", "Été", "Jardin de Montagne", 4.0, 60, 8, false),
    new Produit("Oeufs", "Nabeul", "Toute l'année", "Plusieurs producteurs", 3.5, 500, 5, false),
    new Produit("Menthe fraîche", "Nabeul", "Toute l'année", "Plusieurs producteurs", 4.2, 150, 3, false),
    new Produit("Pommes", "Jendouba", "Automne", "Ferme El Khir", 4.5, 90, 4.5, false),
    new Produit("Amandes", "Sidi Bouzid", "Automne", "Coopérative El Amel", 4.7, 75, 22, 15)
];

let produits = [];

function chargerProduits() {
    const produitsSauvegardes = localStorage.getItem('produitsBioBladi');
    if (produitsSauvegardes && JSON.parse(produitsSauvegardes).length > 0) {
        const data = JSON.parse(produitsSauvegardes);
        produits = data.map(p => new Produit(p.nom, p.region, p.saison, p.agriculteur, p.note, p.stock, p.prix, p.offre));
    } else {
        produits = [...produitsParDefaut];
        sauvegarderProduits();
    }
}

function sauvegarderProduits() {
    localStorage.setItem('produitsBioBladi', JSON.stringify(produits));
}

// ============================================
// 3. AFFICHAGE DYNAMIQUE DU TABLEAU
// ============================================

function afficherTableau() {
    const tbody = document.getElementById('table-body');
    if (!tbody) return;

    tbody.innerHTML = '';

    produits.forEach(produit => {
        const ligne = document.createElement('tr');
        ligne.className = 'product-row';

        // Produit
        const tdNom = document.createElement('td');
        tdNom.className = 'product-name';
        const nomFichier = produit.nom.toLowerCase().replace(/[éèêë]/g, 'e').replace(/[^a-z]/g, '_');
        tdNom.innerHTML = `
            <img src="image/${nomFichier}.jpg" class="product-icon" alt="${produit.nom}" 
                 onerror="this.style.display='none'">
            ${produit.nom}
        `;
        ligne.appendChild(tdNom);

        // Région
        const tdRegion = document.createElement('td');
        tdRegion.textContent = produit.region;
        ligne.appendChild(tdRegion);

        // Saison
        const tdSaison = document.createElement('td');
        tdSaison.textContent = produit.saison;
        ligne.appendChild(tdSaison);

        // Agriculteur
        const tdAgriculteur = document.createElement('td');
        tdAgriculteur.textContent = produit.agriculteur;
        ligne.appendChild(tdAgriculteur);

        // Note + Prix + Stock
        const tdInfos = document.createElement('td');
        let offreHtml = produit.getOffreTexte() ? `<div class="offre-badge">${produit.getOffreTexte()}</div>` : '';
        tdInfos.innerHTML = `
            <div class="product-rating">${produit.getEtoiles()}</div>
            <div class="product-price">💰 ${produit.prix} DT</div>
            <div class="product-stock ${produit.stock < 10 ? 'stock-low' : ''}">
                📦 Stock: ${produit.stock}
            </div>
            ${offreHtml}
        `;
        ligne.appendChild(tdInfos);

        tbody.appendChild(ligne);
    });
}

// ============================================
// 4. FONCTIONS DE RECHERCHE
// ============================================

function rechercherProduits(critere, valeur) {
    let resultats = [];

    switch (critere) {
        case 'nom':
            resultats = produits.filter(p => p.nom.toLowerCase().includes(valeur.toLowerCase()));
            break;
        case 'region':
            resultats = produits.filter(p => p.region.toLowerCase().includes(valeur.toLowerCase()));
            break;
        case 'saison':
            resultats = produits.filter(p => p.saison.toLowerCase().includes(valeur.toLowerCase()));
            break;
        case 'prixMin':
            resultats = produits.filter(p => p.prix >= parseFloat(valeur));
            break;
        case 'prixMax':
            resultats = produits.filter(p => p.prix <= parseFloat(valeur));
            break;
        case 'stock':
            resultats = produits.filter(p => p.stock > 0);
            break;
        case 'promo':
            resultats = produits.filter(p => p.offre !== false);
            break;
        default:
            resultats = [...produits];
    }

    return resultats;
}

function afficherRecherche(critere, valeur) {
    const resultats = rechercherProduits(critere, valeur);

    if (resultats.length === 0) {
        afficherAlerte("Aucun produit trouvé !", "error");
        return;
    }

    const produitsOriginaux = [...produits];
    produits = resultats;
    afficherTableau();

    let resetBtn = document.getElementById('reset-search-btn');
    if (!resetBtn) {
        resetBtn = document.createElement('button');
        resetBtn.id = 'reset-search-btn';
        resetBtn.textContent = "🔄 Afficher tous les produits";
        resetBtn.style.cssText = `
            display: block;
            margin: 20px auto;
            padding: 10px 24px;
            background: #2E8B57;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        `;
        resetBtn.onclick = () => {
            produits = produitsOriginaux;
            afficherTableau();
            resetBtn.remove();
            afficherAlerte("Affichage de tous les produits", "info");
        };

        const section = document.querySelector('.products-section');
        if (section) section.insertAdjacentElement('afterend', resetBtn);
    }

    afficherAlerte(`🔍 ${resultats.length} produit(s) trouvé(s)`, "success");
}

// ============================================
// 5. ALERTES
// ============================================

function afficherAlerte(message, type) {
    const existing = document.getElementById('dynamic-alert');
    if (existing) existing.remove();

    const alert = document.createElement('div');
    alert.id = 'dynamic-alert';
    const colors = { success: '#2E8B57', error: '#d32f2f', info: '#ff9800' };
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 24px;
        background: ${colors[type] || '#2E8B57'};
        color: white;
        border-radius: 8px;
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    `;
    alert.textContent = message;
    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// ============================================
// 6. CRÉATION DU FORMULAIRE DE RECHERCHE
// ============================================

function creerFormulaireRecherche() {
    if (document.getElementById('search-form')) return;

    const searchHtml = `
        <div id="search-form" style="max-width: 500px; margin: 30px auto; background: white; padding: 20px; border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 1px solid #c8e6c9;">
            <h3 style="color: #2E8B57; margin-bottom: 15px; text-align: center;">🔍 Rechercher un produit</h3>
            <div style="margin-bottom: 12px;">
                <select id="critere" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                    <option value="nom">Par nom</option>
                    <option value="region">Par région</option>
                    <option value="saison">Par saison</option>
                    <option value="prixMin">Prix minimum (DT)</option>
                    <option value="prixMax">Prix maximum (DT)</option>
                    <option value="stock">Produits en stock</option>
                    <option value="promo">Produits en promotion</option>
                </select>
            </div>
            <div style="margin-bottom: 12px;">
                <input type="text" id="searchValue" placeholder="Valeur à rechercher" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
            </div>
            <button id="btnRechercher" style="width:100%; padding: 12px; background:#2E8B57; color:white; border:none; border-radius: 30px; font-weight:600; cursor:pointer;">Rechercher</button>
        </div>
    `;

    const section = document.querySelector('.products-section');
    if (section) {
        section.insertAdjacentHTML('afterend', searchHtml);

        document.getElementById('btnRechercher').onclick = () => {
            const critere = document.getElementById('critere').value;
            const valeur = document.getElementById('searchValue').value;

            if (!valeur && critere !== 'stock' && critere !== 'promo') {
                afficherAlerte("Veuillez entrer une valeur de recherche", "error");
                return;
            }

            afficherRecherche(critere, valeur);
        };
    }
}

// ============================================
// 7. STYLES CSS DYNAMIQUES
// ============================================

function ajouterStyles() {
    if (document.getElementById('dynamic-styles')) return;

    const style = document.createElement('style');
    style.id = 'dynamic-styles';
    style.textContent = `
        .product-rating { color: #FFD700; font-size: 14px; }
        .product-price { font-weight: 600; color: #14532d; margin-top: 4px; }
        .product-stock { font-size: 12px; color: #2c3e2f; margin-top: 4px; }
        .stock-low { color: #ff9800; font-weight: 500; }
        .offre-badge { background: #ffd700; color: #1a2e1f; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-top: 6px; display: inline-block; }
        .product-icon { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px; vertical-align: middle; }
        .product-name { display: flex; align-items: center; gap: 8px; }
        .footer-table { width: 100%; border-collapse: collapse; background: white; border-radius: 16px; overflow: hidden; }
        .footer-table th, .footer-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        .products-header th { background: #e8f5e9; color: #2e7d32; font-weight: 600; }
        .table-title { background: linear-gradient(135deg, #2E8B57, #1e6b3f); color: white; font-size: 1.2rem; text-align: center; }
        .section-header { background: #1f3b2c; color: white; text-align: center; font-size: 1.1rem; }
        .product-row:hover { background: #f9f9f9; }
        #search-form input:focus, #search-form select:focus { outline: none; border-color: #2E8B57; box-shadow: 0 0 0 2px rgba(46,139,86,0.2); }
    `;
    document.head.appendChild(style);
}

// ============================================
// 8. INITIALISATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    ajouterStyles();
    chargerProduits();
    afficherTableau();
    creerFormulaireRecherche();
});