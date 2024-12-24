<?php
// Inclure la connexion à la base de données
require_once('connexion.php');

// Vérifier si l'ID de l'entreprise est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID d'entreprise manquant.");
}

$entreprise_id = (int) $_GET['id'];

// Récupérer les informations de l'entreprise à modifier
try {
    $stmt = $db->prepare("SELECT * FROM entreprise WHERE num_entreprise = :id");
    $stmt->execute([':id' => $entreprise_id]);
    $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$entreprise) {
        die("Entreprise introuvable.");
    }

    // Récupérer les spécialités associées
    $stmt = $db->prepare("SELECT num_spec FROM spec_entreprise WHERE num_entreprise = :id");
    $stmt->execute([':id' => $entreprise_id]);
    $entreprise_specialites = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des informations : " . $e->getMessage());
}

// Récupérer toutes les spécialités disponibles
try {
    $stmt = $db->prepare("SELECT num_spec, libelle FROM specialite");
    $stmt->execute();
    $specialites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des spécialités : " . $e->getMessage());
}

// Mise à jour des informations de l'entreprise
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des champs requis
    if (!empty($_POST['raison_sociale']) && !empty($_POST['rue_entreprise']) &&
        !empty($_POST['cp_entreprise']) && !empty($_POST['ville_entreprise']) &&
        !empty($_POST['tel_entreprise']) && !empty($_POST['specialites'])
    ) {
        // Nettoyage des données pour éviter les failles XSS
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
        $selected_specialites = array_map('intval', $_POST['specialites']);

        // Mise à jour des informations de l'entreprise
        try {
            $sql = "UPDATE entreprise SET
                        raison_sociale = :raison_sociale,
                        nom_contact = :nom_contact,
                        nom_resp = :nom_resp,
                        rue_entreprise = :rue_entreprise,
                        cp_entreprise = :cp_entreprise,
                        ville_entreprise = :ville_entreprise,
                        tel_entreprise = :tel_entreprise,
                        fax_entreprise = :fax_entreprise,
                        email = :email,
                        observation = :observation,
                        site_entreprise = :site_entreprise,
                        niveau = :niveau
                    WHERE num_entreprise = :id";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':raison_sociale' => $raison_sociale,
                ':nom_contact' => $nom_contact,
                ':nom_resp' => $nom_resp,
                ':rue_entreprise' => $rue_entreprise,
                ':cp_entreprise' => $cp_entreprise,
                ':ville_entreprise' => $ville_entreprise,
                ':tel_entreprise' => $tel_entreprise,
                ':fax_entreprise' => $fax_entreprise,
                ':email' => $email,
                ':observation' => $observation,
                ':site_entreprise' => $site_entreprise,
                ':niveau' => $niveau,
                ':id' => $entreprise_id
            ]);

            // Mettre à jour les spécialités associées
            $db->prepare("DELETE FROM spec_entreprise WHERE num_entreprise = :id")->execute([':id' => $entreprise_id]);

            $stmt = $db->prepare("INSERT INTO spec_entreprise (num_entreprise, num_spec) VALUES (:num_entreprise, :num_spec)");
            foreach ($selected_specialites as $spec_id) {
                $stmt->execute([
                    ':num_entreprise' => $entreprise_id,
                    ':num_spec' => $spec_id,
                ]);
            }

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
    <title>Modifier une entreprise</title>
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
    <div class="formulaire">
        <h1>Modifier une entreprise</h1>
        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success); ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
    <div class="form-group">
        <label for="raison_sociale">Nom de l'entreprise* :</label>
        <input type="text" id="raison_sociale" name="raison_sociale" value="<?= htmlspecialchars($entreprise['raison_sociale']); ?>" required>
    </div>

    <div class="form-group">
        <label for="nom_contact">Nom du contact :</label>
        <input type="text" id="nom_contact" name="nom_contact" value="<?= htmlspecialchars($entreprise['nom_contact']); ?>">
    </div>

    <div class="form-group">
        <label for="nom_resp">Nom du responsable :</label>
        <input type="text" id="nom_resp" name="nom_resp" value="<?= htmlspecialchars($entreprise['nom_resp']); ?>">
    </div>

    <div class="form-group">
        <label for="rue_entreprise">Rue* :</label>
        <input type="text" id="rue_entreprise" name="rue_entreprise" value="<?= htmlspecialchars($entreprise['rue_entreprise']); ?>" required>
    </div>

    <div class="form-group">
        <label for="cp_entreprise">Code postal* :</label>
        <input type="text" id="cp_entreprise" name="cp_entreprise" value="<?= htmlspecialchars($entreprise['cp_entreprise']); ?>" required>
    </div>

    <div class="form-group">
        <label for="ville_entreprise">Ville* :</label>
        <input type="text" id="ville_entreprise" name="ville_entreprise" value="<?= htmlspecialchars($entreprise['ville_entreprise']); ?>" required>
    </div>

    <div class="form-group">
        <label for="tel_entreprise">Téléphone* :</label>
        <input type="text" id="tel_entreprise" name="tel_entreprise" value="<?= htmlspecialchars($entreprise['tel_entreprise']); ?>" required>
    </div>

    <div class="form-group">
        <label for="fax_entreprise">Fax :</label>
        <input type="text" id="fax_entreprise" name="fax_entreprise" value="<?= htmlspecialchars($entreprise['fax_entreprise']); ?>">
    </div>

    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($entreprise['email']); ?>">
    </div>

    <div class="form-group">
        <label for="site_entreprise">Site web :</label>
        <input type="text" id="site_entreprise" name="site_entreprise" value="<?= htmlspecialchars($entreprise['site_entreprise']); ?>">
    </div>

    <div class="form-group">
        <label for="niveau">Niveau :</label>
        <input type="text" id="niveau" name="niveau" value="<?= htmlspecialchars($entreprise['niveau']); ?>">
    </div>

    <div class="form-group">
        <label for="observation">Observation :</label>
        <textarea id="observation" name="observation"><?= htmlspecialchars($entreprise['observation']); ?></textarea>
    </div>

    <div class="form-group">
        <label for="specialites">Spécialités* :</label>
        <div class="specialite-options">
            <?php foreach ($specialites as $spec): ?>
                <label>
                    <input type="checkbox" name="specialites[]" value="<?= htmlspecialchars($spec['num_spec']); ?>" <?= in_array($spec['num_spec'], $entreprise_specialites) ? 'checked' : ''; ?>>
                    <?= htmlspecialchars($spec['libelle']); ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <button type="submit">Modifier</button>
     </form>

    </div>
    </article>
</body>
</html>
