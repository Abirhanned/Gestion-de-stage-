<?php
// Connexion à la base de données
require 'connexion.php';

// Récupérer l'ID depuis l'URL
$id = $_GET['id'] ?? null;

if (!$id) {
    // Rediriger si aucun ID n'est fourni
    header('Location: listeEntreprises.php');
    exit();
}

// Requête pour obtenir les détails de l'entreprise
$query = $db->prepare('SELECT * FROM entreprise WHERE num_entreprise = :id');
$query->execute(['id' => $id]);
$entreprise = $query->fetch(PDO::FETCH_ASSOC);

if (!$entreprise) {
    echo "Entreprise introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Détails de l'entreprise</title>
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
			<a href="EntrepriseAppli.php" class="clic"> <img src="icons/entreprise.png" alt="logo" width="30" height="24"  >
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
        <div class="container">
        <h1><?= htmlspecialchars($entreprise['raison_sociale']); ?></h1>
        <hr>
        <div class="details">
            <div class="section">
                <h2>Information</h2>
                <p><strong>Nom de l'entreprise :</strong> <?= htmlspecialchars($entreprise['raison_sociale']); ?></p>
                <p><strong>Nom du contact :</strong> <?= htmlspecialchars($entreprise['nom_contact']); ?></p>
                <p><strong>Nom du responsable :</strong> <?= htmlspecialchars($entreprise['nom_resp']); ?></p>
            </div>
            <div class="section">
                <h2>Contact</h2>
                <p><strong>Rue :</strong> <?= htmlspecialchars($entreprise['rue_entreprise']); ?></p>
                <p><strong>Code postal :</strong> <?= htmlspecialchars($entreprise['cp_entreprise']); ?></p>
                <p><strong>Ville :</strong> <?= htmlspecialchars($entreprise['ville_entreprise']); ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($entreprise['tel_entreprise']); ?></p>
                <p><strong>Fax :</strong> <?= htmlspecialchars($entreprise['fax_entreprise']); ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($entreprise['email']); ?></p>
            </div>
            <div class="section">
                <h2>Divers</h2>
                <p><strong>Observation :</strong> <?= htmlspecialchars($entreprise['observation']); ?></p>
                <p><strong>URL :</strong> <a href="<?= htmlspecialchars($entreprise['site_entreprise']); ?>" target="_blank"><?= htmlspecialchars($entreprise['site_entreprise']); ?></a></p>
                <p><strong>Niveau :</strong> <?= htmlspecialchars($entreprise['niveau']); ?></p>
                <p><strong>En activité :</strong> <?= $entreprise['en_activite'] ? 'Oui' : 'Non'; ?></p>
            </div>
        </div>
        <div class="actions">
            <button onclick="history.back()">Retour</button>
        </div>
    </div>
</article>	
</body>
</html>
