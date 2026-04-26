<?php
session_start();
require "../PHP/database_connection.php";

if (isset($_POST["signupbtn"]))
{
    $nom=$_POST['nom'] ?? '';
    $email=$_POST['email'] ?? '';
    $adress=$_POST['adress'] ?? '';
    $password=$_POST['password'] ?? '';
    $type=$_POST['type'] ?? '';
    $phone=$_POST['phone'] ?? '';
    $prename=$_POST['prename'] ?? '';

$erreurs = [];

//  VALIDATION
if (!preg_match("/^[a-zA-ZÀ-ÿ\s\-]{2,255}$/", $nom)) {
    $erreurs['nom'] = "Le nom est invalide (lettres uniquement, max 255).";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    $erreurs['email'] = "L'adresse email est invalide ou trop longue.";
}
if (!preg_match("/^[a-zA-Z0-9À-ÿ\s,.\-]{5,255}$/", $adress)) {
    $erreurs['adress'] = "L'adresse contient des caractères interdits ou est hors limite.";
}
if (!preg_match("/^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?\":{}|<>]).{8,255}$/", $password)) {
    $erreurs['password'] = "Le mot de passe doit faire 8 carct. min, inclure une majuscule et un caractère spécial.";
}
if (empty($type)) {
    $erreurs['type'] = "Veuillez choisir votre profil (Client ou Agriculteur).";
}
if (!preg_match("/^[0-9]{8}$/", $phone)) {
    $erreurs['phone'] = "Le téléphone doit contenir exactement 8 chiffres.";
}
if (!preg_match("/^[a-zA-ZÀ-ÿ\s\-]{2,255}$/", $prename)) {
    $erreurs['prename'] = "Le prénom est invalide (lettres uniquement, max 255).";
}
if (!empty($erreurs)) {
    $_SESSION['mes_erreurs'] = $erreurs;
    $_SESSION['anciennes_valeurs'] = $_POST; // Pour ne pas vider le formulaire
    header("Location: inscription.php");
} else {
    unset($_SESSION['mes_erreurs']);
    
if($type=="particulier"){
    $requete = $db->prepare("INSERT INTO client (Nom, Prénom, Email, Telephone, Adresse, Password) 
   VALUES (?, ?, ?, ?, ?, ?)");
}
else {
    $requete = $db->prepare("INSERT INTO agriculteur (Nom, Prénom, Email, Telephone, Adresse, Password) 
   VALUES (?, ?, ?, ?, ?, ?)");
}
$requete->execute([$nom, $prename, $email,$phone,$adress,$password]);
header("Location: ../fruits et légumes/fruits et légumes.php");
    exit();
}
}

?>