<?php

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($username, $password) {
        // Vérifiez dans les tables `etudiant` et `professeur`
        $stmt = $this->pdo->prepare('SELECT login, mdp, "etudiant" AS role FROM etudiant WHERE login = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            $stmt = $this->pdo->prepare('SELECT login, mdp, "professeur" AS role FROM professeur WHERE login = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();
        }

        if ($user && $password === $user['mdp']) {
            session_start();
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_login'] = $user['login'];
            return true;
        }

        return false; // Échec de la connexion
    }
}
