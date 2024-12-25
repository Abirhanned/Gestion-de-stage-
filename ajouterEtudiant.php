<?php
// Inclure la connexion à la base de données
require_once('connexion.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si tous les champs requis sont remplis
    if (!empty($_POST['nom_etudiant']) &&
        !empty($_POST['prenom_etudiant']) &&
        !empty($_POST['login']) &&
        !empty($_POST['mdp']) &&
        !empty($_POST['num_classe'])
    ) {
        // Nettoyer et sécuriser les données
        $nom_etudiant = htmlspecialchars(strip_tags($_POST['nom_etudiant']));
        $prenom_etudiant = htmlspecialchars(strip_tags($_POST['prenom_etudiant'] ?? ''));
        $login = htmlspecialchars(strip_tags($_POST['login'] ?? ''));
        $mdp = htmlspecialchars(strip_tags($_POST['mdp']));
        $annee_obtention = htmlspecialchars(strip_tags($_POST['annee_obtention']));
        $num_classe = htmlspecialchars(strip_tags($_POST['num_classe']));

        // Préparer la requête SQL pour insérer les données
        $sql = "INSERT INTO `etudiant` (
                    `nom_etudiant`, 
                    `prenom_etudiant`, 
                    `login`, 
                    `mdp`, 
                    `annee_obtention`, 
                    `num_classe`
                   
                ) VALUES (
                    :nom_etudiant, 
                    :prenom_etudiant, 
                    :login, 
                    :mdp, 
                    :annee_obtention, 
                    :num_classe
                )";

        $query = $db->prepare($sql);

        // Associer les valeurs
        $query->bindValue(':nom_etudiant', $nom_etudiant, PDO::PARAM_STR);
        $query->bindValue(':prenom_etudiant', $prenom_etudiant, PDO::PARAM_STR);
        $query->bindValue(':login', $login, PDO::PARAM_STR);
        $query->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $query->bindValue(':annee_obtention', $annee_obtention, PDO::PARAM_STR);
        $query->bindValue(':num_classe', $num_classe, PDO::PARAM_STR);

        // Exécuter la requête
        if ($query->execute()) {
            $etudiant_id = $db->lastInsertId(); // Récupérer l'ID de l'entreprise insérée

            $success = "Etudiant ajoutée avec succès !";
            header('Location: stagaireAppli.php');
        } else {
            $error = "Une erreur s'est produite lors de l'ajout.";
        }
    } else {
        $error = "Tous les champs requis (*) doivent être remplis.";
    }
}


// Inclure la fermeture de la connexion
require_once('cloose.php');
?>



<!DOCTYPE html>
<html lang="fr">

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




.nav a img {
    margin-right: 8px;
}

.nav a:hover {
    background-color: #1c1c1c;
}


/* Style du formulaire */
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
        <?php elseif (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="ajouterEtudiant.php">
            <div class="form-group">
                <label for="raison_sociale">Nom* :</label>
                <input type="text" id="nom_etudiant" name="nom_etudiant" required>
            </div>
            <div class="form-group">
                <label for="nom_contact">Prénom* :</label>
                <input type="text" id="prenom_etudiant" name="prenom_etudiant" required>
            </div>
            <div class="form-group">
                <label for="nom_resp">Nom d'utilisateur (8 caractères)* :</label>
                <input type="text" id="login" name="login" required pattern=".{8}">
            </div>
            <div class="form-group">
                <label for="rue_entreprise">Mot de passe (entre 8 et 30 caractères)* :</label>
                <input type="text" id="mdp" name="mdp" required pattern=".{8,30}">
            </div>
            <div class="form-group">
                <label for="cp_entreprise">Date d'obtention du BTS (AAAA-MM-JJ) :</label>
                <input type="datetime-local" id="annee_obtention" name="annee_obtention" >
            </div>
            <div class="form-group">
                <label for="nom_contact">Classe* :</label>
                <input type="number" id="num_classe" name="num_classe" min="1" max="100" required>
            </div>

            <button type="submit" class="btn-submit">Ajouter</button>
            <br>
            <a href="stagaireAppli.php">Retour à la liste des étudiants</a>
            </form>
            <div class="note">Les champs marqués d'un astérisque (*) sont obligatoires.</div>
              </div>
              </div>
        </article>
</body>
</html>
