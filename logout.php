<?php
// Initialiser la session
session_start();
 
// Vider toutes les variables de session
$_SESSION = array();
 
// Détruire la session.
session_destroy();
 
// Rediriger vers la page de login
header("location: login.php");
exit;
?>