<?php
session_start();
session_destroy();
header("Location: ../html/bienvenue.html");
exit();
?>