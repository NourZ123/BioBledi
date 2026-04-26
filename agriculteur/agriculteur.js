let form = document.getElementById("productForm");

form.addEventListener("submit", function (event) {
  let errorSpans = document.getElementsByClassName("error");
  for (let span of errorSpans) {
    span.innerHTML = "";
  }

  let nom = document.getElementById("nom-produit");
  let prix = document.getElementById("prix-produit");
  let stock = document.getElementById("stock-produit");
  let categorie = document.getElementById("categorie-produit");
  let offre = document.getElementById("offre-produit");
  let unite = document.getElementById("unite-produit");
  let region = document.getElementById("region-produit");
  let photo = document.getElementById("photo-produit");

  let alphaUnite = /^[\\[a-zA-ZÀ-ÿ\s]{1,255}$/;

  if (
    nom.value.trim() === "" ||
    prix.value.trim() === "" ||
    stock.value.trim() === "" ||
    categorie.value === "" ||
    unite.value.trim() === "" ||
    region.value === ""
  ) {
    alert("Veuillez remplir tous les champs *.");
    event.preventDefault();
    return;
  }
  let alphaNum = /^[a-zA-ZÀ-ÿ0-9\s\-]{2,255}$/;

  if (!alphaNum.test(nom.value)) {
    document.getElementById("errNom").innerHTML =
      "Nom invalide (lettres, chiffres et tirets uniquement).";
    event.preventDefault();
  }

  if (isNaN(prix.value) || parseFloat(prix.value) <= 0) {
    document.getElementById("errPix").innerHTML =
      "Le prix doit être supérieur à 0.";
    event.preventDefault();
  }

  if (!/^\d+$/.test(stock.value) || parseInt(stock.value) < 0) {
    document.getElementById("errStock").innerHTML =
      "La quantité doit être un nombre entier.";
    event.preventDefault();
  }

  if (offre.value !== "") {
    let valOffre = parseFloat(offre.value);
    if (valOffre < 0 || valOffre > 50) {
      document.getElementById("errOffre").innerHTML =
        "L'offre doit être entre 0% et 50%.";
      event.preventDefault();
    }
  }
  if (!alphaUnite.test(unite.value)) {
    document.getElementById("errUnite").innerHTML =
      "Unité invalide (lettres ou  autorisés).";
    event.preventDefault();
  }
  if (photo.files.length === 0) {
  } else {
    let file = photo.files[0];
    let extension = file.name.split(".").pop().toLowerCase();
    let allowed = ["jpg", "jpeg", "png", "webp"];

    if (!allowed.includes(extension)) {
      document.getElementById("errIMage").innerHTML =
        "Formats autorisés : JPG, PNG, WEBP.";
      event.preventDefault();
    }

    if (file.size > 5 * 1024 * 1024) {
      // 5 Mo
      document.getElementById("errIMage").innerHTML =
        "L'image est trop lourde (max 5 Mo).";
      event.preventDefault();
    }
  }
});

document
  .getElementById("photo-produit")
  .addEventListener("change", function (e) {
    if (this.files && this.files[0]) {
      let reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("preview-img").src = e.target.result;
        document.getElementById("uploadZone").style.display = "none";
        document.getElementById("preview-container").style.display = "block";
      };
      reader.readAsDataURL(this.files[0]);
    }
  });
