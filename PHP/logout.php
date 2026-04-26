<?php
session_start();
session_destroy();
header("Location: ../se connecter/bienvenue.html");
exit();
?>