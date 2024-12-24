<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle "professeur"
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'professeur') {
    header('Location: EntrepriseAppli.php?message=Erreur : Accès refusé.&type=error');
    exit();
}

// Inclure la connexion à la base
require_once('connexion.php');

// Vérifier si l'ID de l'entreprise est passé en paramètre
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $num_entreprise = $_GET['id'];

    try {
        // Vérifiez si l'entreprise existe
        $sql = 'SELECT raison_sociale FROM entreprise WHERE num_entreprise = :num_entreprise';
        $query = $db->prepare($sql);
        $query->bindParam(':num_entreprise', $num_entreprise, PDO::PARAM_INT);
        $query->execute();
        $entreprise = $query->fetch(PDO::FETCH_ASSOC);

        if ($entreprise) {
            // Étape 1 : Supprimer les enregistrements dans spec_entreprise
            $sql = 'DELETE FROM spec_entreprise WHERE num_entreprise = :num_entreprise';
            $query = $db->prepare($sql);
            $query->bindParam(':num_entreprise', $num_entreprise, PDO::PARAM_INT);
            $query->execute();

            // Étape 2 : Supprimer l'entreprise
            $sql = 'DELETE FROM entreprise WHERE num_entreprise = :num_entreprise';
            $query = $db->prepare($sql);
            $query->bindParam(':num_entreprise', $num_entreprise, PDO::PARAM_INT);
            
            if ($query->execute()) {
                // Rediriger avec un message de succès
                header('Location: EntrepriseAppli.php?message=Entreprise supprimée avec succès&type=success');
                exit();
            } else {
                header('Location: EntrepriseAppli.php?message=Erreur : La suppression a échoué.&type=error');
                exit();
            }
        } else {
            // Si l'entreprise n'existe pas
            header('Location: EntrepriseAppli.php?message=Erreur : Entreprise introuvable.&type=error');
            exit();
        }
    } catch (PDOException $e) {
        // En cas d'erreur SQL
        header('Location: EntrepriseAppli.php?message=Erreur : ' . $e->getMessage() . '&type=error');
        exit();
    }
} else {
    // Si aucun ID n'est passé en paramètre
    header('Location: EntrepriseAppli.php?message=Erreur : Paramètre ID invalide.&type=error');
    exit();
}
?>
