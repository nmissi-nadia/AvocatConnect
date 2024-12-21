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

// Démarrer une session pour récupérer l'ID du client connecté
session_start();
$client_id = $_SESSION['user_id']; // Supposons que l'ID du client est stocké dans la session

// Récupérer les profils des avocats
$queryAvocats = "
    SELECT u.us_id, u.name, u.first_name, u.email, i.specialite, i.picture 
    FROM utilisateur u 
    JOIN infos i ON u.us_id = i.avocat_id 
    WHERE u.role = 'Avocat'
";
$resultAvocats = mysqli_query($conn, $queryAvocats);

// Récupérer les disponibilités des avocats
$queryDisponibilites = "
    SELECT d.avocat_id, d.dispo_date, d.statut, u.name, u.first_name
    FROM disponibilite d
    JOIN utilisateur u ON d.avocat_id = u.us_id
    WHERE d.statut = 'disponible'
";
$resultDisponibilites = mysqli_query($conn, $queryDisponibilites);

$disponibilites = [];
while ($row = mysqli_fetch_assoc($resultDisponibilites)) {
    $disponibilites[$row['avocat_id']][] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CSS -->
</head>
<body class="bg-gray-100">

<!-- Header -->
<header class="bg-green-600 text-white text-center py-6">
    <h1 class="text-3xl font-bold">Bienvenue sur votre Dashboard Client</h1>
    <p class="text-sm mt-2">Consultez les calendriers des avocats pour leurs disponibilités.</p>
</header>

<!-- Section : Calendriers des disponibilités -->
<div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Calendriers des Avocats</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php while ($avocat = mysqli_fetch_assoc($resultAvocats)): ?>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                <!-- Informations de l'avocat -->
                <img src="uploads/<?php echo htmlspecialchars($avocat['picture']); ?>" alt="Avatar" class="w-24 h-24 rounded-full mx-auto">
                <h3 class="text-lg font-bold text-center mt-4">
                    <?php echo htmlspecialchars($avocat['name'] . ' ' . $avocat['first_name']); ?>
                </h3>
                <p class="text-gray-600 text-center mt-2">Spécialité : <?php echo htmlspecialchars($avocat['specialite']); ?></p>

                <!-- Calendrier -->
                <div class="mt-4">
                    <h4 class="text-md font-semibold text-gray-700 mb-2">Disponibilités :</h4>
                    <?php if (isset($disponibilites[$avocat['us_id']])): ?>
                        <div class="grid grid-cols-3 gap-2">
                            <?php foreach ($disponibilites[$avocat['us_id']] as $dispo): ?>
                                <div class="bg-green-200 text-center py-2 px-3 rounded">
                                    <?php echo date('d/m/Y', strtotime($dispo['dispo_date'])); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Pas de disponibilités pour le moment.</p>
                    <?php endif; ?>
                </div>

                <!-- Bouton pour Réserver -->
                <div class="mt-4 text-center">
                    <button onclick="openReservationModal(<?php echo $avocat['us_id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Réserver
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- MODAL de réservation -->
<div id="reservationModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-11/12 md:w-1/2">
        <button id="closeModal" class="absolute top-4 right-4 text-red-500 text-xl">&times;</button>
        <h2 class="text-xl font-bold text-center mb-4">Réserver une consultation</h2>
        <form action="create_reservation.php" method="POST">
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <input type="hidden" name="avocat_id" id="avocat_id">
            <div class="mb-4">
                <input type="date" name="date" class="w-full p-4 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-4 rounded-lg hover:bg-blue-700">Réserver</button>
        </form>
        <?php if (isset($disponibilites[$avocat['us_id']])): ?>
    <div class="grid grid-cols-3 gap-2">
        <?php foreach ($disponibilites[$avocat['us_id']] as $dispo): ?>
            <div class="bg-green-200 text-center py-2 px-3 rounded">
                <?php echo date('d/m/Y', strtotime($dispo['dispo_date'])); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-gray-500 text-sm">Pas de disponibilités pour le moment.</p>
<?php endif; ?>

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

