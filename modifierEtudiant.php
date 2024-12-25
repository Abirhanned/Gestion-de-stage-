<?php
// Inclure la connexion à la base de données
require_once('connexion.php');

// Vérifier si l'ID de l'entreprise est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de l'étudiant manquant.");
}

$etudiant_id = (int) $_GET['id'];

// Récupérer les informations de l'entreprise à modifier
try {
    $stmt = $db->prepare("SELECT * FROM etudiant WHERE num_etudiant = :id");
    $stmt->execute([':id' => $etudiant_id]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$etudiant) {
        die("Etudiant introuvable.");
    }


} catch (PDOException $e) {
    die("Erreur lors de la récupération des informations : " . $e->getMessage());
}



// Mise à jour des informations de l'entreprise
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des champs requis
    if (!empty($_POST['nom_etudiant']) &&
        !empty($_POST['prenom_etudiant']) &&
        !empty($_POST['login']) &&
        !empty($_POST['mdp']) &&
        !empty($_POST['num_classe'])
    ) {
        // Nettoyage des données pour éviter les failles XSS
        $nom_etudiant = htmlspecialchars(strip_tags($_POST['nom_etudiant']));
        $prenom_etudiant = htmlspecialchars(strip_tags($_POST['prenom_etudiant'] ?? ''));
        $login = htmlspecialchars(strip_tags($_POST['login'] ?? ''));
        $mdp = htmlspecialchars(strip_tags($_POST['mdp']));
        $annee_obtention = htmlspecialchars(strip_tags($_POST['annee_obtention']));
        $num_classe = htmlspecialchars(strip_tags($_POST['num_classe']));

        // Mise à jour des informations de l'entreprise
        try {
            $sql = "UPDATE etudiant SET
                        nom_etudiant = :nom_etudiant,
                        prenom_etudiant = :prenom_etudiant,
                        login = :login,
                        mdp = :mdp,
                        annee_obtention = :annee_obtention,
                        num_classe = :num_classe
                 
                    WHERE num_etudiant = :id";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom_etudiant' => $nom_etudiant,
                ':prenom_etudiant' => $prenom_etudiant,
                ':login' => $login,
                ':mdp' => $mdp,
                ':annee_obtention' => $annee_obtention,
                ':num_classe' => $num_classe,
                ':id' => $etudiant_id
            ]);

 

            $success = "Informations mises à jour avec succès !";
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    } else {
        $error = "Tous les champs requis (*) doivent être remplis.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .formulaire {
    flex: 1;
    background: #fff;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.section-titre {
    background-color: #0066cc;
    color: #fff;
    font-size: 16px;
    padding: 8px 10px;
    margin-bottom: 10px;
    border-radius: 3px;
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
    padding-right: 10px;
    color: #333;
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

textarea {
    resize: none;
    height: 60px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #0066cc;
}

.alert {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 3px;
    font-size: 14px;
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

.specialite-options {
    display: flex;
    flex-direction: column;
}

/* Bouton Ajouter */
.btn-submit {
    display: inline-block;
    margin-top: 10px;
    background-color: #0066cc;
    color: #fff;
    font-size: 16px;
    padding: 8px 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s ease;
}

.btn-submit:hover {
    background-color: #005bb5;
}

/* Note des champs obligatoires */
.note {
    margin-top: 10px;
    background-color: #d9fdd3;
    color: #3c763d;
    padding: 10px;
    border-radius: 3px;
    text-align: center;
    font-size: 14px;
    border: 1px solid #a9e2a9;
}
.specialite-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.specialite-option {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    font-weight: bold;
    color: #333;
}

.specialite-option input {
    margin-right: 5px;
    transform: scale(1.2); /* Agrandir légèrement les cases à cocher */
}

.specialite-options p {
    color: #666;
    font-style: italic;
}

    </style>
</style>
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
            <a href="developper.php">
                <img src="icons/droite.png" alt="logo" width="30" height="24"> Développer
            </a>
            <a href="reduire.php">
                <img src="icons/gauche.png" alt="logo" width="30" height="24"> Réduire
            </a>
        </nav>
    <article>
    <div class="formulaire">
        <h1>Modifier un étudiant</h1>
        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success); ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="modifierEtudiant.php?id=<?= htmlspecialchars($etudiant['num_etudiant']); ?>">
            <div class="form-group">
                <label for="raison_sociale">Nom* :</label>
                <input type="text" id="nom_etudiant" name="nom_etudiant" value="<?= htmlspecialchars($etudiant['nom_etudiant']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nom_contact">Prénom* :</label>
                <input type="text" id="prenom_etudiant" name="prenom_etudiant" value="<?= htmlspecialchars($etudiant['prenom_etudiant']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nom_resp">Nom d'utilisateur (8 caractères)* :</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($etudiant['login']); ?>"required pattern=".{8}">
            </div>
            <div class="form-group">
                <label for="rue_entreprise">Mot de passe (entre 8 et 30 caractères)* :</label>
                <input type="text" id="mdp" name="mdp" value="<?= htmlspecialchars($etudiant['mdp']); ?>" required pattern=".{8,30}">
            </div>
            <div class="form-group">
                <label for="cp_entreprise">Date d'obtention du BTS (AAAA-MM-JJ) :</label>
                <input type="datetime-local" id="annee_obtention" name="annee_obtention" value="<?= htmlspecialchars($etudiant['annee_obtention']); ?>" >
            </div>
            <div class="form-group">
                <label for="nom_contact">Classe* :</label>
                <input type="number" id="num_classe" name="num_classe" min="1" max="100" value="<?= htmlspecialchars($etudiant['num_classe']); ?>" required>
            </div>

            <button type="submit" >Valider</button>
            <br>
            <a href="stagaireAppli.php">Retour à la liste des étudiants</a>
            </form>

    </div>
    </article>
</body>
</html>
