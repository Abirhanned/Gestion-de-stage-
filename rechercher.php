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

// Initialisation des variables
$result = [];
$searchPerformed = false;

// Récupérer les valeurs des champs de recherche
$raison_sociale = isset($_GET['raison_sociale']) ? trim($_GET['raison_sociale']) : '';
$ville = isset($_GET['ville']) ? trim($_GET['ville']) : '';
$specialite = isset($_GET['specialite']) ? trim($_GET['specialite']) : '';

// Vérifier si une recherche a été effectuée
$conditions = [];
$params = [];
if (!empty($raison_sociale) || !empty($ville) || !empty($specialite)) {
    $searchPerformed = true; // Une recherche a été effectuée
    if (!empty($raison_sociale)) {
        $conditions[] = 'entreprise.raison_sociale LIKE :raison_sociale';
        $params[':raison_sociale'] = '%' . $raison_sociale . '%';
    }
    if (!empty($ville)) {
        $conditions[] = 'entreprise.ville_entreprise LIKE :ville';
        $params[':ville'] = '%' . $ville . '%';
    }
    if (!empty($specialite)) {
        $conditions[] = 'specialite.libelle LIKE :specialite';
        $params[':specialite'] = '%' . $specialite . '%';
    }

    // Construire la requête SQL
    try {
        $sql = 'SELECT entreprise.raison_sociale, entreprise.nom_contact, entreprise.nom_resp, 
                       entreprise.rue_entreprise, entreprise.cp_entreprise, entreprise.ville_entreprise, 
                       entreprise.site_entreprise, specialite.libelle AS specialite
                FROM entreprise
                LEFT JOIN spec_entreprise ON entreprise.num_entreprise = spec_entreprise.num_entreprise
                LEFT JOIN specialite ON spec_entreprise.num_spec = specialite.num_spec';
        
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query = $db->prepare($sql);
        $query->execute($params);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Rechercher une entreprise</title>
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
            border: 2px blue;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid blue;
        }

        th {
            background-color: #d0e7ff;
            color: #001f5b;
        }

        td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="contenu">
        <nav class="nav flex-column">
            <a href="acceuilAppli.php" >  
                <img src="icons/home.png" alt="logo" width="30" height="24"> Accueil
            </a>
            <a href="EntrepriseAppli.php"class="clic"> 
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
            <h1>Rechercher une entreprise</h1>
            <form action="rechercher.php" method="GET">
                <label for="raison_sociale">Nom de l'entreprise :</label>
                <input type="text" name="raison_sociale" id="raison_sociale" placeholder="Nom de l'entreprise" value="<?= htmlspecialchars($raison_sociale); ?>">
                
                <label for="ville">Ville :</label>
                <input type="text" name="ville" id="ville" placeholder="Ville" value="<?= htmlspecialchars($ville); ?>">
                
                <label for="specialite">Spécialité :</label>
                <input type="text" name="specialite" id="specialite" placeholder="Spécialité" value="<?= htmlspecialchars($specialite); ?>">
                
                <button type="submit">Rechercher</button>
            </form>

            <hr>

            <h2>Résultats de la recherche</h2>
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
                    </tr>
                </thead>
                <tbody>
                    <?php if ($searchPerformed && count($result) > 0): ?>
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
                                <td><?= htmlspecialchars($row['specialite'] ?: 'Aucune spécialité'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php elseif ($searchPerformed): ?>
                        <tr>
                            <td colspan="5">Aucun résultat trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Effectuez une recherche pour voir les résultats.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="EntrepriseAppli.php">Retour à la liste des entreprises</a>
        </article>
    </div>
</body>
</html>
