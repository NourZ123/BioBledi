<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Finaliser ma commande</title>

    <link rel="stylesheet" href="finaliser ma commande.css" />
    <link rel="icon" href="image/credit-card-svgrepo-com.svg" />
    <link rel="stylesheet" href="../code footer.css" />
    <link rel="stylesheet" href="../code css commun.css">
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        background: #f3f5f8;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }
    </style>
  </head>

  <body>
    <div class="head">
      <div class="head-left">
        <div>
          <img src="image/logo.jpg" alt="logo" class="logo" />
        </div>
        <div class="page-title-container">
          <p class="title">
            <strong>Payment</strong>
          </p>
        </div>

        <nav class="navigation">
          <a href="../index/index.html" class="menu-item">Accueil</a>
          <a href="../fruits et légumes/fruits et légumes.php" class="menu-item"
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
          <a href="../funpage/funpage.html" class="menu-item">Fun page</a>
        </nav>
      </div>
      <div class="head-right">
        <div class="head-actions">
          <a href="../compte Client/compte.php">
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

    <hr />

    <div class="page-container">
      <form action="commande.php" method="post" name="finaliser ma commande" id="form">
        <div class="card">
          <h1 class="page-title">Finaliser ma commande</h1>

          <div class="section">
            <h3>Mode de livraison</h3>
            <input type="hidden" name="choix_livraison" id="hidden_livraison" value="">
            <div class="choice-group">
              <button type="button" class="choice" id='adress' name='ADomicile'style="font-weight: bold; font-size: 16px; height: 90px">A domicile</button>
              <button type="button" class="choice" id='PR' name='PR' style="font-weight: bold; font-size: 16px; height: 90px">Point relais</button>
              <button type="button" class="choice" id='surplace' name='surplace' style="font-weight: bold; font-size: 16px; height: 90px">Retirer de la ferme</button>
            
            </div>
          </div>
          <div id="adr" style="display: none;"><div class="form-group">
            <label>Adresse</label>
            <input
              type="text"
              class="form-input"
              id="point_relais"
              placeholder="ex: 123 rue de la Paix"
              name="adress"
            />
            <span id="errAdress" class="error"></span>
          </div></div>
          <div class="section">
    
</div>

<div class="section">
    <h3>Mode de paiement</h3>
    <input type="hidden" name="choix_paiement" id="hidden_paiement" value="">
    <div class="choice-group">
        <button type="button"" name="mode_paiement" value="carte" class="choice payment" id="cardpayment" style="font-weight: bold;  font-size: 16px;">
            <img src="image/credit-card-svgrepo-com.svg" alt="card" /> Carte bancaire
        </button>
        <button type="button" name="mode_paiement" value="cash" class="choice payment" id="cash" style="font-weight: bold;  font-size: 16px;">
            <img src="image/truck-svgrepo-com.svg" alt="truck" /> A la livraison
        </button>
    </div>
