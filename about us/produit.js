// ============================================
// 1. DÉFINITION DU TYPE D'OBJET (Constructeur)
// ============================================

/**
 * Constructeur pour créer un objet Produit
 */
function Produit(nom, region, saison, agriculteur, note, stock, prix, offre) {
    this.nom = nom;
    this.region = region;
    this.saison = saison;
    this.agriculteur = agriculteur;
    this.note = note;
    this.stock = stock;
    this.prix = prix;
    this.offre = offre;
    
    // Méthode pour obtenir l'affichage des étoiles
    this.getEtoiles = function() {
        let etoiles = "";
        for (let i = 0; i < Math.floor(this.note); i++) {
            etoiles += "⭐";
        }
        if (this.note % 1 >= 0.5) {
            etoiles += "½";
        }
        for (let i = Math.ceil(this.note); i < 5; i++) {
            etoiles += "☆";
        }
        return etoiles;
    };
    
    // Méthode pour obtenir le libellé de l'offre
    this.getOffreTexte = function() {
        if (this.offre) {
            return "🌟 Offre spéciale -" + this.offre + "% sur 2kg";
        }
        return "";
    };
}

// ============================================
// 2. COLLECTION DE DONNÉES (Tableau d'objets)
// ============================================

let produits = [
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

let produitsTemp = []; // Pour stocker les produits avant recherche
let tableBody = null;

// ============================================
// 3. FONCTION POUR AFFICHER LE TABLEAU
// ============================================

function afficherTableau() {
    if (!tableBody) return;
    
    tableBody.innerHTML = '';
    
    produits.forEach(produit => {
        const ligne = document.createElement('tr');
        ligne.className = 'product-row';
        
        const tdProduit = document.createElement('td');
        tdProduit.className = 'product-name';
        const nomImage = produit.nom.toLowerCase().replace(/ /g, '_');
        tdProduit.innerHTML = `
            <img src="image/${nomImage}.jpg" 
                 alt="${produit.nom}" class="product-icon" 
                 onerror="this.src='image/default.jpg'">
            ${produit.nom}
        `;
        ligne.appendChild(tdProduit);
        
        const tdRegion = document.createElement('td');
        tdRegion.textContent = produit.region;
        ligne.appendChild(tdRegion);
        
        const tdSaison = document.createElement('td');
        tdSaison.textContent = produit.saison;
        ligne.appendChild(tdSaison);
        
        const tdAgriculteur = document.createElement('td');
        tdAgriculteur.textContent = produit.agriculteur;
        ligne.appendChild(tdAgriculteur);
        
        const tdNote = document.createElement('td');
        tdNote.innerHTML = `
            <div style="display:flex; flex-direction:column;">
                <span>${produit.getEtoiles()}</span>
                <span style="font-size:12px; color:#2E8B57;">${produit.prix} DT</span>
                <span style="font-size:11px; color:${produit.stock > 0 ? '#388e3c' : '#d32f2f'}">
                    ${produit.stock > 0 ? `📦 Stock: ${produit.stock}` : '❌ Rupture'}
                </span>
            </div>
        `;
        
        if (produit.offre) {
            tdNote.innerHTML += `<div class="special-offer" style="font-size:11px; margin-top:5px;">${produit.getOffreTexte()}</div>`;
        }
        ligne.appendChild(tdNote);
        
        tableBody.appendChild(ligne);
    });
}

// ============================================
// 4. FONCTION POUR AJOUTER UN PRODUIT (FONCTION 1)
// ============================================

function ajouterProduit(nom, region, saison, agriculteur, note, stock, prix, offre) {
    // Validation des données
    if (!nom || !region || !saison || !agriculteur) {
        alert("Veuillez remplir tous les champs obligatoires !");
        return false;
    }
    
    // Créer un nouveau produit avec le constructeur
    const nouveauProduit = new Produit(
        nom, 
        region, 
        saison, 
        agriculteur, 
        parseFloat(note), 
        parseInt(stock), 
        parseFloat(prix), 
        offre
    );
    
    // Ajouter au tableau
    produits.push(nouveauProduit);
    
    // Rafraîchir l'affichage
    afficherTableau();
    
    return true;
}

// ============================================
// 5. FONCTION POUR RECHERCHER DES PRODUITS (FONCTION 2)
// ============================================

/**
 * Fonction de recherche qui retourne un tableau de produits correspondant aux critères
 * @param {string} critere - Le type de recherche (nom, region, saison, prixMin, prixMax, stock, promo)
 * @param {string|number} valeur - La valeur à rechercher
 * @returns {Array} Tableau des produits correspondant à la recherche
 */
function rechercherProduits(critere, valeur) {
    let resultats = [];
    
    switch(critere) {
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
            resultats = produits;
    }
    
    return resultats;
}

// ============================================
// 6. FONCTION POUR AFFICHER LES RÉSULTATS DE RECHERCHE
// ============================================

function afficherRecherche(critere, valeur) {
    // Appel de la fonction de recherche
    const resultats = rechercherProduits(critere, valeur);
    
    if (resultats.length === 0) {
        alert("Aucun produit trouvé pour cette recherche !");
        return;
    }
    
    // Sauvegarder les produits actuels pour pouvoir réinitialiser
    produitsTemp = [...produits];
    
    // Remplacer les produits par les résultats de recherche
    produits = resultats;
    
    // Afficher les résultats dans le tableau
    afficherTableau();
    
    // Ajouter un bouton pour réinitialiser et revenir à tous les produits
    let resetBtn = document.querySelector('.reset-btn');
    if (!resetBtn) {
        resetBtn = document.createElement('button');
        resetBtn.textContent = "Afficher tous les produits";
        resetBtn.className = "reset-btn";
        resetBtn.style.cssText = "margin: 20px auto; padding: 10px 20px; background: #2E8B57; color: white; border: none; border-radius: 30px; cursor: pointer; display: block; font-family: 'Poppins', sans-serif;";
        
        resetBtn.onclick = function() {
            // Restaurer tous les produits
            produits = produitsTemp;
            afficherTableau();
            this.remove();
        };
        
        const tableContainer = document.querySelector('.footer-container');
        if (tableContainer) {
            tableContainer.insertAdjacentElement('afterend', resetBtn);
        }
    }
}

// ============================================
// 7. CRÉATION DES FORMULAIRES
// ============================================

function creerFormulaires() {
    const formsContainer = document.createElement('div');
    formsContainer.className = 'forms-container';
    formsContainer.style.cssText = `
        max-width: 1000px;
        margin: 30px auto;
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        justify-content: center;
        padding: 0 20px;
    `;
    
    // ========== FORMULAIRE D'AJOUT ==========
    const addForm = document.createElement('div');
    addForm.className = 'add-form';
    addForm.style.cssText = `
        flex: 1;
        min-width: 280px;
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border: 1px solid #c8e6c9;
    `;
    addForm.innerHTML = `
        <h3 style="color: #2E8B57; margin-bottom: 15px;">➕ Ajouter un produit</h3>
        <div style="margin-bottom: 12px;">
            <input type="text" id="nomProd" placeholder="Nom du produit *" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <div style="margin-bottom: 12px;">
            <input type="text" id="regionProd" placeholder="Région *" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <div style="margin-bottom: 12px;">
            <input type="text" id="saisonProd" placeholder="Saison *" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <div style="margin-bottom: 12px;">
            <input type="text" id="agriculteurProd" placeholder="Agriculteur *" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <div style="margin-bottom: 12px;">
            <input type="number" id="noteProd" placeholder="Note (0-5)" step="0.1" min="0" max="5" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <div style="margin-bottom: 12px;">
            <input type="number" id="stockProd" placeholder="Stock" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <div style="margin-bottom: 12px;">
            <input type="number" id="prixProd" placeholder="Prix (DT)" step="0.5" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <div style="margin-bottom: 12px;">
            <label><input type="checkbox" id="offreProd"> En promotion (%)</label>
            <input type="number" id="remiseProd" placeholder="% de remise" style="width:100%; margin-top:5px; padding:8px; border-radius:8px; border:1px solid #ccc; display:none; font-family: inherit;">
        </div>
        <button id="btnAjouter" style="width:100%; padding:12px; background:#2E8B57; color:white; border:none; border-radius:30px; font-weight:600; cursor:pointer; font-family: inherit;">Ajouter le produit</button>
    `;
    
    addForm.querySelector('#offreProd').addEventListener('change', function(e) {
        const remiseField = addForm.querySelector('#remiseProd');
        remiseField.style.display = e.target.checked ? 'block' : 'none';
    });
    
    // ========== FORMULAIRE DE RECHERCHE ==========
    const searchForm = document.createElement('div');
    searchForm.className = 'search-form';
    searchForm.style.cssText = `
        flex: 1;
        min-width: 280px;
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border: 1px solid #c8e6c9;
    `;
    searchForm.innerHTML = `
        <h3 style="color: #2E8B57; margin-bottom: 15px;">🔍 Rechercher un produit</h3>
        <div style="margin-bottom: 12px;">
            <select id="critere" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
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
            <input type="text" id="valeurRecherche" placeholder="Valeur à rechercher" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family: inherit;">
        </div>
        <button id="btnRechercher" style="width:100%; padding:12px; background:#2E8B57; color:white; border:none; border-radius:30px; font-weight:600; cursor:pointer; font-family: inherit;">Rechercher</button>
    `;
    
    // Ajout des formulaires
    const productsSection = document.querySelector('.products-section');
    if (productsSection) {
        productsSection.insertAdjacentElement('afterend', formsContainer);
        formsContainer.appendChild(addForm);
        formsContainer.appendChild(searchForm);
    }
    
    // ========== ÉVÉNEMENT AJOUTER ==========
    document.getElementById('btnAjouter').addEventListener('click', function() {
        const nom = document.getElementById('nomProd').value;
        const region = document.getElementById('regionProd').value;
        const saison = document.getElementById('saisonProd').value;
        const agriculteur = document.getElementById('agriculteurProd').value;
        const note = document.getElementById('noteProd').value || 0;
        const stock = document.getElementById('stockProd').value || 0;
        const prix = document.getElementById('prixProd').value || 0;
        const offre = document.getElementById('offreProd').checked;
        const remise = document.getElementById('remiseProd').value;
        
        // Appel de la fonction d'ajout
        if (ajouterProduit(nom, region, saison, agriculteur, note, stock, prix, offre ? parseInt(remise) : false)) {
            document.getElementById('nomProd').value = '';
            document.getElementById('regionProd').value = '';
            document.getElementById('saisonProd').value = '';
            document.getElementById('agriculteurProd').value = '';
            document.getElementById('noteProd').value = '';
            document.getElementById('stockProd').value = '';
            document.getElementById('prixProd').value = '';
            document.getElementById('offreProd').checked = false;
            document.getElementById('remiseProd').style.display = 'none';
            alert('✅ Produit ajouté avec succès !');
        }
    });
    
    // ========== ÉVÉNEMENT RECHERCHER ==========
    document.getElementById('btnRechercher').addEventListener('click', function() {
        const critere = document.getElementById('critere').value;
        const valeur = document.getElementById('valeurRecherche').value;
        
        if (!valeur && critere !== 'stock' && critere !== 'promo') {
            alert('Veuillez entrer une valeur de recherche');
            return;
        }
        
        // Appel de la fonction d'affichage de recherche (qui elle-même appelle la fonction de recherche)
        afficherRecherche(critere, valeur);
    });
}

// ============================================
// 8. INITIALISATION AU CHARGEMENT
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.footer-table');
    let existingTbody = table.querySelector('tbody');
    
    if (!existingTbody) {
        tableBody = document.createElement('tbody');
        const rows = table.querySelectorAll('tr');
        for (let i = 2; i < rows.length; i++) {
            tableBody.appendChild(rows[i]);
        }
        table.appendChild(tableBody);
    } else {
        tableBody = existingTbody;
    }
    
    afficherTableau();
    creerFormulaires();
});