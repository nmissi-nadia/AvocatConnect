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

// Démarrer une session pour l'utilisateur connecté
session_start();
$client_id = $_SESSION['user_id']; // ID du client connecté

// Récupérer les profils des avocats
$queryAvocats = "
    SELECT u.us_id, u.name, u.first_name, u.email, i.specialite, i.picture
    FROM utilisateur u
    JOIN infos i ON u.us_id = i.avocat_id
    WHERE u.role = 'Avocat'
";
$resultAvocats = mysqli_query($conn, $queryAvocats);

// Récupérer les réservations du client
$queryReservations = "
    SELECT r.reservation_id, r.reservation_date, r.statut,
           u.name AS avocat_name, u.first_name AS avocat_first_name
    FROM reservations r
    JOIN utilisateur u ON r.avocat_id = u.us_id
    WHERE r.client_id = $client_id
    ORDER BY r.reservation_date ASC
";
$resultReservations = mysqli_query($conn, $queryReservations);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- En-tête -->
<header class="bg-green-600 text-white text-center py-6">
    <h1 class="text-3xl font-bold">Bienvenue sur votre Dashboard Client</h1>
    <p class="text-sm mt-2">Consultez les profils des avocats et gérez vos réservations.</p>
</header>

<!-- Section : Profils des avocats -->
<div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Profils des Avocats</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php while ($avocat = mysqli_fetch_assoc($resultAvocats)): ?>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                <img src="uploads/<?php echo htmlspecialchars($avocat['picture']); ?>" alt="Avatar" class="w-24 h-24 rounded-full mx-auto">
                <h3 class="text-lg font-bold text-center mt-4">
                    <?php echo htmlspecialchars($avocat['name'] . ' ' . $avocat['first_name']); ?>
                </h3>
                <p class="text-gray-600 text-center mt-2">Spécialité : <?php echo htmlspecialchars($avocat['specialite']); ?></p>
                <p class="text-center mt-4">
                    <button onclick="openReservationModal(<?php echo $avocat['us_id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Réserver
                    </button>
                </p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Section : Gestion des réservations -->
<div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Vos Réservations</h2>
    <?php if (mysqli_num_rows($resultReservations) > 0): ?>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border py-2 px-4 text-left">Avocat</th>
                    <th class="border py-2 px-4 text-left">Date</th>
                    <th class="border py-2 px-4 text-left">Statut</th>
                    <th class="border py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reservation = mysqli_fetch_assoc($resultReservations)): ?>
                    <tr class="border hover:bg-gray-100">
                        <td class="py-2 px-4">
                            <?php echo htmlspecialchars($reservation['avocat_name'] . ' ' . $reservation['avocat_first_name']); ?>
                        </td>
                        <td class="py-2 px-4">
                            <?php echo htmlspecialchars($reservation['reservation_date']); ?>
                        </td>
                        <td class="py-2 px-4">
                            <?php echo htmlspecialchars($reservation['statut']); ?>
                        </td>
                        <td class="py-2 px-4">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Modifier</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Annuler</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-500 text-center">Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>

<!-- MODAL de réservation -->
<div id="reservationModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-11/12 md:w-1/2">
        <button id="closeModal" class="absolute top-4 right-4 text-red-500 text-xl">&times;</button>
        <h2 class="text-xl font-bold text-center mb-4">Réserver une consultation</h2>
        <form action="#" method="POST">
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <input type="hidden" name="avocat_id" id="avocat_id">
            <div class="mb-4">
                <input type="date" name="date" class="w-full p-4 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-4 rounded-lg hover:bg-blue-700">Réserver</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('reservationModal');
    const closeModal = document.getElementById('closeModal');

    function openReservationModal(avocatId) {
        document.getElementById('avocat_id').value = avocatId;
        modal.classList.remove('hidden');
    }

    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
</script>

</body>
</html>
