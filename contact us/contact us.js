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
