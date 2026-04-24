<?php
session_start();
if (!isset($_SESSION['type'])) {
    header('Location: ../se connecter/bienvenue.html');
    exit();
}
if ($_SESSION['type'] === 'agriculteur') {
    header('Location: agriculteur/agriculteur.php');
} else {
    header('Location: compte Client/compte.php');
}
exit();
?>