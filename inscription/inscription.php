<?php

$host = 'sql112.infinityfree.com';
$dbname = 'if0_41693542_biobledi';
$user = 'if0_41693542';
$password = 'm885hRfjbJ0YAt';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

if (isset($_POST["signupbtn"]))
{
    $nom=$_POST['nom'];
    $email=$_POST['email'];
    $adress=$_POST['adress'];
    $password=$_POST['password'];
    $type=$_POST['type'];
    $phone=$_POST['phone'];
    $prename=$_POST['prename'];
if($type=="particulier"){
    $requete = $db->prepare("INSERT INTO client (Nom, Prénom, Email, Telephone, Adresse, Password) 
   VALUES (?, ?, ?, ?, ?, ?)");
}
else {
    $requete = $db->prepare("INSERT INTO agriculteur (Nom, Prénom, Email, Telephone, Adresse, Password) 
   VALUES (?, ?, ?, ?, ?, ?)");
}
$requete->execute([$nom, $prename, $email,$phone,$adress,$password]);
header("Location: ../fruits et légumes/fruits et légumes.html");
    exit();
}
?>