<?php
// Nous avons besoin d'utiliser les sessions, toujours commencer les sessions en utilisant le code suivant :
session_start();
// Si l'utilisateur n'est pas connecté, le rediriger vers la page de login
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}
$DATABASE_HOST = 'localhost:8889';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'memberarea';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Nous n'avons pas le mot de passe ou l'email enregistré dans les sessions donc, à la place, nous pouvons récupérer les données de la base de données
$stmt = $con->prepare('SELECT username, password FROM users WHERE id = ?');
// Dans ce cas, nous pouvons utiliser l'ID pour obtenir les infos du compte
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($username, $password);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profil</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<div class="content">
			<h2>Profil</h2>
			<div>
				<p>Voici les détails de votre compte :</p>
				<table>
					<tr>
						<td>Nom d'utilisateur :</td>
						<td><?=$_SESSION['username']?></td>
					</tr>
					<tr>
						<td>Mot de passe :</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email :</td>
						<td><?=$email?></td>
					</tr>
				</table>
			</div>
        </div>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>
	</body>
</html>