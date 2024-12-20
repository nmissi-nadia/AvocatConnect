<?php
// Démarrer la session
session_start();

// Vérifier si une session est active
if (isset($_SESSION)) {
    // Détruire toutes les variables de session
    session_unset();

    // Détruire la session elle-même
    session_destroy();
}

// Rediriger l'utilisateur vers la page de connexion ou d'accueil
header("Location: index.html");
exit();
?>