<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'avocat';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Démarrer une session pour l'avocat connecté
session_start();
$avocat_id = $_SESSION['user_id']; // ID de l'avocat connecté

// Récupérer les réservations pour l'avocat
$queryReservations = "
    SELECT r.reservation_id, r.reservation_date, r.statut, 
           u.name AS client_name, u.first_name AS client_first_name
    FROM reservations r
    JOIN utilisateur u ON r.client_id = u.us_id
    WHERE r.avocat_id = $avocat_id
    ORDER BY r.reservation_date ASC
";
$resultReservations = mysqli_query($conn, $queryReservations);

// Récupérer les statistiques pour l'avocat
$queryStats = "
    SELECT
        (SELECT COUNT(*) FROM reservations WHERE avocat_id = $avocat_id AND statut = 'en_attente') AS demandes_en_attente,
        (SELECT COUNT(*) FROM reservations WHERE avocat_id = $avocat_id AND statut = 'confirmee' AND reservation_date = CURDATE()) AS demandes_aujourd_hui,
        (SELECT COUNT(*) FROM reservations WHERE avocat_id = $avocat_id AND statut = 'confirmee' AND reservation_date = CURDATE() + INTERVAL 1 DAY) AS demandes_demain
";
$resultStats = mysqli_query($conn, $queryStats);
$stats = mysqli_fetch_assoc($resultStats);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Avocat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- En-tête -->
<header class="bg-green-600 text-white text-center py-6">
    <h1 class="text-3xl font-bold">Bienvenue sur votre Dashboard</h1>
    <p class="text-sm mt-2">Gérez vos réservations et vos disponibilités.</p>
</header>

<!-- Section : Statistiques -->
<div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Statistiques</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-100 p-4 rounded-lg shadow">
            <p class="text-lg font-bold">Demandes en attente</p>
            <p class="text-4xl text-blue-600 font-semibold">
                <?php echo $stats['demandes_en_attente']; ?>
            </p>
        </div>
        <div class="bg-green-100 p-4 rounded-lg shadow">
            <p class="text-lg font-bold">Demandes confirmées aujourd'hui</p>
            <p class="text-4xl text-green-600 font-semibold">
                <?php echo $stats['demandes_aujourd_hui']; ?>
            </p>
        </div>
        <div class="bg-yellow-100 p-4 rounded-lg shadow">
            <p class="text-lg font-bold">Demandes confirmées demain</p>
            <p class="text-4xl text-yellow-600 font-semibold">
                <?php echo $stats['demandes_demain']; ?>
            </p>
        </div>
    </div>
</div>

<!-- Section : Gestion des réservations -->
<div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Gestion des Réservations</h2>
    <?php if (mysqli_num_rows($resultReservations) > 0): ?>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border py-2 px-4 text-left">Client</th>
                    <th class="border py-2 px-4 text-left">Date</th>
                    <th class="border py-2 px-4 text-left">Statut</th>
                    <th class="border py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reservation = mysqli_fetch_assoc($resultReservations)): ?>
                    <tr class="border hover:bg-gray-100">
                        <td class="py-2 px-4">
                            <?php echo htmlspecialchars($reservation['client_name'] . ' ' . $reservation['client_first_name']); ?>
                        </td>
                        <td class="py-2 px-4">
                            <?php echo htmlspecialchars($reservation['reservation_date']); ?>
                        </td>
                        <td class="py-2 px-4">
                            <?php echo htmlspecialchars($reservation['statut']); ?>
                        </td>
                        <td class="py-2 px-4">
                            <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700">Accepter</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Refuser</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-500 text-center">Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>

<!-- Section : Gestion des disponibilités -->
<div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Gestion des Disponibilités</h2>
    <p class="text-gray-600">Ajoutez ou modifiez vos créneaux horaires disponibles.</p>
    <form action="update_disponibilites.php" method="POST" class="mt-4">
        <div class="mb-4">
            <label for="dispo_date" class="block text-gray-700 font-bold">Date :</label>
            <input type="date" id="dispo_date" name="dispo_date" class="w-full p-4 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="statut" class="block text-gray-700 font-bold">Statut :</label>
            <select id="statut" name="statut" class="w-full p-4 border rounded-lg">
                <option value="disponible">Disponible</option>
                <option value="occupe">Occupé</option>
            </select>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-4 rounded-lg hover:bg-blue-700">Mettre à jour</button>
    </form>
</div>

</body>
</html>
