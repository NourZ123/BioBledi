let phone = document.getElementById("phone");
phone.addEventListener("input", function (e) {
  let digits = e.target.value.replace(/\D/g, "").substring(0, 8);
  let formatted = "";

  if (digits.length > 0) {
    formatted = digits.substring(0, 2);
    if (digits.length > 2) formatted += " " + digits.substring(2, 5);
    if (digits.length > 5) formatted += " " + digits.substring(5, 8);
  }
  e.target.value = formatted;
});
function afficherHeure() {
  let now = new Date();
  let date = now.toLocaleDateString("fr-Fr");
  let heure = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
  let logo = `<img src="image/logo.jpg" alt="logo" style="height:50px; vertical-align:middle; margin:0 8px; border-radius:20px;">`;
  const message = `${logo} Bienvenue au site web de  BioBladi !  Aujourd'hui est le ${date} et l'heure est ${heure}.`;
  document.getElementById("text").innerHTML = message;
}
setInterval(afficherHeure, 1000);
afficherHeure();
let lesitems = document.querySelectorAll(".item");
let indexEnCours = 0;

function faireTournerImages() {
  lesitems[indexEnCours].classList.remove("active");
  indexEnCours = (indexEnCours + 1) % lesitems.length;
  lesitems[indexEnCours].classList.add("active");
}
setInterval(faireTournerImages, 3000);
