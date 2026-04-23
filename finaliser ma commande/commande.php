<?php 
    session_start();
    require_once '../database_connection.php';

    if (isset($_SESSION['user_data']))
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    {
      $adresse = "";
    $status = "en attente";
    if (isset($_POST['ADomicile']))
    {$adresse=$_SESSION['user_data']['Adresse'];}
    elseif(isset($_POST['PR']))
    {$adresse=$_POST['adress'];}
    elseif(isset($_POST['surplace']))
    {$adresse='retirer de la ferme';}
    
    if (isset($_POST['card']) && isset($_POST['nom']) && isset($_POST['cvc']) && isset($_POST['dateexp']) && isset($_POST['cardnumber']) )
    {
      $status='payé';
    }
    elseif (isset($_POST['cash']))
    $status='impayé';
    $id = $_SESSION['user_data']['ID'];
    $montant=$_SESSION['totalàpayer'];
    if (!empty($id) && $montant > 0) {
$sql = "INSERT INTO commande (montant, adresse, id_client, statut) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->execute([$montant, $adresse, $id, $status]);
header("Location: ../compte Client/compte.php");
if (isset($_SESSION['panier'])) {
    unset($_SESSION['panier']); 
}
        exit();
}
    }
    }

      ?>