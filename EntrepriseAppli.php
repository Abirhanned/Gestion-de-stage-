<?php  
session_start();

if (!isset($_SESSION['user_role'])) {
    echo "Erreur : Vous devez être connecté pour accéder à cette page.";
    exit();
}

require_once('connexion.php');  

// Liste des colonnes possibles, avec 'adresse' inclus
$toutesColonnes = [
    'raison_sociale' => 'Entreprise',
    'nom_contact' => 'Nom du contact',
    'nom_resp' => 'Responsable',
    'tel_entreprise' => 'Téléphone',
    'fax_entreprise' => 'Fax',
    'email' => 'Email',
    'site_entreprise' => 'Site',
    'niveau' => 'Niveau',
    'en_activite' => 'En activité',
    'adresse' => 'Adresse' , // Colonne "adresse" incluse
    'specialite' => 'Spécialité'
];




// Si la session ne contient pas encore de colonnes affichées, on définit un jeu de colonnes par défaut
if (!isset($_SESSION['colonnes_affichees'])) {
    $_SESSION['colonnes_affichees'] = ['raison_sociale', 'nom_resp', 'adresse', 'site_entreprise']; // Adresse déjà incluse
}

$colonnesAffichees = $_SESSION['colonnes_affichees'];

// Gestion de l'ajout ou suppression de colonnes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_colonne'])) {
    $nouvelleColonne = $_POST['ajouter_colonne'];
    if (!in_array($nouvelleColonne, $colonnesAffichees)) {
        $colonnesAffichees[] = $nouvelleColonne;
        $_SESSION['colonnes_affichees'] = $colonnesAffichees;
    }
}

if (isset($_GET['supprimer_colonne']) && in_array($_GET['supprimer_colonne'], $colonnesAffichees)) {
    $colonneASupprimer = $_GET['supprimer_colonne'];
    $colonnesAffichees = array_diff($colonnesAffichees, [$colonneASupprimer]);
    $_SESSION['colonnes_affichees'] = $colonnesAffichees;
}


$colonnesDisponibles = array_diff(array_keys($toutesColonnes), $colonnesAffichees);

// Construction de la chaîne SQL pour récupérer les colonnes affichées
$colonnesSQLString = implode(', ', $colonnesAffichees);

// Construction de la requête SQL dynamique
$sql = "SELECT entreprise.num_entreprise, 
               entreprise.raison_sociale, 
               entreprise.nom_contact, 
               entreprise.nom_resp, 
               entreprise.tel_entreprise, 
               entreprise.fax_entreprise, 
               entreprise.email, 
               entreprise.site_entreprise, 
               entreprise.niveau, 
               entreprise.en_activite, 
               entreprise.adresse,
               specialite.libelle AS specialite
        FROM entreprise
        LEFT JOIN spec_entreprise ON entreprise.num_entreprise = spec_entreprise.num_entreprise
        LEFT JOIN specialite ON spec_entreprise.num_spec = specialite.num_spec";

try {
    $query = $db->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!-- Le reste de votre code HTML -->


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Stage BTS - Entreprise</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; }
        table { border-collapse: collapse; width: 100%; border: 2px solid blue; }
        th, td { padding: 15px; text-align: left; border: 1px solid blue; }
        th { background-color: #d0e7ff; color: #001f5b; }
        td { background-color: #f9f9f9; }
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
            <h2>Liste des entreprises</h2>
            <form method="POST" action="">
                <label for="colonne">Ajouter une colonne :</label>
                <select name="ajouter_colonne" id="colonne">
                    <?php foreach ($colonnesDisponibles as $key): ?>
                        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($toutesColonnes[$key]) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Ajouter</button>
            </form>
            <table>
    <thead>
        <tr>
            <th>Opérations</th>
            <?php foreach ($colonnesAffichees as $colonne): ?>
                <th>
                    <?= htmlspecialchars($toutesColonnes[$colonne]) ?>
                    <a href="?supprimer_colonne=<?= urlencode($colonne) ?>" style="color: blue; text-decoration: none;">[-]</a>
                </th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (count($result) > 0): ?>
            <?php foreach ($result as $row): ?>
                <tr>
                    <!-- Cellule des actions -->
                    <td class="action-buttons">
                        <a href="InscriptionAppli.php"><img src="icons/inscrire.png" alt="Inscrire" width="30" height="24"></a>
                        <a href="details.php?id=<?= urlencode($row['num_entreprise']); ?>"><img src="icons/voir.png" alt="Voir" width="30" height="24"></a>
                        
                        <?php if ($_SESSION['user_role'] === 'professeur'): ?>
                            <a href="modifier.php?id=<?= urlencode($row['num_entreprise']); ?>"><img src="icons/modifier.png" alt="Modifier" width="30" height="24"></a>
                            <a href="supprimer.php?id=<?= urlencode($row['num_entreprise']); ?>"><img src="icons/supprimer.png" alt="Supprimer" width="30" height="24"></a>
                        <?php endif; ?> 
                    </td>

                    <!-- Autres colonnes dynamiques -->
                    <?php foreach ($colonnesAffichees as $colonne): ?>
                        <td>
                            <?php 
                                // Vérifie si la colonne est 'specialite' et affiche le libellé
                                if ($colonne === 'specialite') {
                                    echo isset($row['specialite']) ? htmlspecialchars($row['specialite']) : 'Non renseigné';
                                } else {
                                    // Affiche le contenu des autres colonnes
                                    echo isset($row[$colonne]) ? htmlspecialchars($row[$colonne]) : 'Non renseigné';
                                }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= count($colonnesAffichees) + 1 ?>">Aucune entreprise trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</tbody>
        </article>
    </div>
</body>
</html>
