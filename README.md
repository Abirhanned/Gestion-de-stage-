# Web  Projet de Gestion des Stages

Ce projet est une application de gestion des stages pour un système éducatif, permettant aux utilisateurs de se connecter et d'accéder à une interface de gestion après une identification réussie. L'application utilise PHP avec un système de gestion de base de données MySQL.

Le fichier index.php est la page d'entrée de l'application. Il sert de point central pour :
- Vérifier si un utilisateur est connecté à l'application.
- Gérer la logique de connexion.
- Afficher une vue propre et lisible

Le fichier AuthController.php est une classe dédiée à la gestion de l'authentification des utilisateurs dans l'application. Il centralise toute la logique liée à la connexion et déconnexion des utilisateurs.

Le fichier  login.html.twig est un template de la bibliothèque Twig. Il  affiche une page de connexion où les utilisateurs peuvent saisir leur nom d'utilisateur et leur mot de passe pour se connecter à l'application.
