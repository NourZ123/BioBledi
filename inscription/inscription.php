<?php
require "../database_connection.php";

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
header("Location: ../fruits et légumes/fruits et légumes.php");
    exit();
}
?>