<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'avocat';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Récupération de l'ID de l'avocat connecté (par exemple, via la session)
session_start();
$avocat_id = $_SESSION['user_id']; // Supposons que l'ID de l'avocat connecté est stocké dans la session



mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'avocat</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Importation de Tailwind CSS -->
</head>
<body class="bg-gray-100">



<footer class="bg-green-600 text-white text-center py-4 mt-12">
    <p>© 2024-2030 AvocatConnect - Tous droits réservés</p>
</footer>

</body>
</html>
