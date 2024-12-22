<?php  
// On inclut la connexion à la base  
require_once('connexion.php');  

try {
    // Requête SQL pour récupérer les colonnes nécessaires
    $sql = 'SELECT raison_sociale, nom_contact, nom_resp, rue_entreprise, cp_entreprise, ville_entreprise, site_entreprise 
            FROM entreprise';
    $query = $db->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        // Parcours des résultats
    } else {
        echo "<tr><td colspan='6'>Aucune donnée trouvée.</td></tr>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Stage BTS</title>
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

        }
        th, td {
         padding: 15px;
         text-align: left;}

    </style>
</head>
<body>
    <header>
        <!-- Vous pouvez ajouter un en-tête ici si nécessaire -->
    </header>
 
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
                <table>
                    <thead>
                        <tr>
                            <th>Opérations</th>
                            <th>Entreprise</th>
                            <th>Responsable</th>
                            <th>Adresse</th>
                            <th>Site</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($result) > 0) {
                            // Parcours des résultats et affichage dans le tableau
                            foreach ($result as $row) {
                                echo "<tr>";
                                echo "<td class='action-buttons'>
                                        <button>Voir</button>
                                        <button>Modifier</button>
                                        <button>Supprimer</button>
                                      </td>";
                                echo "<td>" . htmlspecialchars($row['raison_sociale']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nom_resp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['rue_entreprise'] . ', ' . $row['cp_entreprise'] . ' ' . $row['ville_entreprise']) . "</td>";
                                echo "<td><a href='" . htmlspecialchars($row['site_entreprise']) . "'>Lien</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Aucune donnée trouvée.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </article>
    </div>
</body>
</html>
