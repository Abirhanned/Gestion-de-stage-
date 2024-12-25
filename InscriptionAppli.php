<?php
// Inclure la connexion à la base de données
require_once('connexion.php');

// Initialisation des variables
$result = $result1 = $result2 = [];

try {
    // Requêtes pour récupérer les données nécessaires
    $result = $db->query('SELECT num_etudiant, CONCAT(nom_etudiant, " ", prenom_etudiant) AS identite FROM etudiant')->fetchAll(PDO::FETCH_ASSOC);
    $result1 = $db->query('SELECT num_prof, CONCAT(nom_prof, " ", prenom_prof) AS identite FROM professeur')->fetchAll(PDO::FETCH_ASSOC);
    $result2 = $db->query('SELECT num_entreprise, raison_sociale FROM entreprise')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Vérification des champs obligatoires
    if (empty($_POST['num_entreprise']) || empty($_POST['num_etudiant']) || empty($_POST['num_prof']) ||
        empty($_POST['debut_stage']) || empty($_POST['fin_stage']) || empty($_POST['type_stage'])) {
        $errors[] = "Tous les champs marqués d'un astérisque (*) sont obligatoires.";
    }

    if (empty($errors)) {
        // Vérifier que la date de fin n'est pas avant la date de début
        $debut_stage = $_POST['debut_stage'];
        $fin_stage = $_POST['fin_stage'];
    
        if (strtotime($fin_stage) < strtotime($debut_stage)) {
            $errors[] = "La date de fin du stage ne peut pas être antérieure à la date de début.";
        }

    }

    // Si aucune erreur
    if (empty($errors)) {
        try {
            var_dump($_POST);
            // Préparer la requête SQL pour insérer les données dans la table "stage"
            $stmt = $db->prepare("INSERT INTO stage (num_entreprise, num_etudiant, num_prof, debut_stage, fin_stage, type_stage, desc_projet, observation_stage)
                                  VALUES (:num_entreprise, :num_etudiant, :num_prof, :debut_stage, :fin_stage, :type_stage, :desc_projet, :observation_stage)");

            // Exécuter la requête avec les données
            $stmt->execute([
                ':num_entreprise' => $_POST['num_entreprise'],
                ':num_etudiant' => $_POST['num_etudiant'],
                ':num_prof' => $_POST['num_prof'],
                ':debut_stage' => $_POST['debut_stage'],
                ':fin_stage' => $_POST['fin_stage'],
                ':type_stage' => $_POST['type_stage'],
                ':desc_projet' => $_POST['desc_projet'] ?? null,
                ':observation_stage' => $_POST['observation_stage'] ?? null,
            ]);

            // Message de succès
            $success = "Inscription réalisée avec succès !";
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}

require_once('cloose.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un étudiant</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Style global */
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #e0e0e0;
        }

        /* Style du formulaire */
        .formulaire {
            background: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .form-group label {
            width: 30%;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 68%;
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 3px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .btn-submit {
            background-color: #0066cc;
            color: #fff;
            font-size: 16px;
            padding: 8px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #005bb5;
        }

        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        .alert.success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .alert.error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
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
            <a href="stagaireAppli.php" >
                <img src="icons/stage.png" alt="logo" width="30" height="24"> Stagiaire
            </a>
            <a href="InscriptionAppli.php" class="clic">
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
            <div class="content">
                <div class="formulaire">
                    <h1>Informations concernant l'étudiant</h1>

                    <?php if (!empty($success)): ?>
                        <div class="alert success"><?= htmlspecialchars($success); ?></div>
                    <?php elseif (!empty($errors)): ?>
                        <div class="alert error"><?= implode('<br>', $errors); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="InscriptionAppli.php">
                        <h2>Contact</h2>
                        <div class="form-group">
                            <label for="num_entreprise">Entreprise* :</label>
                            <select id="num_entreprise" name="num_entreprise" required>
                                <option value="">Sélectionner l'entreprise</option>
                                <?php foreach ($result2 as $row): ?>
                                    <option value="<?= htmlspecialchars($row['num_entreprise']); ?>"><?= htmlspecialchars($row['raison_sociale']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="num_etudiant">Etudiant* :</label>
                            <select id="num_etudiant" name="num_etudiant" required>
                                <option value="">Sélectionner l'étudiant</option>
                                <?php foreach ($result as $row): ?>
                                    <option value="<?= htmlspecialchars($row['num_etudiant']); ?>"><?= htmlspecialchars($row['identite']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="num_prof">Professeur* :</label>
                            <select id="num_prof" name="num_prof" required>
                                <option value="">Sélectionner le professeur</option>
                                <?php foreach ($result1 as $row): ?>
                                    <option value="<?= htmlspecialchars($row['num_prof']); ?>"><?= htmlspecialchars($row['identite']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <h2>Description du stage</h2>
                        <div class="form-group">
                            <label for="debut_stage">Date de début du stage* :</label>
                            <input type="datetime-local" id="debut_stage" name="debut_stage" required>
                        </div>
                        <div class="form-group">
                            <label for="fin_stage">Date de fin du stage* :</label>
                            <input type="datetime-local" id="fin_stage" name="fin_stage" required>
                        </div>
                        <div class="form-group">
                            <label for="type_stage">Type de stage* :</label>
                            <select name="type_stage" id="type_stage" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="Stage">Stage</option>
                                <option value="Alternance">Alternance</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="desc_projet">Description du stage :</label>
                            <input type="text" id="desc_projet" name="desc_projet">
                        </div>
                        <div class="form-group">
                            <label for="observation_stage">Observation du stage :</label>
                            <input type="text" id="observation_stage" name="observation_stage">
                        </div>

                        <button type="submit" class="btn-submit">Inscrire</button>
                    </form>
                </div>
            </div>
        </article>
    </div>
</body>

</html>
