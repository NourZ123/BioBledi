<?php
session_start();
require "../PHP/database_connection.php";
if (isset($_POST["connexion"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $user_type = isset($_POST["user_type"]) ? $_POST["user_type"] : "client";
    $table = ($user_type === "agriculteur") ? "agriculteur" : "client";
    $query = $db->prepare("SELECT * FROM $table WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch();
    if (!$user) {
        header("Location: ../html/bienvenue.html?error=email");
        exit();
    } else {
        if ($user['Password'] === $password) {
            $_SESSION['user_data'] = $user; 
            $_SESSION['type'] = $user_type; 
            if ($user_type === "agriculteur") {
                header("Location: ../PHP/agriculteur.php");
            } else {
                header("Location: ../PHP/fruits et légumes.php");
            }
            exit();
        } else {
            header("Location: ../html/bienvenue.html?error=pass");
            exit();
        }
    }
}
?>

