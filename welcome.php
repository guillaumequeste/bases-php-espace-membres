<?php
// Initialisation de la session
session_start();
 
// Vérifier si l'utilisateur est connecté, si non, le rediriger vers la page login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Salut <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bienvenue sur notre site.</h1>
    </div>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Réinitialiser votre mot de passe</a>
        <a href="logout.php" class="btn btn-danger">Se déconecter</a>
    </p>
</body>
</html>