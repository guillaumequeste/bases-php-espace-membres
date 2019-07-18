<?php
// Initialiser la session
session_start();
 
// Vérifier si l'utilisateur est connecté, si non, le rediriger vers la page de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Inclure le fichier config
require_once "config.php";
 
// Définir les variables et les initialiser avec des valeurs vides
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Traitement du formulaire lorsqu'il est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Valider le nouveau mot de passe
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Veuillez entrer votre nouveau mot de passe.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Votre mot de passe doit contenir au moins 6 caractères.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Valider la confirmation du mot de passe
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer votre mot de passe.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }
        
    // Vérifier les erreurs avant de mettre à jour la base de données
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Préparer un update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Mettre les variables en paramètres au statement préparé
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Essayer d'exécuter le statement préparé
            if(mysqli_stmt_execute($stmt)){
                // Mot de passe mis à jour avec succès. Deétruire la session et rediriger vers la page login
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Clore le statement
        mysqli_stmt_close($stmt);
    }
    
    // Clore la connexion
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Réinitialisation du mot de passe</h2>
        <p>Veuillez remplir ce formulaire afin de réinitialiser votre mot de passe</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirmez le mot de passe</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Valider">
                <a class="btn btn-link" href="welcome.php">Annuler</a>
            </div>
        </form>
    </div>    
</body>
</html>