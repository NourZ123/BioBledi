
// declaration d'un objet qui contient les informations de l'agriculteur
//les données ne vont pas changer donc on utilise const
const farmerData = {
    nom: "Ben",
    prenom: "Ali",
    email: "agri@test.com",
    telephone: "+216 12 345 678",
    adresse: "Tunis, Tunisie",
    ferme: "Ferme Bio Test",
    typeProduction: "Fruits & Légumes",
    description: "Ferme biologique certifiée"
};

let produitsAgriculteur = [];

function chargerInfosAgriculteur() {
    //on cherche la balise qui va contenir les infos de l'agriculteur
    const infoGrid = document.getElementById('farmerInfo');
    //si elle n'existe pas, on arrête la fonction
    if (!infoGrid) return;
    //on remplace le contenu de la grille par les infos de l'agriculteur 
    infoGrid.innerHTML = `
        <div class="info-card">
            <span class="info-label">👤 Nom complet</span>
            <span class="info-value">${farmerData.nom} ${farmerData.prenom}</span>
        </div>
        <div class="info-card">
            <span class="info-label">📧 Email</span>
            <span class="info-value">${farmerData.email}</span>
        </div>
        <div class="info-card">
            <span class="info-label">📞 Téléphone</span>
            <span class="info-value">${farmerData.telephone}</span>
        </div>
        <div class="info-card">
            <span class="info-label">📍 Adresse</span>
            <span class="info-value">${farmerData.adresse}</span>
        </div>
        <div class="info-card">
            <span class="info-label">🏠 Nom de la ferme</span>
            <span class="info-value">${farmerData.ferme}</span>
        </div>
        <div class="info-card">
            <span class="info-label">🌾 Type de production</span>
            <span class="info-value">${farmerData.typeProduction}</span>
        </div>
        <div class="info-card">
            <span class="info-label">📝 Description</span>
            <span class="info-value">${farmerData.description}</span>
        </div>
    `;
}
//gestion des produits

function chargerProduitsAgriculteur() {
    // Produits par défaut pour le test
    produitsAgriculteur = [
        {
            id: 1,
            nom: "Fraise",
            prix: 15,
            stock: 50,
            unite: "kg",
            region: "Nabeul",
            saison: "Printemps",
            offre: false,
            description: "Fraises fraîches"
        },
        {
            id: 2,
            nom: "Tomates",
            prix: 4.5,
            stock: 120,
            unite: "kg",
            region: "Nabeul",
            saison: "Été",
            offre: false,
            description: "Tomates cerises"
        },
        {
            id: 3,
            nom: "Huile d'olive",
            prix: 18,
            stock: 80,
            unite: "litre",
            region: "Sfax",
            saison: "Automne",
            offre: 15,
            description: "Huile d'olive extra vierge"
        }
    ];
    //pour afficher les produits dans la page
    afficherProduits();
    //pour mettre à jour les compteurs
    mettreAJourStatistiques();
}

