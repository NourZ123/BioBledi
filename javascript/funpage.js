// Les données fixes

// Ici on crée un  tableau de tous les légumes qu'on va utiliser dans le jeu
const liste_des_legumes = [
  // Chaque légume est un objet avec 3 informations
  //lors de la récolte les points seront ajoutés au score
  {
    nom: "Fraise",
    image: "../images/food-strawberry-svgrepo-com.svg",
    points: 15,
  },
  {
    nom: "Carotte",
    image: "../images/carrot-salad-vegetables-svgrepo-com.svg",
    points: 12,
  },
  {
    nom: "Piment",
    image: "../images/chili-fruit-spicy-svgrepo-com.svg",
    points: 20,
  },
  { nom: "Pomme", image: "../images/apples-food-svgrepo-com.svg", points: 10 },
  {
    nom: "Aubergine",
    image: "../images/aubergine-svgrepo-com.svg",
    points: 12,
  },
  { nom: "Orange", image: "../images/orange-svgrepo-com.svg", points: 10 },
];

//Les variables
let meilleurScore = 0; // Stocke le meilleur score de la session
let jeuactif = false; // cette variable indique si le jeu est en cours ou non
let scoretotal = 0; // le nombre total de points gagnés
let legumesRecoltes = 0; // nombre de légumes  récoltés
let legumesmanques = 0; // nombre de légumes tombés sans être récoltés
let tempsRestant = 40; // temps restant en secondes (initialisé à 40 secondes)

// Ces variables servent à gérer les "timers" (des horloges)
//par défaut une variable avec let est undefined ;pour lui affecter une valeur je l'ai initialisé par null
let timerPrincipal = null; // Timer qui compte les secondes
let timerinitialisation = null; // Timer qui crée de nouveaux légumes
let listeAnimations = []; // Liste de toutes les animations de chute
let difficulteActuelle = "normal"; // Niveau de difficulté choisi

// on définit l'objet reglagesdifficulte qui nous informe sur le degré de difficulté du jeu
// Plus le temps entre les apparitions successives diminue plus le degré de difficulté augmente
//plus le degré de difficulté augmente plus les points augmentent
const reglagesDifficulte = {
  // Pour chaque niveau, on a 3 paramètres :
  // tempsentreApparitions : combien de temps avant qu'un nouveau légume apparaisse
  // vitesseChute : combien de temps met le légume pour toucher le bas
  // multiplicateurPoints : plus c'est grand, plus on gagne de points
  lent: {
    tempsEntreApparitions: 1200, // 1.2 secondes entre chaque légume
    vitesseChute: 2000, // 2 secondes pour tomber
    multiplicateurPoints: 0.8, // Points × 0.8
  },
  normal: {
    tempsEntreApparitions: 800, // 0.8 secondes entre chaque légume
    vitesseChute: 1500, // 1.5 secondes pour tomber
    multiplicateurPoints: 1, // Points × 1
  },
  rapide: {
    tempsEntreApparitions: 500, // 0.5 secondes entre chaque légume
    vitesseChute: 1000, // 1 seconde pour tomber
    multiplicateurPoints: 1.3, // Points × 1.3
  },
  extreme: {
    tempsEntreApparitions: 300, // 0.3 secondes entre chaque légume
    vitesseChute: 600, // 0.6 seconde pour tomber
    multiplicateurPoints: 1.8, // Points × 1.8
  },
};

// Affiche un message qui disparaît après 1.5 secondes à travers setTimeout

function afficherMessage(message, type) {
  //afficher le message dans la zone qui a l'id feedbackMessage
  const zoneMessage = document.getElementById("feedbackMessage");
  //Choisir la bonne image selon le type de message
  let icone = "";
  if (type === "success") {
    // Pour les succès (quand on attrape un légume)
    icone = `<img src="../images/success.svg" class="message-icon" alt="succès"> `;
  } else if (type === "error") {
    // Pour les erreurs (quand un légume tombe)
    icone = `<img src="../images/error.svg" class="message-icon" alt="erreur"> `;
  } else if (type === "info") {
    // Pour les informations (bienvenue, changement de difficulté)
    icone = `<img src="/images/info.svg" class="message-icon" alt="info"> `;
  }

  // Mettre l'icône et le message ensemble dans zoneMessage( la balise qui a l'id 'feedbackMessage')
  zoneMessage.innerHTML = icone + message;
  //className permet de changer l'attribut class
  //donc on ajoute la classe css pour changer la couleur du fond
  zoneMessage.className = `feedback-message ${type}`;

  //  Programmer la disparition du message après 1.5 secondes
  setTimeout(function () {
    zoneMessage.innerHTML = "";
    zoneMessage.className = "feedback-message";
  }, 1500);
}

