<?php
// Connexion à la base de données
require "../db_connect.php";

$type = $_GET['type'] ?? '';
console.log("hi");
$query = $type 
    ? "SELECT u.us_id AS id, u.name, u.first_name FROM utilisateur u JOIN infos i ON u.us_id = i.avocat_id WHERE i.specialite = '$type'"
    : "SELECT u.us_id AS id, u.name, u.first_name FROM utilisateur u WHERE u.role = 'Avocat'";

$result = mysqli_query($conn, $query);
$lawyers = [];
while ($row = mysqli_fetch_assoc($result)) {
    $lawyers[] = $row;
}
console.log($result);
echo json_encode($lawyers);
mysqli_close($conn);
?>