</div>

          <div class="card-payment">
            <div class="form-group">
              <label>Titulaire</label>
              <input type="text" placeholder="Jean Dupont" id="nom" name="nom" />
              <span class="error" id="errNom"></span>
            </div>

            <div class="form-group">
              <label>Numéro de carte</label>
              <input
                type="text"
                id="card-number"
                placeholder="XXXX XXXX XXXX XXXX"
                length="16"
                name="cardnumber"
              />
              <span class="error" id="errCardNumber"></span>
            </div>

            <div class="row">
              <div class="form-group">
                <label>Date expiration</label>
                <input type="text" placeholder="MM/YY" id="expi-date" name="dateexp" />
                <span class="error" id="errDate"></span>
              </div>

              <div class="form-group">
                <label>CVC</label>
                <input
                  type="text"
                  placeholder="123"
                  maxlength="3"
                  minlength="3"
                  name="cvc"
                />
              </div>
            </div>
          </div>

          <div class="buttons">
            <a href="../mon panier/panier.php"
              ><button class="btn2">Retour</button></a
            >
            <a href="../compte Client/compte.php"><button class="btn1" name="btn1">Continuer</button></a>
          </div>
        </div>
      </form>
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
          <a href="../fruitsetlegumesfruits et légumes.php">Marché</a>
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
    <script>
  let form = document.getElementById("form");
  const date = document.getElementById("expi-date");
  let cash = document.getElementById("cash");
  let card = document.getElementById("cardpayment");
  let paymentform = document.getElementsByClassName("card-payment")[0];
  
  let hiddenLiv = document.getElementById("hidden_livraison");
  let hiddenPay = document.getElementById("hidden_paiement");

  form.addEventListener("submit", function (event) {
    let errorElements = document.getElementsByClassName("error");
    for(let el of errorElements) { el.innerHTML = ""; }
    
    let nom = document.getElementById("nom");
    let number = document.getElementById("card-number");
    let alpha = /^[a-zA-Z ]+$/;

    if (hiddenPay.value === "card") {
      if (nom.value.trim() === "" || number.value.trim() === "" || date.value.trim() === "") {
        alert("Veuillez remplir tous les champs de la carte bancaire.");
        event.preventDefault();
        return;
      }

      if (!alpha.test(nom.value)) {
        document.getElementById("errNom").innerHTML = "Veuillez saisir un nom valide.";
        event.preventDefault();
        return;
      }
    }
  });

  date.addEventListener("input", (event) => {
    let v = event.target.value.replace(/\D/g, "");
    if (v.length > 2) {
      event.target.value = v.substring(0, 2) + "/" + v.substring(2, 4);
    } else {
      event.target.value = v;
    }
  });

  date.addEventListener("blur", () => {
    const val = date.value;
    const errDate = document.getElementById("errDate");
    if (val.length === 5) {
      const [mSaisi, ySaisi] = val.split("/").map(Number);
      if (mSaisi < 1 || mSaisi > 12) {
        errDate.innerHTML = "Mois invalide";
        date.value = "";
        return;
      }
      const currentdate = new Date();
      const Cmonth = currentdate.getMonth() + 1;
      const Cyear = currentdate.getFullYear() % 100;
      if (ySaisi < Cyear || (ySaisi === Cyear && mSaisi < Cmonth)) {
        errDate.innerHTML = "Carte expirée !";
        date.value = "";
      }
    }
  });

  let numberInput = document.getElementById("card-number");
  numberInput.addEventListener("input", (e) => {
    let v = e.target.value.replace(/\D/g, "");
    if (v.length > 16) v = v.substring(0, 16);
    let formatted = v.match(/.{1,4}/g)?.join(" ") || "";
    e.target.value = formatted;
  });

  card.addEventListener("click", () => {
    hiddenPay.value = "card";
    paymentform.style.display = "block";
    card.style.border = "solid 2px #14532d";
    card.style.color = "#14532d";
    cash.style.border = "2px solid #E2E8F0";
    cash.style.color = "black";
  });

  cash.addEventListener("click", () => {
    hiddenPay.value = "cash";
    paymentform.style.display = "none";
    cash.style.border = "solid 2px #14532d";
    cash.style.color = "#14532d";
    card.style.border = "2px solid #E2E8F0";
    card.style.color = "black";
  });

  let adresse = document.getElementById('adress');
  let surplace = document.getElementById('surplace');
  let Pr = document.getElementById('PR');
  let adr_pointR = document.getElementById('adr');

  adresse.addEventListener("click", () => {
    hiddenLiv.value = "domicile";
    adresse.style.border = "solid 2px #14532d";
    adresse.style.color = "#14532d";
    [surplace, Pr].forEach(el => { el.style.border = "2px solid #E2E8F0"; el.style.color = "black"; });
    adr_pointR.style.display = "none";
  });

  Pr.addEventListener("click", () => {
    hiddenLiv.value = "PR";
    Pr.style.border = "solid 2px #14532d";
    Pr.style.color = "#14532d";
    [surplace, adresse].forEach(el => { el.style.border = "2px solid #E2E8F0"; el.style.color = "black"; });
    adr_pointR.style.display = "block";
  });

  surplace.addEventListener("click", () => {
    hiddenLiv.value = "surplace";
    surplace.style.border = "solid 2px #14532d";
    surplace.style.color = "#14532d";
    [adresse, Pr].forEach(el => { el.style.border = "2px solid #E2E8F0"; el.style.color = "black"; });
    adr_pointR.style.display = "none";
  });
</script>
  </body>
</html>
