<?php

// Connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'avocat';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

// Fonction pour récupérer les spécialités depuis la table specialite
function getSpecialites($conn) {
    $query = "SELECT * FROM specialite";
    $result = mysqli_query($conn, $query);
    $specialites = [];
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $specialites[] = $row;
        }
    }
    
    return $specialites;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupération des données du formulaire
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $specialite = mysqli_real_escape_string($conn, $_POST['specialite']);
    
    // Gestion du fichier d'image
    $imageData = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageData = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    }
    
    // Validation des champs obligatoires
    if (empty($name) || empty($prenom) || empty($email) || empty($password)) {
        die("Tous les champs obligatoires doivent être remplis.");
    }
    
    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Insertion dans la table utilisateur
    $queryUser = "INSERT INTO utilisateur (name, first_name, email, phone_number, mot_de_passe, role) 
                   VALUES ('$name', '$prenom', '$email', '$number', '$hashedPassword', 'Avocat')";
    
    if (mysqli_query($conn, $queryUser)) {
        $userId = mysqli_insert_id($conn);
        
        // Insertion des informations détaillées dans la table infos
        $queryInfo = "INSERT INTO infos (avocat_id, specialite, biography, annee_experience, picture, location) 
                      VALUES ('$userId', '$specialite', '$bio', '$experience', '$imageData', '$location')";
        
        if (mysqli_query($conn, $queryInfo)) {
            echo "Inscription réussie !";
        } else {
            echo "Erreur lors de l'insertion des informations : " . mysqli_error($conn);
        }
    } else {
        echo "Erreur lors de l'inscription : " . mysqli_error($conn);
    }
}

mysqli_close($conn);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information de l'avocat</title>
    <link rel="stylesheet" href="../../assets/css/avocatreg.css">
</head>
<body>
    <div id="maindiv">
        <div id="from">
            <h2 style="margin-bottom: -20px;">Créer un compte</h2>
            <h4 style="margin-bottom: 20px;">Ici pour les Avocat</h4>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" class="input" placeholder="Entrez votre Nom" required><br>
                <input type="text" name="prenom" class="input" placeholder="Entrez votre Prénom" required><br>
                <input type="email" name="email" class="input" placeholder="Entrez votre email" required><br>
                <input type="password" name="password" class="input" placeholder="Entrez votre mot de passe" required><br>
                <input type="text" name="bio" class="input" placeholder="Entrez votre Biography" required><br>
                <input type="number" name="experience" class="input" placeholder="Entrer les années d'experience" required><br>
                <input type="text" name="address" class="input" placeholder="Enter your address" required><br>
                <input type="tel" name="number" class="input" placeholder="Entrez votre Num de téléphone" required><br>
                <input type="text" name="location" class="input" placeholder="Entrez votre location de cabinet" required><br>
                <select name="specialite" id="spe" required>
                    <option value="" disabled selected>Choisissez votre spécialité</option>
                    <option value="droit_penal">Droit pénal</option>
                    <option value="droit_civil">Droit civil</option>
                    <option value="droit_fiscal">Droit fiscal</option>
                    <option value="droit_commercial">Droit commercial</option>
                    <option value="droit_social">Droit social</option>
                    <option value="droit_international">Droit international</option>
                    <option value="droit_immobilier">Droit immobilier</option>
                    <option value="droit_numérique">Droit du numérique</option>
                    <option value="droit_environnemental">Droit environnemental</option>
                    <option value="droit_des_affaires">Droit des affaires</option>
                </select><br>
                <input type="file" name="image" class="input" required><br>
                <p class="terms"><input type="checkbox" required> En cliquant, vous acceptez <br> AvocatConnect Conditions d'utilisation et politique de confidentialité.</p>
                <button type="submit" id="Signupbtn">Inscrire</button>
                <p class="temp">Vous avez déjà un compte ? <a href="../userlogin.php">Connecter</a></p>
            </form>
        </div>
    </div>
    <div id="conditions">
        <p>Conditions d'utilisation Avis de confidentialité Aide <br>© 2024-2030, Inc.</p>
    </div>
</body>
</html>
