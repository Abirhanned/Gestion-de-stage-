<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez vers la page de connexion
if (!isset($_SESSION['user_login'])) {
    header('Location: identification.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Stage BTS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <style>

        .droite {
            float: right;
            display: block;
            margin-left: 20px; /* Vous pouvez ajuster cette valeur pour plus d'espacement */
        }

        /* Ajoutez une règle pour nettoyer les flottants après l'élément flottant */
        .clear {
            clear: both;
        }
        .reponse{
            text-align: left;
            border: 1px solid darkgray;
            border-radius: 10px;
            padding: 10px;
            background: lightgray
         
        }

        .important{
            color: red
        }
    </style>
</head>
<body>

    <div class="contenu">
        <nav class="nav flex-column">
            <a href="acceuilAppli.php">  
                <img src="icons/home.png" alt="logo" width="30" height="24"> Accueil
            </a>
            <a href="EntrepriseAppli.php"> 
                <img src="icons/entreprise.png" alt="logo" width="30" height="24"> Entreprise
            </a>
            <a href="stagaireAppli.php"> 
                <img src="icons/stage.png" alt="logo" width="30" height="24"> Stagiaire
            </a>
            <a href="InscriptionAppli.php"> 
                <img src="icons/inscrire.png" alt="logo" width="30" height="24"> Inscription
            </a>
            <a href="aideAppli.php" class="clic"> 
                <img src="icons/aide.png" alt="logo" width="30" height="24"> Aide
            </a>
            <a href="deconnexion.php"> 
                <img src="icons/deconnexion.png" alt="logo" width="30" height="24"> Déconnexion
            </a>
        </nav>

    <article>
    <div class="droite">
                <h1>Aide</h1>
                <p>Bienvenue sur la FAQ</p>
            </div>
            <!-- Ajoutez un élément pour nettoyer les flottants après .droite -->
            <div class="clear"></div>
            <hr>

            <div>
        <h2> Entreprise </h2>

    <h3> Comment rechercher une entreprise ? </h3>
<p class="reponse"> Si vous voulez rechercher une entreprise, vous devez aller sur la page " Entreprise ", pour cliquer sur le bouton " Rechercher une entreprise ". Il vous est alors fourni trois critères. Utilisez-les afin de pouvoir trouver les entreprises qui correspondent à vos choix.</p>

<h3>Comment ajouter une entreprise ?</h3>
<p class="reponse">Pour ajouter une entreprise, rendez-vous sur la page " Entreprise ", où vous devez cliquer sur le bouton " Ajouter une entreprise ". Vous devrez ensuite ajouter les informations concernant l’entreprise. Toutes les informations ne sont pas obligatoires, mais il est conseillé d’en fournir un maximum pour renseigner les futurs stagiaires sur les entreprises référencées.</p>

<h3>Comment afficher ou enlever une information concernant l'entreprise ?</h3>
<p class="reponse">En allant sur la page " Entreprise ", vous pouvez voir les entreprises déjà référencées. Vous pouvez alors remarquer que certaines informations concernant l'entreprise sont absentes. Vous pouvez cependant les afficher grâce à la liste déroulante : choisissez l'information que vous voulez afficher puis cliquez sur le bouton " Ajouter ". Si vous voulez enlever une information, il vous suffit de cliquer sur le moins situé à l'entête de la colonne représentant l'information concerné.</p>

<h3>N'y a-t-il pas une autre solution pour voir ces informations ?</h3>
<p class="reponse">Bien sûr, vous pouvez cliquer sur l’icône <img src="icons/voir.png" alt="Voir" width="20" height="16"> pour voir toutes les informations concernant l'entreprise que vous avez sélectionné.</p>

<h3>Comment puis-je supprimer une entreprise ?</h3>
<p class="reponse">Rien de plus simple, il vous suffit de cliquer sur l'icône <img src="icons/supprimer.png" alt="Voir" width="20" height="16"> qui se situe sur la deuxième colonne " Opération ".</p>
<b class="important">Faites bien attention de ne pas vous tromper de ligne !</b>

<h3>Et si je veux modifier une information fausse ?</h3>
<p class="reponse">Cliquez sur l’icône <img src="icons/modifier.png" alt="Voir" width="20" height="16">, puis changer le(s) information(s) que vous voulez. Vous pourrez par la même occasion renseigner une information manquante si vous en avez la possibilité.</p>
    </div>

    <h2> Stagiaire </h2>
    <h3>Comment rechercher un stagiaire ? </h3>
    <p class="reponse">Tout d'abord, dirigez-vous sur la page " Stagiaire ". Cliquez ensuite sur le bouton " Rechercher un stagiaire existant ". Vous aurez alors quatre listes déroulantes. Vous pourrez alors choisir, pour chaque champ, l'information voulue.</p>
<h3>Comment inscrire un étudiant à un stage ?</h3>
<p class="reponse">Pour cela, vous devez vous rendre sur la page " Inscription ". Ensuite, vous devrez remplir un formulaire contenant diverses informations concernant le stage de l’étudiant, comme par exemple l’entreprise ou encore le professeur qui s’occupera du stage de l’étudiant. Vous pouvez aussi le faire à partir de la page " Entreprise " : cliquez sur la poignée de main située sur la première colonne " Opération ", et le formulaire d'inscription s'affichera avec le nom de l'entreprise pré-rentré. </p>
<h3>Comment peut-on voir les informations des stagiaires ?</h3>
<p class="reponse">Sur la liste qui s'affiche sur la page " Stagiaire ", ou en cliquant sur l’icône <img src="icons/voir.png" alt="Voir" width="20" height="16">.</p>
<h3>Comment peut-on supprimer un stagiaire ?</h3>
<p class="reponse">Comme pour une entreprise : cliquez sur l'icône <img src="icons/supprimer.png" alt="Voir" width="20" height="16"> présente sur la page " Stagiaire ".</p>
<h3>Et pour modifier le contenu d'un champ, pareil que pour les entreprises ?</h3>
<p class="reponse">Tout juste ! </p>

    </div>

</body>
</html>
