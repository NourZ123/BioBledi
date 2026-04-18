<?php
session_start();
require "../database_connection.php";
$errEmail="";
$errPass="";
if (isset($_POST["connexion"])){
$email=$_POST["email"];
$password=$_POST["password"];
$query=$db->prepare("SELECT password from client where email= ?");
$query->execute([$email]);
$row=$query->fetch();
if(!$row)
{
    header("Location: bienvenue.html?error=email");
    exit();
   
}else{
    if($row['password'] === $password)
    {
    header("Location: ../fruits et légumes/fruits et légumes.html");
    exit();
    }else{
        header("Location: bienvenue.html?error=pass");
        exit();
    }
}
}
?>