/**
 * Met à jour tous les chiffres affichés à l'écran
 */
function mettreAJourAffichage() {
  // On trouve chaque élément par son "id" et on change le texte
  document.getElementById("score").textContent = scoretotal; // Le score
  document.getElementById("collected").textContent = legumesRecoltes; // Les récoltes
  document.getElementById("missed").textContent = legumesmanques; // Les manqués
  document.getElementById("timer").textContent = tempsRestant; // Le temps
  mettreAJourScoresPersonnels();
}

// reinitialisation de tous les variables

function reinitialiserJeu() {
  // Arrêter tous les timers : Si le timer existe, on l'arrête
  if (timerPrincipal) {
    clearInterval(timerPrincipal);
    timerPrincipal = null;
  }
  if (timerinitialisation) {
    clearInterval(timerinitialisation);
    timerinitialisation = null;
  }
  // On arrête toutes les chutes des légumes
  for (let i = 0; i < listeAnimations.length; i++) {
    clearInterval(listeAnimations[i]);
  }
  listeAnimations = []; // On vide la liste

  // initialiser les variables
  scoretotal = 0;
  legumesRecoltes = 0;
  legumesmanques = 0;
  tempsRestant = 45;
  jeuactif = false;
  //supprimer tous les légumes dans la zone de jeu
  const conteneur = document.getElementById("vegetablesContainer");
  if (conteneur) {
    conteneur.innerHTML = ""; // vider tout le contenu
  }

  mettreAJourAffichage();

  // Effacer le message de fin de partie s'il existe puisqu'on va commencer dès le début
  const messageFin = document.getElementById("gameOverMessage");
  if (messageFin) {
    messageFin.innerHTML = "";
  }
}

// fonction pour faire tomber un légume
function faireTomberLegume(legume) {
  // Trouver le conteneur où mettre le légume
  const conteneur = document.getElementById("vegetablesContainer");
  if (!conteneur) return; // Si le conteneur n'existe pas, on arrête

  // récupérer le degré de difficulté choisi
  const reglages = reglagesDifficulte[difficulteActuelle];
  // on récupére le nombre de points lié à ce légume
  // et on le multiple par le multiplicateur de difficulté du niveau choisi

  // on utilise Math.round() pour obtenir l'arrondi afin d'avoir une valeur approchée du score à la fin
  const pointsgagnes = Math.round(
    legume.points * reglages.multiplicateurPoints
  );

  //  Créer un nouvel élément HTML pour le légume : on crée une nouvelle balise <div>
  const elementLegume = document.createElement("div");
  // On lui donne la classe CSS "vegetable"
  elementLegume.className = "vegetable";

  // On ajoute des attributs pour stocker les informations
  elementLegume.setAttribute("data-nom", legume.nom);
  elementLegume.setAttribute("data-points", pointsgagnes);

  // Choisir une position horizontale aléatoire à partir de laquelle va descendre le légume
  // offsetWidth donne la largeur du conteneur
  const largeurConteneur = conteneur.offsetWidth;
  // On calcule la position maximum (pour que le légume ne sorte pas)
  const positionMax = Math.max(largeurConteneur - 80, 0);
  // Math.random() donne un nombre entre 0 et 1 qu'on multiplie par positionMax
  // afin d'obtenir une postion dans la zone de jeu
  const positionGauche = Math.random() * positionMax;
  elementLegume.style.left = positionGauche + "px";
  elementLegume.style.top = "0px"; // On commence tout en haut : 0px depuis le haut de la zone de jeu

  //  Ajouter l'image et le nom du légume à l'intérieur
  elementLegume.innerHTML = `
        <img src="../images/${legume.image}" class="vegetable-img" alt="${legume.nom}">
        <div class="vegetable-name">${legume.nom}</div>
    `;

  // ce qui se passe quand on clique sur ce légume
  elementLegume.onclick = function (event) {
    // event.stopPropagation() empêche l'événement de remonter
    event.stopPropagation();
    // On appelle la fonction qui gère la récolte
    recolterLegume(event, elementLegume);
  };

  // Ajouter le légume dans le conteneur (zone de jeu)
  conteneur.appendChild(elementLegume);

  // Faire l'animation de chute
  const tempsDepart = Date.now(); // On note l'heure exacte du début
  const hauteurDepart = 0;
  const hauteurArrivee = conteneur.offsetHeight - 90; // Où s'arrêter (en bas)

  // setInterval exécute du code toutes les 16 millisecondes
  const animationChute = setInterval(function () {
    // Si le jeu n'est plus actif ou si le légume a disparu, on arrête l'animation
    if (!jeuactif || !elementLegume.parentNode) {
      clearInterval(animationChute);
      return;
    }

    // Calculer combien de temps s'est écoulé
    const tempsEcoule = Date.now() - tempsDepart;
    // Calculer la progression (0 = début, 1 = fin)
    const progression = Math.min(tempsEcoule / reglages.vitesseChute, 1);
    // Calculer la nouvelle position
    const nouvellePosition =
      hauteurDepart + (hauteurArrivee - hauteurDepart) * progression;
    elementLegume.style.top = nouvellePosition + "px";

    // Si la chute est terminée (progression = 1)
    if (progression >= 1) {
      clearInterval(animationChute); // Arrêter l'animation
      if (elementLegume.parentNode) {
        elementLegume.remove(); // Supprimer le légume
        if (jeuactif) {
          // Si le jeu est actif, c'est un légume manqué
          legumesmanques = legumesmanques + 1;
          mettreAJourAffichage();
          afficherMessage(` ${legume.nom} est tombé !`, "error");
        }
      }
    }
  }, 16); // 16 ms

  //on range chaque minuteur à travers son id dans une liste pour qu'on puisse le récupérer plus tard
  // et supprimer tous les minuteurs avec une boucle à la fin de la partie
  listeAnimations.push(animationChute);
  // On attache aussi l'animation à l'élément lui-même
  // chaque minuteur est lié à l'animation elle meme.
  // Lorsque l'utilisateur reussit à récolter un légume on doit arreter uniquement le minuteur de ce légume
  elementLegume.animationChute = animationChute;
}

