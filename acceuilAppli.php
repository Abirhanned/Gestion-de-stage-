<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez vers la page de connexion
if (!isset($_SESSION['user_login'])) {
    header('Location: identification.php');
    exit();
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Stage BTS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bienvenue, <?= $_SESSION['user_login']; ?>!</h1>
    <p>Vous êtes connecté(e) en tant que <?= $_SESSION['user_role']; ?>.</p>
   

	<div class="contenu">
		<nav class="nav flex-column">
			<a href="acceuilAppli.php" class="clic">  <img src="icons/home.png" alt="logo" width="30" height="24"  >
            Accueil</a>
			<a href="EntrepriseAppli.php"> <img src="icons/entreprise.png" alt="logo" width="30" height="24"  >
            Entrepeise</a>
			<a href="stagaireAppli.php"> <img src="icons/stage.png" alt="logo" width="30" height="24"  >
            Stagaire</a>
			<a href="InscriptionAppli.php"> <img src="icons/inscrire.png" alt="logo" width="30" height="24"  >
            Inscription</a>
			<a href="aideAppli.php"> <img src="icons/aide.png" alt="logo" width="30" height="24"  >
            Aide</a>
			<a href="deconnexion.php"> <img src="icons/deconnexion.png" alt="logo" width="30" height="24" >
            Deconnexion</a>
		</nav>
			
		<article>
        <h1>Stage BTS</h1>
        <p>Bienvenue sur la page de gestion des stages  <p>
        <hr>
        </article>
        
