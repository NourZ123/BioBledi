// ============================================
// ESPACE AGRICULTEUR - GESTION COMPLÈTE
// ============================================

let session = null;
let farmerData = null;
let produitsAgriculteur = [];

// ============================================
// 1. GESTION DE LA SESSION
// ============================================

function getSession() {
    const sessionData = localStorage.getItem('biobladi_session');
    if (sessionData) {
        session = JSON.parse(sessionData);
        return session;
    }
    return null;
}

function checkAuth() {
    const session = getSession();
    if (!session || session.type !== 'agriculteur') {
        alert('Veuillez vous connecter en tant qu\'agriculteur');
        window.location.href = '../se connecter/bienvenue.html';
        return false;
    }
    return true;
}

function deconnexion() {
    localStorage.removeItem('biobladi_session');
    window.location.href = '../se connecter/bienvenue.html';
}

// ============================================
// 2. AFFICHAGE DES INFORMATIONS PERSONNELLES
// ============================================

function chargerInfosAgriculteur() {
    const session = getSession();
    if (!session) return;
    
    const users = JSON.parse(localStorage.getItem('biobladi_users')) || [];
    farmerData = users.find(u => u.email === session.email);
    
    if (farmerData) {
        const infoGrid = document.getElementById('farmerInfo');
        if (infoGrid) {
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
                ${farmerData.ferme ? `
                <div class="info-card">
                    <span class="info-label">🏠 Nom de la ferme</span>
                    <span class="info-value">${farmerData.ferme}</span>
                </div>
                ` : ''}
                ${farmerData.typeProduction ? `
                <div class="info-card">
                    <span class="info-label">🌾 Type de production</span>
                    <span class="info-value">${farmerData.typeProduction}</span>
                </div>
                ` : ''}
                ${farmerData.description ? `
                <div class="info-card">
                    <span class="info-label">📝 Description</span>
                    <span class="info-value">${farmerData.description}</span>
                </div>
                ` : ''}
            `;
        }
    }
}

// ============================================
// 3. GESTION DES PRODUITS
// ============================================

function chargerProduitsAgriculteur() {
    const session = getSession();
    if (!session) return;
    
    const allProduits = JSON.parse(localStorage.getItem('produitsBioBladi')) || [];
    produitsAgriculteur = allProduits.filter(p => p.agriculteur === `${session.nom} ${session.prenom}`);
    
    afficherProduits();
    mettreAJourStatistiques();
}

function afficherProduits() {
    const container = document.getElementById('productsContainer');
    if (!container) return;
    
    if (produitsAgriculteur.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <p>Aucun produit ajouté pour le moment.</p>
                <p>Utilisez le formulaire ci-dessus pour ajouter vos produits.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = produitsAgriculteur.map(produit => `
        <div class="product-card" data-id="${produit.id}">
            <div class="product-name">${produit.nom}</div>
            <div class="product-price">💰 ${produit.prix} DT / ${produit.unite || 'kg'}</div>
            <div class="product-stock ${produit.stock < 10 ? 'low' : ''}">📦 Stock: ${produit.stock}</div>
            <div class="product-stock">📍 ${produit.region}</div>
            <div class="product-stock">🌱 ${produit.saison}</div>
            ${produit.offre ? `<div class="product-offer">🎉 Offre spéciale -${produit.offre}%</div>` : ''}
            <div class="product-actions">
                <button class="edit-btn" onclick="modifierProduit(${produit.id})">✏️ Modifier</button>
                <button class="delete-btn" onclick="supprimerProduit(${produit.id})">🗑️ Supprimer</button>
            </div>
        </div>
    `).join('');
}

function mettreAJourStatistiques() {
    const totalProducts = produitsAgriculteur.length;
    const totalStock = produitsAgriculteur.reduce((sum, p) => sum + p.stock, 0);
    const totalSales = produitsAgriculteur.filter(p => p.vendu || 0).reduce((sum, p) => sum + (p.vendu || 0), 0);
    
    document.getElementById('totalProducts').textContent = totalProducts;
    document.getElementById('totalStock').textContent = totalStock;
    document.getElementById('totalSales').textContent = totalSales;
}

// ============================================
// 4. AJOUTER UN PRODUIT
// ============================================

function ajouterProduit(event) {
    event.preventDefault();
    
    const session = getSession();
    if (!session) return;
    
    const nom = document.getElementById('nom-produit').value;
    const prix = document.getElementById('prix-produit').value;
    const stock = document.getElementById('stock-produit').value;
    const categorie = document.getElementById('categorie-produit').value;
    const saison = document.getElementById('saison-produit').value;
    const offre = document.getElementById('offre-produit').value;
    const description = document.getElementById('description-produit').value;
    
    if (!nom || !prix || !stock) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }
    
    const nouveauProduit = {
        id: Date.now(),
        nom: nom,
        prix: parseFloat(prix),
        stock: parseInt(stock),
        categorie: categorie,
        saison: saison,
        region: farmerData?.adresse?.split(',')[0] || 'Tunisie',
        agriculteur: `${session.nom} ${session.prenom}`,
        unite: 'kg',
        offre: offre ? parseInt(offre) : false,
        description: description,
        dateAjout: new Date().toISOString()
    };
    
    const allProduits = JSON.parse(localStorage.getItem('produitsBioBladi')) || [];
    allProduits.push(nouveauProduit);
    localStorage.setItem('produitsBioBladi', JSON.stringify(allProduits));
    
    // Réinitialiser le formulaire
    document.getElementById('productForm').reset();
    
    // Recharger les produits
    chargerProduitsAgriculteur();
    
    alert(`✅ "${nom}" a été ajouté avec succès !`);
}

// ============================================
// 5. MODIFIER UN PRODUIT
// ============================================

function modifierProduit(id) {
    const produit = produitsAgriculteur.find(p => p.id === id);
    if (!produit) return;
    
    const nouveauNom = prompt('Nouveau nom du produit:', produit.nom);
    if (nouveauNom === null) return;
    
    const nouveauPrix = prompt('Nouveau prix (DT):', produit.prix);
    if (nouveauPrix === null) return;
    
    const nouveauStock = prompt('Nouveau stock:', produit.stock);
    if (nouveauStock === null) return;
    
    const nouvelleOffre = prompt('Offre spéciale (%) (0 = pas d\'offre):', produit.offre || 0);
    if (nouvelleOffre === null) return;
    
    // Mettre à jour le produit
    produit.nom = nouveauNom;
    produit.prix = parseFloat(nouveauPrix);
    produit.stock = parseInt(nouveauStock);
    produit.offre = parseInt(nouvelleOffre) || false;
    
    // Sauvegarder dans localStorage
    const allProduits = JSON.parse(localStorage.getItem('produitsBioBladi')) || [];
    const index = allProduits.findIndex(p => p.id === id);
    if (index !== -1) {
        allProduits[index] = produit;
        localStorage.setItem('produitsBioBladi', JSON.stringify(allProduits));
    }
    
    // Recharger
    chargerProduitsAgriculteur();
    alert(`✅ "${produit.nom}" a été modifié avec succès !`);
}

// ============================================
// 6. SUPPRIMER UN PRODUIT
// ============================================

function supprimerProduit(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) return;
    
    const allProduits = JSON.parse(localStorage.getItem('produitsBioBladi')) || [];
    const nouveauxProduits = allProduits.filter(p => p.id !== id);
    localStorage.setItem('produitsBioBladi', JSON.stringify(nouveauxProduits));
    
    chargerProduitsAgriculteur();
    alert('✅ Produit supprimé avec succès !');
}

// ============================================
// 7. INITIALISATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    if (checkAuth()) {
        chargerInfosAgriculteur();
        chargerProduitsAgriculteur();
        
        // Formulaire d'ajout
        const form = document.getElementById('productForm');
        if (form) {
            form.addEventListener('submit', ajouterProduit);
        }
        
        // Bouton déconnexion
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', deconnexion);
        }
    }
});