// fonction pour récolter un légume
function recolterLegume(evenement, elementLegume) {
  // une fois récolté , il faut empêcher l'événement (qui est la chute du légume) de se propager
  evenement.stopPropagation();

  // il faut vérifier si le jeu est actif
  if (!jeuactif) {
    return;
  }

  //Récupérer les informations du légume
  const points = parseInt(elementLegume.getAttribute("data-points"));
  const nom = elementLegume.getAttribute("data-nom");

  // Ajouter les points et compter la récolte
  scoretotal = scoretotal + points;
  legumesRecoltes = legumesRecoltes + 1;

  // Vérifier si on a battu le meilleur score
  if (scoretotal > meilleurScore) {
    meilleurScore = scoretotal;
    afficherMessage(` NOUVEAU RECORD ! ${scoretotal} points ! `, "success");
  }

  // Mettre à jour l'affichage
  mettreAJourAffichage();

  // Arrêter l'animation de chute
  if (elementLegume.animationChute) {
    clearInterval(elementLegume.animationChute);
  }

  // Supprimer le légume
  elementLegume.remove();

  //  Afficher un message de succès
  afficherMessage(`${points} pts ! ${nom} récolté !`, "success");
}
//fonction pour démarrer la génération des légumes
// Commence à créer des légumes qui tombent
function demarrerGenerationLegumes() {
  // Récupérer les réglages de la difficulté
  const reglages = reglagesDifficulte[difficulteActuelle];

  // setInterval va créer un nouveau légume toutes les X millisecondes
  timerinitialisation = setInterval(function () {
    // Vérifier que le jeu est actif
    if (!jeuactif) {
      return;
    }
    // Choisir un légume au hasard dans la liste
    // Math.random() donne un nombre entre 0 et 1
    // On le multiplie par le nombre de légumes (6) pour avoir un index entre 0 et 5
    const indexAleatoire = Math.floor(Math.random() * liste_des_legumes.length);
    const legumeChoisi = liste_des_legumes[indexAleatoire];
    // Faire tomber le légume
    faireTomberLegume(legumeChoisi);
  }, reglages.tempsEntreApparitions); // Attendre "tempsEntreApparitions" en ms entre chaque apparition
}

//fonction pour diminuer le temps restant de 1 seconde
//Vérifie si le temps est écoulé pour terminer la partie

