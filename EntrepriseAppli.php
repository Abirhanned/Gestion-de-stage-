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
    $sql = 'SELECT entreprise.raison_sociale, entreprise.nom_contact, entreprise.nom_resp, 
                   entreprise.rue_entreprise, entreprise.cp_entreprise, entreprise.ville_entreprise, 
                   entreprise.site_entreprise, specialite.libelle AS specialite
            FROM entreprise
            LEFT JOIN spec_entreprise ON entreprise.num_entreprise = spec_entreprise.num_entreprise
            LEFT JOIN specialite ON spec_entreprise.num_spec = specialite.num_spec';
    
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

    </style>
</head>
<body>
    
    <div class="contenu">
        <nav class="nav flex-column">
            <a href="acceuilAppli.php">
                <img src="icons/home.png" alt="logo" width="30" height="24"> Accueil
            </a>
            <a href="EntrepriseAppli.php" class="clic">
                <img src="icons/entreprise.png" alt="logo" width="30" height="24"> Entreprise
            </a>
            <a href="stagaireAppli.php">
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
            <a href="developper.php">
                <img src="icons/droite.png" alt="logo" width="30" height="24"> Développer
            </a>
            <a href="reduire.php">
                <img src="icons/gauche.png" alt="logo" width="30" height="24"> Réduire
            </a>
        </nav>
        <article>
            <a href="rechercher.php">
                <button type="button" class="btn btn-outline-primary">
                    <img src="icons/rechercher.png" alt="logo" width="30" height="24"> Rechercher une entreprise
                </button>
            </a>
            <a href="ajouter.php">
                <button type="button" class="btn btn-outline-primary">
                    <img src="icons/ajouter.png" alt="logo" width="30" height="24"> Ajouter une entreprise
                </button>
            </a>
            <hr>

            <div class="content">
                <div class="header">
                    <input type="text" placeholder="Rechercher une entreprise">
                    <button>Ajouter</button>
                </div>
            <h2>Liste des entreprises</h2>
            <table>
                <thead>
                    <tr>
                        <th>Opérations</th>
                        <th>Entreprise</th>
                        <th>Responsable</th>
                        <th>Adresse</th>
                        <th>Site</th>
                        <th>specialite</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($result) > 0): ?>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td class="action-buttons">
                                    <a href="details.php?id=<?= urlencode($row['raison_sociale']); ?>"><img src="icons/voir.png" alt="logo" width="30" height="24"></a>
                                    <?php if ($_SESSION['user_role'] === 'professeur'): ?>
                                        <a href="edit.php?id=<?= urlencode($row['raison_sociale']); ?>"><img src="icons/modifier.png" alt="logo" width="30" height="24"></a>
                                        <a href="delete.php?id=<?= urlencode($row['raison_sociale']); ?>"><img src="icons/supprimer.png" alt="logo" width="30" height="24"></a>
                                        <a href="add.php"><img src="icons/ajouter.png" alt="logo" width="30" height="24"></a>
                                    <?php elseif ($_SESSION['user_role'] === 'etudiant'): ?>
                                        <a href="InscriptionAppli.php"><img src="icons/inscrire.png" alt="logo" width="30" height="24"></a>
                                    <?php endif; ?>
                                <td><?= htmlspecialchars($row['raison_sociale']); ?></td>
                                <td><?= htmlspecialchars($row['nom_resp']); ?></td>
                                <td><?= htmlspecialchars($row['rue_entreprise'] . ', ' . $row['cp_entreprise'] . ' ' . $row['ville_entreprise']); ?></td>
                                <td><a href="<?= htmlspecialchars($row['site_entreprise']); ?>">Lien</a></td>
                                <td><?= htmlspecialchars($row['specialite'] ?: 'Aucune spécialité'); ?></td> <!-- Si aucune spécialité -->
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
