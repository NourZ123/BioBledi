<?php
session_start();
require "../PHP/database_connection.php"; 
$connexion= $_SESSION['user_data'] ?? null;
if ($connexion){
  $user = $_SESSION['user_data'];
  $type=$_SESSION['type'];
  $id=$user['ID'];
  $email_ancien=$user['Email'];
  $email_nouv=$_POST['email'];
  $adress_nouv=$_POST['adress'];
  $phone_nouv=$_POST['phone'];
  if($adress_nouv=="" && $phone_nouv=="" && $email_nouv=="")
  {
      header("Location: ../check_compte.php");
      exit();
  }else{
  if ($adress_nouv!="")
  {
    if($type=="client")
    {$req_adress="UPDATE client set Adresse=? where id=?";}
  else
     { $req_adress="UPDATE agriculteur set Adresse=? where id=?";}
  $instruction=$db->prepare($req_adress);
  $ok=$instruction->execute([$adress_nouv,$id]);
  if ($ok) {
    $_SESSION['user_data']['Adresse'] = $adress_nouv; 
}   
  }
  if ($phone_nouv!="")
  {
    if($type=="client")
    {$req_tel="UPDATE client set Telephone=? where ID=?";}
  else
     { $req_tel="UPDATE agriculteur set Telephone=? where ID=?";}
  $instruction=$db->prepare($req_tel);
  $ok=$instruction->execute([$phone_nouv,$id]); 
  if ($ok) {
    $_SESSION['user_data']['Telephone'] = $phone_nouv; 
}  
  }
  if ($email_nouv!="")
  {
    if($type=="client")
    {   $verif = "SELECT COUNT(*) FROM client WHERE Email = ? AND ID != ?";
        $instru = $db->prepare($verif);
        $instru->execute([$email_nouv, $id]);
        $existe= $instru->fetchColumn();
        if ($existe>0)
        {
            header("Location: ../modifier mes informations/modifier.html?error=exist");
        }
        else{
            $req_email="UPDATE client set Email=? where ID=?";
            $instruction=$db->prepare($req_email);
            $ok=$instruction->execute([$email_nouv,$id]);
            if ($ok) {
                $_SESSION['user_data']['Email'] = $email_nouv; 
            }  
        }
    }
  else
     {
        $verif = "SELECT COUNT(*) FROM agriculteur WHERE Email = ? AND ID != ?";
        $instru = $db->prepare($verif);
        $instru->execute([$email_nouv, $id]);
        $existe= $instru->fetchColumn(); 
        if($existe>0)
        {
            header("Location: ../modifier mes informations/modifier.html?error=exist");
        }
        else{
            $req_email="UPDATE agriculteur set Email=? where ID=?";
            $instruction=$db->prepare($req_email);
            $ok=$instruction->execute([$email_nouv,$id]);
            if ($ok) {
                $_SESSION['user_data']['Email'] = $email_nouv; 
            } 
        }
} 
  }
}
header("Location: ../check_compte.php?modif=ok");


}
  ?>