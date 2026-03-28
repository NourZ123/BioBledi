// définition du constructeur
function Produit(nom, region, saison, agriculteur, note, stock, prix, offre) {
    //on veut créer un id unique pour chaque produit
    this.id = Date.now() + Math.random();
    this.nom = nom;
    this.region = region;
    this.saison = saison;
    this.agriculteur = agriculteur;
    //convertir la note et le stock en entiers
    this.note = parseInt(note);
    this.stock = parseInt(stock);
    //convertir le prix en float
    this.prix = parseFloat(prix);
    //si l'offre n'est pas fournie on met false par défaut
    this.offre = offre || false;

    // C'est une méthode pour afficher la note simplement (ex: "Note: 4/5")
    this.getNoteTexte = function() {
        return ` Note: ${this.note}/5`;
    };
    // Méthode qui retourne le texte de l'offre s'il existe
    this.getOffreTexte = function() {
        if (this.offre) return ` Offre spéciale -${this.offre}% sur 2kg`;
        return "";
    };
}

// La collecte des données des produits
let produits = [
    new Produit("Miel", "Kef", "Printemps/Été", "Miel Boumiza", 4, 45, 25, false),
    new Produit("Huile d'olive", "Sfax", "Automne/Hiver", "Coopérative Errayhan", 5, 120, 18, false),
    new Produit("Harissa", "Sfax", "Toute l'année", "Ferme Châabane", 5, 80, 12, false),
    new Produit("Deglet Nour", "Tozeur", "Automne", "Ferme El Bey", 5, 200, 15, 25),
    new Produit("Figues de barbarie", "Zaghouan", "Été", "Jardin de Montagne", 4, 60, 8, false),
    new Produit("Oeufs", "Nabeul", "Toute l'année", "Plusieurs producteurs", 4, 500, 5, false),
    new Produit("Menthe fraîche", "Nabeul", "Toute l'année", "Plusieurs producteurs", 4, 150, 3, false),
    new Produit("Pommes", "Jendouba", "Automne", "Ferme El Khir", 5, 90, 4.5, false),
    new Produit("Amandes", "Sidi Bouzid", "Automne", "Coopérative El Amel", 5, 75, 22, 15)
];
//on fait une copie dans produitsOriginal pour le retrouver plus tard après les changements appliqués

let produitsOriginal = [...produits];

// Regrouppement par région
function grouperParRegion(produitsList) {
    const groupes = [];
    let i = 0;
    while (i < produitsList.length) {
        let groupe = { region: produitsList[i].region, produits: [] };
        while (i < produitsList.length && produitsList[i].region === groupe.region) {
            //tant qu'il reste des produits 
            groupe.produits.push(produitsList[i]);
            i++;
        }//une fois qu'on a fini de regrouper les produits de la même région, on ajoute le groupe au tableau des groupes
        groupes.push(groupe);
    }
    return groupes;
}

// Affichage du tableau
function afficherTableau() {
    //on récupère le corps du tableau (tbody) pour pouvoir y ajouter les lignes dynamiquement
    const tbody = document.getElementById('table-body');
    if (!tbody) return;
    //on vide le tableau avant de le remplir à nouveau pour éviter les doublons
    tbody.innerHTML = '';

    const groupes = grouperParRegion(produits);

    for (let g = 0; g < groupes.length; g++) {
        const groupe = groupes[g];
        const produitsRegion = groupe.produits;
        const nbProduits = produitsRegion.length;

        for (let p = 0; p < nbProduits; p++) {
            const produit = produitsRegion[p];
            const ligne = document.createElement('tr');
            ligne.className = 'ligne-produit';

            // Produit
            const tdNom = document.createElement('td');
            tdNom.className = 'nom-produit';
            const nomImage = produit.nom.toLowerCase().replace(/ /g, '_');
            //on veut afficher une icône à côté du nom du produit si l'image existe, sinon on affiche juste le nom
            //on suppose que les images sont nommées avec le nom du produit en minuscule et sans espaces 
            //onerror est un événement qui se déclenche lorsque l'image ne peut pas être chargée, dans ce cas l'image est invisible
            // "none" va cacher l'image si elle n'existe pas"
            
            tdNom.innerHTML = `<img src="image/${nomImage}.jpg" class="icone-produit" onerror="this.style.display='none'"> ${produit.nom}`;
            ligne.appendChild(tdNom);

            // Région (rowspan)
            if (p === 0) {
                const tdRegion = document.createElement('td');
                if (nbProduits > 1) tdRegion.setAttribute('rowspan', nbProduits);
                tdRegion.textContent = produit.region;
                ligne.appendChild(tdRegion);
            }

            // Saison
            const tdSaison = document.createElement('td');
            tdSaison.textContent = produit.saison;
            ligne.appendChild(tdSaison);

            // Agriculteur
            const tdAgriculteur = document.createElement('td');
            tdAgriculteur.textContent = produit.agriculteur;
            ligne.appendChild(tdAgriculteur);

            // Note / Prix / Stock
            const tdInfos = document.createElement('td');
            let offreHtml = produit.getOffreTexte() ? `<div class="offre-badge">${produit.getOffreTexte()}</div>` : '';
            if (produit.offre) tdInfos.setAttribute('colspan', '2');
            tdInfos.innerHTML = `
                <div class="product-details">
                    <div class="note-simple">${produit.getNoteTexte()}</div>
                    <div class="product-price"> ${produit.prix} DT</div>
                    <div class="product-stock"> Stock: ${produit.stock}</div>
                    ${offreHtml}
                </div>
            `;
            ligne.appendChild(tdInfos);

            tbody.appendChild(ligne);
        }
    }
}

