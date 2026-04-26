<?php
session_start();
if (!isset($_SESSION['type'])) {
    header('Location: compte.php');
    exit();
}
if ($_SESSION['type'] === 'agriculteur') {
    header('Location: ../PHP/agriculteur.php');
} else {
    header('Location: ../PHP/compte.php');
}
exit();
?>