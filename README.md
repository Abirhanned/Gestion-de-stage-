# Web  Projet de Gestion des Stages

Ce projet est une application de gestion des stages pour un système éducatif, permettant aux utilisateurs de se connecter et d'accéder à une interface de gestion après une identification réussie. L'application utilise PHP avec un système de gestion de base de données MySQL.

Le fichier index.php est la page d'entrée de l'application. Il sert de point central pour :
- Vérifier si un utilisateur est connecté à l'application.
- Gérer la logique de connexion.
- Afficher une vue propre et lisible

Le fichier AuthController.php est une classe dédiée à la gestion de l'authentification des utilisateurs dans l'application. Il centralise toute la logique liée à la connexion et déconnexion des utilisateurs.

Le fichier  login.html.twig est un template de la bibliothèque Twig. Il  affiche une page de connexion où les utilisateurs peuvent saisir leur nom d'utilisateur et leur mot de passe pour se connecter à l'application.

Le fichier connexion.php gère  la connexion à la base de données. Il contient les paramètres nécessaires pour établir une liaison sécurisée entre l'application et la base de données.

Le fichier close.php, qui assure la déconnexion sécurisée de la base de données lorsque les opérations sont terminées.

Le fichier  deconnexion.php est le fichier qui gère la déconnexion des utilisateurs. Il détruit ou invalide la session active pour sécuriser l'application.

Le fichier acceuilAppli.php est la page d'accueil de l'application.

Le fichier EntrepriseAppli.php est la page principale pour la gestion des entreprises. Elle regroupe les différentes fonctionnalités, comme l'ajout, la modification, la suppression , la recherche, les details des entreprises et l'inscription dans une interface centralisée. Ces fonctionnalités s'appuient sur plusieurs fichiers spécifiques :
- ajouter.php, qui permet d'ajouter de nouvelles entreprises via un formulaire de saisie ;
- modifier.php, qui fournit une interface pour modifier les informations existantes des entreprises ;
- rechercher.php, qui facilite la recherche d'entreprises spécifiques en fonction de divers critères ;
- supprimer.php, qui gère la suppression sécurisée des entreprises de la base de données ;
- details.php, qui affiche des informations détaillées sur une entreprise particulière, incluant ses données associées.
