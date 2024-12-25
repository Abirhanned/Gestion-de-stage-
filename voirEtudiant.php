<?php
// Connexion à la base de données
require 'connexion.php';

// Récupérer l'ID depuis l'URL
$id = $_GET['id'] ?? null;

if (!$id) {
    // Rediriger si aucun ID n'est fourni
    header('Location: listeetudiants.php');
    exit();
}

// Requête pour obtenir les détails de l'etudiant
$query = $db->prepare('SELECT * FROM etudiant WHERE num_etudiant = :id');
$query->execute(['id' => $id]);
$etudiant = $query->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    echo "Etudiant introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Informations sur l'étudiant</title>
    <style>
    article {
    flex-grow: 1; /* Le contenu principal prend tout l'espace restant */
    background-color: #ffffff; /* Fond blanc */
    border: 1px solid #ccc; /* Bordure légère */
    border-radius: 8px; /* Coins arrondis */
    padding: 20px; /* Espacement intérieur */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Effet d'ombre */}
</style>
</head>
<body>

<div class="contenu">
		<nav class="nav flex-column">
			<a href="acceuilAppli.php" >  <img src="icons/home.png" alt="logo" width="30" height="24"  >
            Accueil</a>
			<a href="EntrepriseAppli.php" > <img src="icons/entreprise.png" alt="logo" width="30" height="24"  >
            Entrepeise</a>
			<a href="stagaireAppli.php" class="clic"> <img src="icons/stage.png" alt="logo" width="30" height="24"  >
            Stagaire</a>
			<a href="InscriptionAppli.php"> <img src="icons/inscrire.png" alt="logo" width="30" height="24"  >
            Inscription</a>
			<a href="aideAppli.php"> <img src="icons/aide.png" alt="logo" width="30" height="24"  >
            Aide</a>
			<a href="deconnexion.php"> <img src="icons/deconnexion.png" alt="logo" width="30" height="24" >
            Deconnexion</a>
		</nav>
        <article>		
        <div class="container">
        <h1><?= htmlspecialchars($etudiant['nom_etudiant']. ' ' . $etudiant['prenom_etudiant']); ?></h1>
        <hr>
        <div class="details">
            <div class="section">
                <h2>Identité</h2>
                <p><strong>Numéro de l'étudiant :</strong> <?= htmlspecialchars($etudiant['num_etudiant']); ?></p>
                <p><strong>Nom de l'étudiant :</strong> <?= htmlspecialchars($etudiant['nom_etudiant']); ?></p>
                <p><strong>Prénom de l'étudiant :</strong> <?= htmlspecialchars($etudiant['prenom_etudiant']); ?></p>
            </div>
            <div class="section">
                <h2>Identification</h2>
                <p><strong>Login :</strong> <?= htmlspecialchars($etudiant['login']); ?></p>
                <p><strong>Mot de passe :</strong> <?= htmlspecialchars($etudiant['mdp']); ?></p>
            </div>
            <div class="section">
                <h2>Scolarité</h2>
                <p><strong>Année d'obtention du diplome :</strong> <?= htmlspecialchars($etudiant['annee_obtention']); ?></p>
                <p><strong>Classe :</strong> <?= htmlspecialchars($etudiant['num_classe']); ?></p>
                <p><strong>En activité :</strong> <?= $etudiant['en_activite'] ? 'Oui' : 'Non'; ?></p>
            </div>
        </div>
        <div class="actions">
            <button onclick="history.back()">Retour</button>
        </div>
    </div>
</article>	
</body>
</html>