// ajouter un produit
function ajouterProduit(nom, region, saison, agriculteur, note, stock, prix, offre) {
    if (!nom || !region || !saison || !agriculteur) {
        alert("Veuillez remplir tous les champs !");
        return false;
    }
    const nouveau = new Produit(nom, region, saison, agriculteur, note, stock, prix, offre);
    produits.push(nouveau);
    //localeCompare est une fonction prédéfinie qui compare deux chaînes de caractères 
    //elle retourne -1 si a < b, 0 si a == b et 1 si a > b
    produits.sort((a, b) => a.region.localeCompare(b.region));
    // on veut regrouper les produits par région pour pouvoir les fussionner dans le tableau avec rowspan
    //on utilise la méthode spread pour créer une vraie copie
    produitsOriginal = [...produits];
    afficherTableau();
    alert(` "${nom}" ajouté avec succès !`);
    return true;
}

function ajouterDepuisFormulaire() {
    const select = document.getElementById('produit-select');
    const nom = select.value;
    if (!nom) { alert("Choisissez un produit !"); return; }

    const note = parseInt(document.getElementById('note-produit').value);
    const prix = parseFloat(document.getElementById('prix-produit').value);

    const produitsInfo = {
        "Miel": { region: "Kef", saison: "Printemps/Été", agriculteur: "Miel Boumiza" },
        "Huile d'olive": { region: "Sfax", saison: "Automne/Hiver", agriculteur: "Coopérative Errayhan" },
        "Harissa": { region: "Sfax", saison: "Toute l'année", agriculteur: "Ferme Châabane" },
        "Deglet Nour": { region: "Tozeur", saison: "Automne", agriculteur: "Ferme El Bey" },
        "Figues de barbarie": { region: "Zaghouan", saison: "Été", agriculteur: "Jardin de Montagne" },
        "Oeufs": { region: "Nabeul", saison: "Toute l'année", agriculteur: "Plusieurs producteurs" },
        "Menthe fraîche": { region: "Nabeul", saison: "Toute l'année", agriculteur: "Plusieurs producteurs" },
        "Pommes": { region: "Jendouba", saison: "Automne", agriculteur: "Ferme El Khir" },
        "Amandes": { region: "Sidi Bouzid", saison: "Automne", agriculteur: "Coopérative El Amel" }
    };
    const info = produitsInfo[nom];
    if (info) {
        ajouterProduit(nom, info.region, info.saison, info.agriculteur, note, 50, prix, false);
    }
    select.value = "";
    document.getElementById('note-produit').value = 4;
    document.getElementById('prix-produit').value = 10;
}

//rechercher des produits
function rechercherProduits(critere, valeur) {
    let resultats = [];
    
    for (let produit of produitsOriginal) {
        switch(critere) {
            case "nom":
                //si le critère est le nom, on vérifie si le nom du produit contient la valeur recherchée (en ignorant la casse)
                //dans ce cas on ajoute le produit aux résultats
                if (produit.nom.toLowerCase().includes(valeur.toLowerCase())) {
                    resultats.push(produit);
                }
                break;
            case "region":
                if (produit.region.toLowerCase().includes(valeur.toLowerCase())) {
                    resultats.push(produit);
                }
                break;
            case "noteMin":
                //parseInt pour convertir la valeur en entier car les notes sont des entiers
                if (produit.note >= parseInt(valeur)) {
                    resultats.push(produit);
                }
                break;
            case "prixMax":
                //parseFloat pour convertir la valeur en décimal
                if (produit.prix <= parseFloat(valeur)) {
                    resultats.push(produit);
                }
                break;
            default:
                resultats.push(produit);  // Tous les produits
                break;
        }
    }
    
    return resultats;
}
function afficherRecherche() {
    const critere = document.getElementById('recherche-critere').value;
    const valeur = document.getElementById('recherche-valeur').value;
    if (!valeur && critere !== 'noteMin' && critere !== 'prixMax') { 
        alert("Entrez une valeur"); 
        return; 
    }
    const resultats = rechercherProduits(critere, valeur);
    if (resultats.length === 0) { 
        alert("Aucun produit trouvé"); 
        return; 
    }
    produits = [...resultats].sort((a, b) => a.region.localeCompare(b.region));
    afficherTableau();
    const msg = document.createElement('div');
    msg.className = 'feedback-message';
    msg.textContent = `🔍 ${resultats.length} produit(s) trouvé(s)`;
    const container = document.querySelector('.conteneur-tableau');
    container.insertBefore(msg, container.firstChild);
    setTimeout(() => msg.remove(), 3000);
}

function afficherTous() {
    produits = [...produitsOriginal];
    afficherTableau();
    alert(" Affichage de tous les produits");
}

//initialisation du tableau avec les produits de départ
document.addEventListener('DOMContentLoaded', function() {
    // Sauvegarder une copie
    produitsOriginal = [];
    for (let i = 0; i < produits.length; i++) {
        produitsOriginal.push(produits[i]);
    }
    
    // Afficher le tableau
    afficherTableau();
    
    // Ajouter les événements
    const btnAjouter = document.getElementById('btn-ajouter');
    if (btnAjouter) {
        btnAjouter.addEventListener('click', ajouterDepuisFormulaire);
    }
    
    const btnRechercher = document.getElementById('btn-rechercher');
    if (btnRechercher) {
        btnRechercher.addEventListener('click', afficherRecherche);
    }
    
    const btnAfficherTous = document.getElementById('btn-afficher-tous');
    if (btnAfficherTous) {
        btnAfficherTous.addEventListener('click', afficherTous);
    }
});