<?php
// Connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'avocat';
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Récupération des données du formulaire
$client_id = $_POST['client_id'];
$avocat_id = $_POST['lawyer'];
$date = $_POST['date'];

// Vérification de la disponibilité
$queryDispo = "SELECT dispo_id FROM disponibilite WHERE avocat_id = $avocat_id AND dispo_date = '$date' AND statut = 'disponible'";
$resultDispo = mysqli_query($conn, $queryDispo);

if (mysqli_num_rows($resultDispo) > 0) {
    $dispo = mysqli_fetch_assoc($resultDispo)['dispo_id'];

    // Insertion dans la table reservations
    $queryInsert = "INSERT INTO reservations (avocat_id, client_id, dispo_id, reservation_date) VALUES ($avocat_id, $client_id, $dispo, '$date')";
    if (mysqli_query($conn, $queryInsert)) {
        echo "Réservation enregistrée avec succès !";
        header("Location :dashbord.php")
    } else {
        echo "Erreur lors de l'enregistrement : " . mysqli_error($conn);
    }
} else {
    echo "Créneau indisponible pour cet avocat.";
}

mysqli_close($conn);
?>
