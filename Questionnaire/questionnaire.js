let formulaire = document.getElementById("formulaire-avis");
formulaire.addEventListener("submit", function (event) {
  let nom = document.getElementById("nom");
  let email = document.getElementById("mail");
  let champsdate = document.getElementById("date");
  let dateactuelle = new Date();
  let dateSaisie = new Date(champsdate.value);
  let dateMinimum = new Date("2026-02-01");
  let alpha = /^[a-zA-Z ]+$/;
  if (!alpha.test(nom.value)) {
    alert("Veuillez saisir un nom valide");
    event.preventDefault();
  }
  if (!email.value.includes("@")) {
    alert("Veuillez saisir une adresse mail valide");
    event.preventDefault();
  }
  if (dateSaisie < dateMinimum || dateSaisie > dateactuelle) {
    alert(
      "Veuillez saisir une date valide (à partir de 2026 et jusqu'à aujourd'hui)."
    );
    event.preventDefault();
  }
});
