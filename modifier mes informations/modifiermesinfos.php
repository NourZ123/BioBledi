<?php
session_start();
require "../PHP/database_connection.php"; 

$connexion = $_SESSION['user_data'] ?? null;

if ($connexion) {
    $user = $_SESSION['user_data'];
    $type = $_SESSION['type'];
    $id = $user['ID'];
    
    $email_nouv = trim($_POST['email'] ?? '');
    $adress_nouv = trim($_POST['adress'] ?? '');
    $phone_nouv = trim($_POST['phone'] ?? '');

    if (empty($email_nouv) && empty($adress_nouv) && empty($phone_nouv)) {
        header("Location: ../check_compte.php");
        exit();
    }

    $erreurs = [];

    if (!empty($email_nouv) && !filter_var($email_nouv, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "format_invalide";
    }

    if (!empty($phone_nouv) && !preg_match("/^[0-9\s]{8,15}$/", $phone_nouv)) {
        $erreurs['phone'] = "format_invalide";
    }

    if (!empty($erreurs)) {
        $_SESSION['erreurs_modif'] = $erreurs;
        header("Location: ../modifier mes informations/modifier.php?error=format");
        exit();
    }

    $table = ($type == "client") ? "client" : "agriculteur";

    if ($adress_nouv != "") {
        $stmt = $db->prepare("UPDATE $table SET Adresse = ? WHERE ID = ?");
        if ($stmt->execute([$adress_nouv, $id])) {
            $_SESSION['user_data']['Adresse'] = $adress_nouv;
        }
    }

    if ($phone_nouv != "") {
        $stmt = $db->prepare("UPDATE $table SET Telephone = ? WHERE ID = ?");
        if ($stmt->execute([$phone_nouv, $id])) {
            $_SESSION['user_data']['Telephone'] = $phone_nouv;
        }
    }

    if ($email_nouv != "") {
        $verif = $db->prepare("SELECT COUNT(*) FROM $table WHERE Email = ? AND ID != ?");
        $verif->execute([$email_nouv, $id]);
        
        if ($verif->fetchColumn() > 0) {
            header("Location: ../modifier mes informations/modifier.php?error=exist");
            exit();
        } else {
            $stmt = $db->prepare("UPDATE $table SET Email = ? WHERE ID = ?");
            if ($stmt->execute([$email_nouv, $id])) {
                $_SESSION['user_data']['Email'] = $email_nouv;
            }
        }
    }

    header("Location: ../check_compte.php?modif=ok");
    exit();
}
?>