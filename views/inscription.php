<?php 
require "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données du formulaire
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $first_name = mysqli_real_escape_string($conn, $_POST['fi_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone']);

    // Validation des champs obligatoires
    if (empty($name) || empty($first_name) || empty($email) || empty($password) || empty($phone_number)) {
        die("Tous les champs doivent être remplis.");
    }

    // Vérification de l'unicité de l'email et du numéro de téléphone
    $checkQuery = "SELECT * FROM utilisateur WHERE email = '$email' OR phone_number = '$phone_number'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        die("L'email ou le numéro de téléphone existe déjà.");
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Requête d'insertion dans la table utilisateur
    $query = "INSERT INTO utilisateur (name, first_name, email, phone_number, mot_de_passe, role) 
              VALUES ('$name', '$first_name', '$email', '$phone_number', '$hashedPassword', 'Client')";

    if (mysqli_query($conn, $query)) {
        echo "Inscription réussie !";
        // Vous pouvez rediriger l'utilisateur après l'inscription réussie
         header('Location: userlogin.php');
        exit();
    } else {
        echo "<script>alert('Erreur lors de l'insertion : ');</script>" . mysqli_error($conn);
    }
}

?>