<?php
session_start();
require "../database_connection.php"; 
$connexion= $_SESSION['user_data'] ?? null;
if ($connexion){
  $user = $_SESSION['user_data'];
  $type=$_SESSION['type'];
  $email_ancien=$user['Email'];

}
  ?>