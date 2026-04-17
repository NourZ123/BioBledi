<?php

$host = 'localhost';
$dbname = 'biobledi';
$user = 'root';
$password = '';

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
/*if($type=="client"){*/
    $requete = $db->prepare("INSERT INTO client (Nom, Prénom, Email, Telephone, Adresse, Password) 
   VALUES (?, ?, ?, ?, ?, ?)");
$requete->execute([$nom, $prename, $email,$phone,$adress,$password]);
echo "Bravo, vous êtes inscrit !";
}
?>