function diminuerTemps() {
  // Si le jeu n'est pas actif, on ne fait rien
  if (!jeuactif) {
    return;
  }
  // Diminuer le temps de 1 seconde
  tempsRestant = tempsRestant - 1;
  // Mettre à jour l'affichage
  mettreAJourAffichage();
  // Si le temps est écoulé (0 ou moins)
  if (tempsRestant <= 0) {
    terminerPartie();
  }
}

// fonction pour terminer la partie et afficher le score final
function terminerPartie() {
  // Le jeu n'est plus actif
  jeuactif = false;

  // Arrêter tous les timers
  if (timerPrincipal) {
    clearInterval(timerPrincipal);
    timerPrincipal = null;
  }

  if (timerinitialisation) {
    clearInterval(timerinitialisation);
    timerinitialisation = null;
  }

  // Arrêter toutes les animations de chute
  for (let i = 0; i < listeAnimations.length; i++) {
    clearInterval(listeAnimations[i]);
  }
  listeAnimations = [];

  //Vider la zone de jeu
  const conteneur = document.getElementById("vegetablesContainer");
  if (conteneur) {
    conteneur.innerHTML = "";
  }

  // Afficher un message de fin
  const messageFin = document.getElementById("gameOverMessage");

  // afficher un message de félicitations
  let messageFelicitations = "";
  if (scoretotal >= 200) {
    messageFelicitations = " Champion ! Tu es un vrai agriculteur ! ";
  } else if (scoretotal >= 100) {
    messageFelicitations = " Bonne récolte ! Continue comme ça !";
  } else {
    messageFelicitations = " Entraîne-toi encore pour devenir un expert !";
  }
  // Ajouter un message spécial si on a battu le record
  let messageRecord = "";
  if (scoretotal === meilleurScore && scoretotal > 0) {
    messageRecord = " C'EST UN NOUVEAU RECORD ! ";
  }

  messageFin.innerHTML = `
        PARTIE TERMINÉE ! <br>
        Ton score : ${scoretotal} points<br>
        Produits récoltés : ${legumesRecoltes}<br>
        Produits manqués : ${legumesmanques}<br>
        ${messageFelicitations}
    `;

  // Afficher un message de feedback
  afficherMessage(
    `Partie terminée ! Score final : ${scoretotal} points`,
    "info"
  );
  //cette fonction servira à tester si le joueur est eligible à tourner la roue ou non
  function Eligible() {
    const message = document.getElementById("eligibilité");
    const container = document.getElementById("second-container");
    const total = legumesRecoltes + legumesmanques;
    const diff = document.getElementById("difficulty");
    const difficulté = diff.value;
    var minimum = 0;
    switch (difficulté) {
      case "slow":
        minimum = 0.4;
        break;
      case "normal":
        minimum = 0.3;
        break;
      case "fast":
        minimum = 0.2;
        break;
      case "crazy":
        minimum = 0.1;
        break;
    }
    if (total > 0) {
      const moy = legumesRecoltes / total;
      if (moy > minimum) {
        message.innerHTML =
          "Bravo vous êtes éligible à tenter votre chance avec la Roue Magique !";
        container.style.display = "block";
        const yOffset = -300;
        const y =
          container.getBoundingClientRect().top + window.pageYOffset + yOffset;
        container.scrollIntoView({
          top: y,
          behavior: "smooth",
          block: "start",
        });
      } else {
        container.style.display = "none";
        message.innerHTML =
          "Malheureusement vous n'êtes pas éligible à tenter votre chance avec la Roue Magique !";
      }
    } else {
      container.style.display = "none";
      message.innerHTML =
        "Malheureusement vous n'êtes pas éligible à tenter votre chance avec la Roue Magique !";
    }
  }
  Eligible();
}

/**
 * Met à jour l'affichage du score actuel et du meilleur score
 */
