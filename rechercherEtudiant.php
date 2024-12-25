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
$num_etudiant = isset($_GET['num_etudiant']) ? trim($_GET['num_etudiant']) : '';
$annee_obtention = isset($_GET['annee_obtention']) ? trim($_GET['annee_obtention']) : '';
$num_classe = isset($_GET['num_classe']) ? trim($_GET['num_classe']) : '';
$en_activite = isset($_GET['en_activite']) ? trim($_GET['en_activite']) : '';

// Vérifier si une recherche a été effectuée
$conditions = [];
$params = [];
if (!empty($num_etudiant) || !empty($annee_obtention) || !empty($num_classe) || !empty($en_activite)) {
    $searchPerformed = true; // Une recherche a été effectuée
    if (!empty($num_etudiant)) {
        $conditions[] = 'etudiant.num_etudiant = :num_etudiant';  // Utilisez "=" au lieu de "LIKE"
        $params[':num_etudiant'] = $num_etudiant;  // Ne pas ajouter "%" si c'est une recherche exacte
    }
    
    if (!empty($annee_obtention)) {
        $conditions[] = 'etudiant.annee_obtention LIKE :annee_obtention';
        $params[':annee_obtention'] = '%' . $annee_obtention . '%';
    }
    
    if (!empty($num_classe)) {
        $conditions[] = 'etudiant.num_classe = :num_classe';  // Utilisez "=" pour les champs numériques
        $params[':num_classe'] = $num_classe;  // Recherche exacte pour les numéros de classe
    }
    
    if (!empty($en_activite)) {
        $conditions[] = 'etudiant.en_activite LIKE :en_activite';
        $params[':en_activite'] = '%' . $en_activite . '%';
    }
    

    // Construire la requête SQL
    try {


        $sql = 'SELECT etudiant.num_etudiant, etudiant.nom_etudiant, etudiant.prenom_etudiant, professeur.nom_prof, professeur.prenom_prof, GROUP_CONCAT(entreprise.raison_sociale) AS entreprise
        FROM etudiant
        LEFT JOIN stage ON stage.num_etudiant = etudiant.num_etudiant
        LEFT JOIN entreprise ON entreprise.num_entreprise = stage.num_entreprise
        LEFT JOIN professeur ON professeur.num_prof = stage.num_prof';
        
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
   // Ajouter le GROUP BY à la fin
   $sql .= ' GROUP BY etudiant.num_etudiant';
   
        $query = $db->prepare($sql);
        $query->execute($params);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
// Pour afficher les données retournées par la requête

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit();
    }
}
try {
    // Requête SQL avec table intermédiaire
    $sql1 = 'SELECT *
FROM etudiant';
    
    $query1 = $db->prepare($sql1);
    $query1->execute();
    $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
try {
    // Requête SQL avec table intermédiaire
    $sql2 = 'SELECT DISTINCT annee_obtention
FROM etudiant';
    
    $query2 = $db->prepare($sql2);
    $query2->execute();
    $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}

try {
    // Requête SQL avec table intermédiaire
    $sql3 = 'SELECT DISTINCT num_classe
FROM etudiant';
    
    $query3 = $db->prepare($sql3);
    $query3->execute();
    $result3 = $query3->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
try {
    // Requête SQL avec table intermédiaire
    $sql4 = 'SELECT DISTINCT etudiant.en_activite
FROM etudiant';
    
    $query4 = $db->prepare($sql4);
    $query4->execute();
    $result4 = $query4->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Rechercher un étudiant</title>
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
            <a href="acceuilAppli.php" >  
                <img src="icons/home.png" alt="logo" width="30" height="24"> Accueil
            </a>
            <a href="EntrepriseAppli.php"> 
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
            <a href="developper.php"> 
                <img src="icons/droite.png" alt="logo" width="30" height="24"> Développer
            </a>
            <a href="reduire.php"> 
                <img src="icons/gauche.png" alt="logo" width="30" height="24"> Réduire
            </a>
        </nav>

        <article>
            <h1>Rechercher un stagiaire</h1>
            
            <form action="rechercherEtudiant.php" method="GET">
    <label for="num_etudiant">Identité :</label>
    <select id="num_etudiant" name="num_etudiant">
        <option value="">Sélectionner un étudiant</option>  <!-- Option vide par défaut -->
        <?php
        if (!empty($result1)) {
            foreach ($result1 as $row) {
                $selected = ($row["num_etudiant"] == $num_etudiant) ? 'selected' : '';  // Ajouter "selected" si c'est la valeur actuelle
                echo "<option value='" . $row["num_etudiant"] . "' $selected>" . $row["num_etudiant"] . "</option>";
            }
        } else {
            echo "<option value=''>Aucune option disponible</option>";
        }
        ?>
    </select>

    <br>
    <label for="annee_obtention">Année d'obtention :</label>
    <select id="annee_obtention" name="annee_obtention">
        <option value="">Sélectionner l'année</option>  <!-- Option vide par défaut -->
        <?php
        if (!empty($result2)) {
            foreach ($result2 as $row) {
                $selected = ($row["annee_obtention"] == $annee_obtention) ? 'selected' : '';  // Ajouter "selected" si c'est la valeur actuelle
                echo "<option value='" . $row["annee_obtention"] . "' $selected>" . $row["annee_obtention"] . "</option>";
            }
        } else {
            echo "<option value=''>Aucune option disponible</option>";
        }
        ?>
    </select>

    <br>
    <label for="num_classe">Numéro de classe :</label>
    <select id="num_classe" name="num_classe">
        <option value="">Sélectionner la classe</option>  <!-- Option vide par défaut -->
        <?php
        if (!empty($result3)) {
            foreach ($result3 as $row) {
                $selected = ($row["num_classe"] == $num_classe) ? 'selected' : '';  // Ajouter "selected" si c'est la valeur actuelle
                echo "<option value='" . $row["num_classe"] . "' $selected>" . $row["num_classe"] . "</option>";
            }
        } else {
            echo "<option value=''>Aucune option disponible</option>";
        }
        ?>
    </select>

    <br>
    <label for="en_activite">En activité :</label>
    <select id="en_activite" name="en_activite">
        <option value="">Sélectionner un statut</option>  <!-- Option vide par défaut -->
        <?php
        if (!empty($result4)) {
            foreach ($result4 as $row) {
                $selected = ($row["en_activite"] == $en_activite) ? 'selected' : '';  // Ajouter "selected" si c'est la valeur actuelle
                echo "<option value='" . $row["en_activite"] . "' $selected>" . $row["en_activite"] . "</option>";
            }
        } else {
            echo "<option value=''>Aucune option disponible</option>";
        }
        ?>
    </select>

    <br>
    <button type="submit">Rechercher</button>
</form>



            <hr>
            <div class="content">
            <h2>Résultats de la recherche</h2>
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

                                <a href="voirEtudiant.php?id=<?= urlencode($row['num_etudiant']); ?>"><img src="icons/voir.png" alt="logo" width="30" height="24" ></a>
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
                            <td colspan="5">Aucun étudiant trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
       
    </div>

            <a href="stagaireAppli.php">Retour à la liste des étudiants</a>
        </article>
    </div>
</body>
</html>
