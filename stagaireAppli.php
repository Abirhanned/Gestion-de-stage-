<?php  
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_role'])) {
    echo "Erreur : Vous devez être connecté pour accéder à cette page.";
    exit();
}

// Inclure la connexion à la base
require_once('connexion.php');  

try {
    // Requête SQL avec table intermédiaire
    $sql = 'SELECT etudiant.num_etudiant, etudiant.nom_etudiant, etudiant.prenom_etudiant, professeur.nom_prof, professeur.prenom_prof, GROUP_CONCAT(entreprise.raison_sociale) AS entreprise
FROM etudiant
LEFT JOIN stage ON stage.num_etudiant = etudiant.num_etudiant
LEFT JOIN entreprise ON entreprise.num_entreprise = stage.num_entreprise
LEFT JOIN professeur ON professeur.num_prof = stage.num_prof
GROUP BY num_etudiant';


    
    $query = $db->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Stage BTS - Entreprise</title>
    <style>
      body {
    font-family: Arial, Helvetica, sans-serif;
}

.container {
    border: 1px solid #ccc;
    padding: 10px;
    background-color: #e0e0e0;
}

table {
    border-collapse: collapse;
    width: 100%;
    border: 2px blue; /* Bordure de la table en bleu */
}

th, td {
    padding: 15px;
    text-align: left;
    border: 1px solid blue; /* Bordure des cellules en bleu */
}

th {
    background-color: #d0e7ff; /* Fond bleu clair pour les en-têtes (optionnel) */
    color: #001f5b; /* Texte en bleu foncé */
}

td {
    background-color: #f9f9f9; /* Fond gris clair pour les cellules (optionnel) */
}

.action-buttons img {
    filter: invert(20%) sepia(70%) saturate(500%) hue-rotate(190deg) brightness(100%) contrast(90%);
}
    </style>
</head>
<body>
    <div class="contenu">
        <nav class="nav flex-column">
            <a href="acceuilAppli.php">
                <img src="icons/home.png" alt="logo" width="30" height="24"> Accueil
            </a>
            <a href="EntrepriseAppli.php" >
                <img src="icons/entreprise.png" alt="logo" width="30" height="24"> Entreprise
            </a>
            <a href="stagaireAppli.php" class="clic">
                <img src="icons/stage.png" alt="logo" width="30" height="24"> Stagiaire
            </a>
            <a href="InscriptionAppli.php">
                <img src="icons/inscrire.png" alt="logo" width="30" height="24"> Inscription
            </a>
            <a href="aideAppli.php">
                <img src="icons/aide.png" alt="logo" width="30" height="24"> Aide
            </a>
            <a href="deconnexion.php">
                <img src="icons/deconnexion.png" alt="logo" width="30" height="24"> Déconnexion
            </a>
        </nav>
        <article>
            <a href="rechercherEtudiant.php">
                <button type="button" class="btn btn-outline-primary">
                    <img src="icons/rechercher.png" alt="logo" width="30" height="24"> Rechercher un stagiaire existant
                </button>
            </a>
            <a href="ajouterEtudiant.php">
                <button type="button" class="btn btn-outline-primary">
                    <img src="icons/ajouter.png" alt="logo" width="30" height="24"> Ajouter un étudiant
                </button>
            </a>
            <hr>

            <div class="content">
            <h2>Liste des étudiants</h2>
            <table>
                <thead>
                    <tr>
                        <th>Opérations</th>
                        <th>Etudiant</th>
                        <th>Entreprises</th>
                        <th>Professeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($result) > 0): ?>
                        <?php foreach ($result as $row): ?>
                            <tr>
                            <td class="action-buttons">

                                <a href="voirEtudiant.php?id=<?= urlencode($row['num_etudiant']); ?>"><img src="icons/voir.png" alt="logo" width="30" height="24"></a>
                                <?php if ($_SESSION['user_role'] === 'professeur'): ?>
                                    <a href="modifierEtudiant.php?id=<?= urlencode($row['num_etudiant']); ?>"><img src="icons/modifier.png" alt="logo" width="30" height="24"></a>
                                    <a href="supprimerEtudiant.php?id=<?= urlencode($row['num_etudiant']); ?>"><img src="icons/supprimer.png" alt="logo" width="30" height="24"></a>
                               
                                    <?php endif; ?>
                                <td><?= htmlspecialchars($row['nom_etudiant']. ' ' . $row['prenom_etudiant']); ?></td>
                                <td><?= htmlspecialchars($row['entreprise']); ?></td>
                                <td><?= htmlspecialchars($row['nom_prof'] . ' ' . $row['prenom_prof']); ?></td>
                               </tr>                           
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Aucune entreprise trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </article>
    </div>
</body>
</html>