function mettreAJourScoresPersonnels() {
  const scoreActuelElement = document.getElementById("currentGameScore");
  const meilleurScoreElement = document.getElementById("bestScore");

  if (scoreActuelElement) {
    scoreActuelElement.textContent = scoretotal;
  }

  if (meilleurScoreElement) {
    meilleurScoreElement.textContent = meilleurScore;
  }
}
//pour démarrer une nouvelle partie
function demarrerNouvellePartie() {
  // Si une partie est déjà en cours, on ne fait rien
  if (jeuactif) {
    return;
  }
  // On remet tout à zéro
  reinitialiserJeu();
  // On active le jeu
  jeuactif = true;

  timerPrincipal = setInterval(diminuerTemps, 1000);
  // On démarre la création des légumes
  demarrerGenerationLegumes();
  // On affiche un message de bienvenue
  afficherMessage(
    " La pêche commence ! Attrape les fruits et légumes qui tombent !",
    "success"
  );
}
//Change le niveau de difficulté
function changerDifficulte() {
  const select = document.getElementById("difficulty");
  const nouvelleDifficulte = select.value;

  // On convertit le texte en anglais pour correspondre à notre objet
  if (nouvelleDifficulte === "slow") {
    difficulteActuelle = "lent";
  } else if (nouvelleDifficulte === "normal") {
    difficulteActuelle = "normal";
  } else if (nouvelleDifficulte === "fast") {
    difficulteActuelle = "rapide";
  } else if (nouvelleDifficulte === "crazy") {
    difficulteActuelle = "extreme";
  }

  // Si une partie est en cours, on la redémarre avec la nouvelle difficulté
  if (jeuactif) {
    terminerPartie();
    demarrerNouvellePartie();
  }

  // Afficher un message
  afficherMessage(
    `Difficulté : ${select.options[select.selectedIndex].text}`,
    "info"
  );
}

// reinitialisation du jeu complètement
function reinitialiserComplet() {
  // Si une partie est en cours, on la termine
  if (jeuactif) {
    terminerPartie();
  }
  // On remet tout à zéro
  reinitialiserJeu();
  // Réinitialiser le meilleur score puisqu'on initialise toute la session
  meilleurScore = 0;
  mettreAJourScoresPersonnels();
  // On affiche un message
  afficherMessage("Jeu réinitialisé ! Clique sur Démarrer pour jouer.", "info");
}
// initialisation de la page
// Cette fonction s'exécute quand la page est complètement chargée

function initialiserPage() {
  const boutonDemarrer = document.getElementById("startGame");
  const boutonReinitialiser = document.getElementById("resetGame");
  const selectDifficulte = document.getElementById("difficulty");

  //  Dire aux boutons quoi faire quand on clique dessus
  // addEventListener("click", fonction) = "quand on clique, exécute la fonction"
  if (boutonDemarrer) {
    boutonDemarrer.addEventListener("click", demarrerNouvellePartie);
  }

  if (boutonReinitialiser) {
    boutonReinitialiser.addEventListener("click", reinitialiserComplet);
  }
  if (selectDifficulte) {
    selectDifficulte.addEventListener("change", changerDifficulte);
  }
  //  Afficher un message de bienvenue
  afficherMessage(
    "Bienvenue ! Clique sur Démarrer pour commencer à pêcher !",
    "info"
  );
}
// document.addEventListener("DOMContentLoaded", fonction) =
// "Quand la page est chargée, exécute cette fonction"
//  On attend que la page soit chargée pour lancer initialiserPage
document.addEventListener("DOMContentLoaded", initialiserPage);

//Debut js pour la roue

const roue = document.getElementById("roue");
const button = document.getElementById("spin");
const result = document.getElementById("result");

const Cadeau = ["1", "2", "3", "4", "5", "6"];
const segmentAngle = 360 / Cadeau.length;

button.addEventListener("click", () => {
  const deg = Math.floor(2000 + Math.random() * 2000);
  roue.style.transform = `rotate(${deg}deg)`;

  setTimeout(() => {
    //on determine sur quel cadeau pointe la flèche en calculant le degré
    //de la tour qu'elle a fait la roue modulo 360degré
    const finalDeg = deg % 360;
    const index = Math.floor((360 - finalDeg) / segmentAngle) % Cadeau.length;
    let promo = "";
    // selon l'indice on trouve le cadeau
    switch (index + 1) {
      case 1:
        result.textContent =
          "Vous avez gagné une remise de-10% sur votre panier  !";
        promo = "10";
        break;
      case 2:
        result.textContent = "Tourner la roue une deuxième fois !";
        break;
      case 3:
        result.textContent =
          "Vous avez gagné une remise de -15% sur votre panier  !";
        promo = "15";

        break;
      case 4:
        result.textContent = "Pas de Chance cette fois-ci !  ";
        break;
      case 5:
        result.textContent =
          "Vous avez gagné une livraison gratuite pour tout achat supèrieur à 50DT!";
        promo = "free";

        break;
      case 6:
        result.textContent =
          "Vous avez gagné une remise de  -5% sur votre panier  !";
        promo = "5";

        break;
    }
    if (promo !== "") {
      fetch(`funpage.php?cadeau=${promo}`);
    }
    // la roue tourne pendant 4 seconde
  }, 4000);
});
