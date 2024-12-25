<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle "professeur"
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'professeur') {
    header('Location: stagaireAppli.php?message=Erreur : Accès refusé.&type=error');
    exit();
}

// Inclure la connexion à la base
require_once('connexion.php');

// Vérifier si l'ID de l'entreprise est passé en paramètre
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $num_etudiant = $_GET['id'];

    try {
        // Vérifiez si l'entreprise existe
        $sql = 'SELECT num_etudiant FROM etudiant WHERE num_etudiant = :num_etudiant';
        $query = $db->prepare($sql);
        $query->bindParam(':num_etudiant', $num_etudiant, PDO::PARAM_INT);
        $query->execute();
        $etudiant = $query->fetch(PDO::FETCH_ASSOC);

        if ($etudiant) {


            // Étape 2 : Supprimer l'entreprise
            $sql = 'DELETE FROM etudiant WHERE num_etudiant = :num_etudiant';
            $query = $db->prepare($sql);
            $query->bindParam(':num_etudiant', $num_etudiant, PDO::PARAM_INT);
            
            if ($query->execute()) {
                // Rediriger avec un message de succès
                header('Location: stagaireAppli.php?message=Etudiant supprimée avec succès&type=success');
                exit();
            } else {
                header('Location: stagaireAppli.php?message=Erreur : La suppression a échoué.&type=error');
                exit();
            }
        } else {
            // Si l'entreprise n'existe pas
            header('Location: dtagaireAppli.php?message=Erreur : Etudiant introuvable.&type=error');
            exit();
        }
    } catch (PDOException $e) {
        // En cas d'erreur SQL
        header('Location: stagaireAppli.php?message=Erreur : ' . $e->getMessage() . '&type=error');
        exit();
    }
} else {
    // Si aucun ID n'est passé en paramètre
    header('Location: stagaireAppli.php?message=Erreur : Paramètre ID invalide.&type=error');
    exit();
}
?>
