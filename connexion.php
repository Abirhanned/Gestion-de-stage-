<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=bdd_geststages', 'root', '');
    $db->exec('SET NAMES "UTF8"');
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