function afficherProduits() {
    //on cherche la balise avec l'id productsContainer
    const container = document.getElementById('productsContainer');
    //si elle n'existe pas on arrete
    if (!container) return;
    //si on n'a aucun produit
    if (produitsAgriculteur.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <p>Aucun produit ajouté pour le moment.</p>
                <p>Utilisez le formulaire ci-dessus pour ajouter vos produits.</p>
            </div>
        `;
        return;
    }
    //on parcourt chaque produit et on crée une carte pour l'afficher
    container.innerHTML = produitsAgriculteur.map(
        //pour chaque produit on exécute ce code
        produit => `
        <div class="product-card" data-id="${produit.id}">
        //
            <div class="product-name">${produit.nom}</div>
            <div class="product-price">💰 ${produit.prix} DT / ${produit.unite || 'kg'}</div>
            <div class="product-stock">📦 Stock: ${produit.stock}</div>
            <div class="product-location">📍 ${produit.region || 'Tunisie'}</div>
            <div class="product-season">🌱 ${produit.saison || 'Toute l\'année'}</div>
            // si le produit a une offre, on affiche une étiquette spéciale
            ${produit.offre ? `<div class="product-offer">🎉 Offre spéciale -${produit.offre}%</div>` : ''}
            <div class="product-actions">
                <button class="edit-btn" onclick="modifierProduit(${produit.id})">✏️ Modifier</button>
                <button class="delete-btn" onclick="supprimerProduit(${produit.id})">🗑️ Supprimer</button>
            </div>
        </div>
    `).join('');
    //joindre tous les éléments du tableau en une seule chaîne de caractères pour l'afficher dans le conteneur
}
function mettreAJourStatistiques() {
    const totalProducts = produitsAgriculteur.length;
    
    let totalStock = 0;
    let totalSales = 0;
    
    for (let produit of produitsAgriculteur) {
        totalStock = totalStock + (produit.stock || 0);
        totalSales = totalSales + (produit.vendu || 0);
    }
    
    const totalproduits = document.getElementById('totalProducts');
    const totalStockF = document.getElementById('totalStock');
    const totalSalesEl = document.getElementById('totalSales');
    
    if (totalproduits) totalproduits.textContent = totalProducts;
    if (totalStockF) totalStockF.textContent = totalStock;
    if (totalSalesEl) totalSalesEl.textContent = totalSales;
}



// ============================================
// 3. AJOUTER UN PRODUIT
// ============================================

function ajouterProduit(event) {
    //event est le clic sur le bouton du formulaire
    //on empeche la page de se recharger automatiquement
    event.preventDefault();
    //on récupère les valeurs du formulaire à travers .value
    const nom = document.getElementById('nom-produit').value;
    const prix = document.getElementById('prix-produit').value;
    const stock = document.getElementById('stock-produit').value;
    const categorie = document.getElementById('categorie-produit').value;
    const saison = document.getElementById('saison-produit').value;
    const offre = document.getElementById('offre-produit').value;
    const description = document.getElementById('description-produit').value;
    //si l'un de ces champs est vide on affiche une alerte et on arrete
    if (!nom || !prix || !stock) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }
    
    const nouveauProduit = {
        //l'id doit etre unique
        id: Date.now(),
        nom: nom,
        prix: parseFloat(prix),
        stock: parseInt(stock),
        categorie: categorie,
        saison: saison,
        region: farmerData.adresse || 'Tunisie',
        agriculteur: `${farmerData.nom} ${farmerData.prenom}`,
        unite: 'kg',
        offre: offre ? parseInt(offre) : false,
        description: description || '',
        dateAjout: new Date().toLocaleDateString(),
        vendu: 0
    };
    //on ajoute le nouveau produit à la liste des produits de l'agriculteur
    produitsAgriculteur.push(nouveauProduit);
    
    // Réinitialiser le formulaire
    document.getElementById('productForm').reset();
    
    // Recharger l'affichage
    afficherProduits();
    mettreAJourStatistiques();
    
    alert(`✅ "${nom}" a été ajouté avec succès !`);
}

function modifierProduit(id) {
    // Chercher le produit
    let produit = null;
    for (let p of produitsAgriculteur) {
        if (p.id === id) {
            produit = p;
            break;
        }
    }
    if (!produit) return;
    
    // Demander les nouvelles valeurs
    const nouveauNom = prompt('Nouveau nom du produit:', produit.nom);
    if (nouveauNom === null) return;
    
    const nouveauPrix = prompt('Nouveau prix (DT):', produit.prix);
    if (nouveauPrix === null) return;
    
    const nouveauStock = prompt('Nouveau stock:', produit.stock);
    if (nouveauStock === null) return;
    
    // Modifier le produit
    produit.nom = nouveauNom;
    produit.prix = parseFloat(nouveauPrix);
    produit.stock = parseInt(nouveauStock);
    
    // Recharger l'affichage
    afficherProduits();
    mettreAJourStatistiques();
    
    alert(`✅ "${produit.nom}" a été modifié avec succès !`);
}
// Supprimer un produit
function supprimerProduit(id) {
    // Chercher l'index du produit
    let index = -1;
    for (let i = 0; i < produitsAgriculteur.length; i++) {
        if (produitsAgriculteur[i].id === id) {
            index = i;
            break;
        }
    }
    if (index === -1) return;
    
    // Supprimer 1 élément qui a la position index
    produitsAgriculteur.splice(index, 1);
    
    // Recharger l'affichage
    afficherProduits();
    mettreAJourStatistiques();
    
    alert('✅ Produit supprimé avec succès !');
}


//quand la page est complètement chargée, on exécute cette fonction 
document.addEventListener('DOMContentLoaded', function() {
    // Charger les informations de l'agriculteur
    chargerInfosAgriculteur();
    
    // Charger les produits
    chargerProduitsAgriculteur();
    
    //on cherche le formulaire de l'id productForm
    const form = document.getElementById('productForm');
    //quand on soumet le formulaire, on exécute la fonction ajouterProduit
    if (form) {
        form.addEventListener('submit', ajouterProduit);
    }
    
    
});