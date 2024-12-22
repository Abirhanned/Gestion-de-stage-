<?php

// Démarrer la session
session_start();

// Si l'utilisateur est déjà connecté, rediriger vers la page d'accueil
if (isset($_SESSION['user_login'])) {
    header('Location: acceuilAppli.php');
    exit();
}

// Charger les dépendances
require 'connexion.php';
require 'AuthController.php';
require 'vendor/autoload.php'; // Autoloader pour Twig

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Initialiser Twig
$loader = new FilesystemLoader('templates'); // Assurez-vous que 'templates' contient 'login.html.twig'
$twig = new Environment($loader);

// Créer une instance de AuthController
$authController = new AuthController($db); // Vérifiez que $db est bien défini dans connexion.php

// Vérification des informations de connexion
$error_message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $password = htmlspecialchars(trim($_POST['password'] ?? ''));

    if ($authController->login($username, $password)) {
        session_regenerate_id(true); // Sécuriser la session
        header('Location: acceuilAppli.php');
        exit();
    } else {
        $error_message = 'Identifiants incorrects.';
    }
}

// Afficher la page de connexion (formulaire)
echo $twig->render('login.html.twig', [
    'error' => $error_message,
]);
?>
