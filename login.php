<?php
// Initialisation de la session
session_start();
 
// Vérifier si l'utilisateur est déjà connecté, si oui le rediriger vers la page de bienvenue
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Inclure le fichier config
require_once "config.php";
 
// Definir les variables and les initialiser avec des valeurs vides
$username = $password = "";
$username_err = $password_err = "";
 
// Traitement du formulaire lorsqu'il est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Vérifier si l'username est vide
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Vérifier si le mot de passe est vide
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valider les champs
    if(empty($username_err) && empty($password_err)){
        // Préparer un select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Mettre les variables en paramètres au statement préparé
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Essayer d'exécuter le statement préparé
            if(mysqli_stmt_execute($stmt)){
                // Stocker le résultat
                mysqli_stmt_store_result($stmt);
                
                // Vérifier si le nom d'utilisateur existe, si oui vérifier le mot de passe
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Le mot de passe est correct, on démarre une nouvelle session
                            session_start();
                            
                            // Stocker les données dans des variables de session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Rediriger l'utilisaateur vers la page de bienvenue
                            header("location: welcome.php");
                        } else{
                            // Afficher un meesage d'erreur si le mot de passe n'est pas valide
                            $password_err = "Le mot de passe entré n'est pas valide";
                        }
                    }
                } else{
                    // Afficher un meesage d'erreur si le nom d'utilisateur n'existe pas
                    $username_err = "NPas de compte trouvé pour ce nom d'utilisateur";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Clore le statement
        mysqli_stmt_close($stmt);
    }
    
    // Clore la connecxion
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Connexion</h2>
        <p>Veuillez remplir les champs pour vous connecter.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Vous n'avez pas de compte ? <a href="registration.php">S'inscrire maintenant.</a>.</p>
        </form>
    </div>    
</body>
</html>