<?php
// Inclure la connexion à la base de données
require_once('connexion.php');

// Récupérer les spécialités dans la base de données
try {
    $stmt = $db->prepare("SELECT num_spec, libelle FROM specialite");
    $stmt->execute();
    $specialites = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère les spécialités sous forme de tableau associatif
} catch (PDOException $e) {
    die("Erreur lors de la récupération des spécialités : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si tous les champs requis sont remplis
    if (!empty($_POST['raison_sociale']) &&
        !empty($_POST['rue_entreprise']) &&
        !empty($_POST['cp_entreprise']) &&
        !empty($_POST['ville_entreprise']) &&
        !empty($_POST['tel_entreprise']) &&
        !empty($_POST['specialites'])
    ) {
        // Nettoyer et sécuriser les données
        $raison_sociale = htmlspecialchars(strip_tags($_POST['raison_sociale']));
        $nom_contact = htmlspecialchars(strip_tags($_POST['nom_contact'] ?? ''));
        $nom_resp = htmlspecialchars(strip_tags($_POST['nom_resp'] ?? ''));
        $rue_entreprise = htmlspecialchars(strip_tags($_POST['rue_entreprise']));
        $cp_entreprise = htmlspecialchars(strip_tags($_POST['cp_entreprise']));
        $ville_entreprise = htmlspecialchars(strip_tags($_POST['ville_entreprise']));
        $tel_entreprise = htmlspecialchars(strip_tags($_POST['tel_entreprise']));
        $fax_entreprise = htmlspecialchars(strip_tags($_POST['fax_entreprise'] ?? ''));
        $email = htmlspecialchars(strip_tags($_POST['email'] ?? ''));
        $observation = htmlspecialchars(strip_tags($_POST['observation'] ?? ''));
        $site_entreprise = htmlspecialchars(strip_tags($_POST['site_entreprise'] ?? ''));
        $niveau = htmlspecialchars(strip_tags($_POST['niveau'] ?? ''));
        $selected_specialites = array_map('intval', $_POST['specialites']); // Convertir en entier pour éviter les injections

        // Préparer la requête SQL pour insérer les données
        $sql = "INSERT INTO entreprise (
                    raison_sociale, 
                    nom_contact, 
                    nom_resp, 
                    rue_entreprise, 
                    cp_entreprise, 
                    ville_entreprise, 
                    tel_entreprise, 
                    fax_entreprise, 
                    email, 
                    observation, 
                    site_entreprise, 
                    niveau
                ) VALUES (
                    :raison_sociale, 
                    :nom_contact, 
                    :nom_resp, 
                    :rue_entreprise, 
                    :cp_entreprise, 
                    :ville_entreprise, 
                    :tel_entreprise, 
                    :fax_entreprise, 
                    :email, 
                    :observation, 
                    :site_entreprise, 
                    :niveau
                )";

        $query = $db->prepare($sql);

        // Associer les valeurs
        $query->bindValue(':raison_sociale', $raison_sociale, PDO::PARAM_STR);
        $query->bindValue(':nom_contact', $nom_contact, PDO::PARAM_STR);
        $query->bindValue(':nom_resp', $nom_resp, PDO::PARAM_STR);
        $query->bindValue(':rue_entreprise', $rue_entreprise, PDO::PARAM_STR);
        $query->bindValue(':cp_entreprise', $cp_entreprise, PDO::PARAM_STR);
        $query->bindValue(':ville_entreprise', $ville_entreprise, PDO::PARAM_STR);
        $query->bindValue(':tel_entreprise', $tel_entreprise, PDO::PARAM_STR);
        $query->bindValue(':fax_entreprise', $fax_entreprise, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':observation', $observation, PDO::PARAM_STR);
        $query->bindValue(':site_entreprise', $site_entreprise, PDO::PARAM_STR);
        $query->bindValue(':niveau', $niveau, PDO::PARAM_STR);

        // Exécuter la requête
        if ($query->execute()) {
            $entreprise_id = $db->lastInsertId(); // Récupérer l'ID de l'entreprise insérée

            // Insérer les relations dans spec_entreprise
            $stmt = $db->prepare("INSERT INTO spec_entreprise (num_entreprise, num_spec) VALUES (:num_entreprise, :num_spec)");
            foreach ($selected_specialites as $spec_id) {
                $stmt->execute([
                    ':num_entreprise' => $entreprise_id,
                    ':num_spec' => $spec_id,
                ]);
            }

            $success = "Entreprise ajoutée avec succès !";
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
<>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une entreprise</title>
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
    <div class="content">
    <div class="formulaire">
        <h1>Ajouter une entreprise</h1>

        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success); ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="ajouter.php">
            <div class="form-group">
                <label for="raison_sociale">Nom de l'entreprise* :</label>
                <input type="text" id="raison_sociale" name="raison_sociale" required>
            </div>
            <div class="form-group">
                <label for="nom_contact">Nom du contact :</label>
                <input type="text" id="nom_contact" name="nom_contact">
            </div>
            <div class="form-group">
                <label for="nom_resp">Nom du responsable :</label>
                <input type="text" id="nom_resp" name="nom_resp">
            </div>
            <div class="form-group">
                <label for="rue_entreprise">Rue* :</label>
                <input type="text" id="rue_entreprise" name="rue_entreprise" required>
            </div>
            <div class="form-group">
                <label for="cp_entreprise">Code postal* :</label>
                <input type="text" id="cp_entreprise" name="cp_entreprise" required>
            </div>
            <div class="form-group">
                <label for="ville_entreprise">Ville* :</label>
                <input type="text" id="ville_entreprise" name="ville_entreprise" required>
            </div>
            <div class="form-group">
                <label for="tel_entreprise">Téléphone* :</label>
                <input type="text" id="tel_entreprise" name="tel_entreprise" required>
            </div>
            <div class="form-group">
                <label for="fax_entreprise">Fax :</label>
                <input type="text" id="fax_entreprise" name="fax_entreprise">
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="observation">Observation :</label>
                <textarea id="observation" name="observation"></textarea>
            </div>
            <div class="form-group">
                <label for="site_entreprise">URL du site :</label>
                <input type="url" id="site_entreprise" name="site_entreprise">
            </div>
            <div class="form-group">
                <label for="niveau">Niveau :</label>
                <input type="text" id="niveau" name="niveau">
            </div>
            <div class="form-group">
            <label for="specialites">Spécialités* :</label>
            <div class="specialite-options">
           <?php if (!empty($specialites)): ?>
            <?php foreach ($specialites as $spec): ?>
                <label class="specialite-option">
                    <input type="checkbox" name="specialites[]" value="<?= htmlspecialchars($spec['num_spec']); ?>">
                    <?= htmlspecialchars($spec['libelle']); ?>
                </label>
            <?php endforeach; ?>
        <?php else: ?> 
            <p>Aucune spécialité disponible.</p>
        <?php endif; ?>
          </div>
        </div>


            <button type="submit" class="btn-submit">Ajouter</button>
            </form>
            <div class="note">Les champs marqués d'un astérisque (*) sont obligatoires.</div>
              </div>
              </div>
        </article>
</body>
</html>
