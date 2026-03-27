let formulaire = document.getElementById("formulaire-avis");
formulaire.addEventListener("submit", function (event) {
  let nom = document.getElementById("nom");
  let email = document.getElementById("mail");
  let prixRadio = document.getElementsByName("prix");
  let notesRadio = document.getElementsByName("note"); // Appréciation globale
  let champsdate = document.getElementById("date");
  let dateactuelle = new Date();
  let dateSaisie = new Date(champsdate.value);
  let dateMinimum = new Date("2025-01-01");
  let message = document.getElementById("msg");
  let alpha = /^[a-zA-Z ]+$/;
  if (
    nom.value === "" ||
    email.value === "" ||
    champsdate.value === "" ||
    message.value === ""
  ) {
    alert(
      "Veuillez remplir tous les champs obligatoires (Nom, Email, Date et Suggestions)."
    );
    event.preventDefault();
    return;
  }

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
      "Veuillez saisir une date valide (à partir de 2025 et jusqu'à aujourd'hui)."
    );
    event.preventDefault();
  }
});
