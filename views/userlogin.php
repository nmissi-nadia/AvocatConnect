<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'avocat';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Vérification du rôle (Client ou Avocat)
    $query = "SELECT * FROM utilisateur WHERE email = '$email'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        echo "<script>console.log($user);</script>";
        // Vérification du mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            // Démarrage de la session
            session_start();
            $_SESSION['user_id'] = $user['us_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirection vers le bon dashboard
           
                header("Location: avocat/dashboard.php");
            
            exit();
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Mot de passe incorrect']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Email introuvable ou rôle incorrect']);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>AVOCATCONNECT</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/userlogin.css">

</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up-container">

            <form action="" id="form1">
                <h1>Créer un compte</h1>
                <div class="social-container">

                </div>
                <span> Utilisez votre email pour vous inscrire</span>
                <input type="text" name="name" placeholder="Name" id="name" required>
                <input type="email" name="email" placeholder="Email" id="email" required>
                <input type="password" name="password" placeholder="Password" id="password" required>
                <input type="phone" name="phone" placeholder="Phone Number" id="phone" required>
                <input type="city" name="city" placeholder="City" id="city" required>
                <input type="age" name="age" placeholder="Age" id="age" required>


                <button id="button1">Inscrire</button>

                <!-- <input type="submit" value="Submit" id="button1"> -->
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="" id="form2">
                <h1 id="head">Se connecter</h1>

                <span>Utilisez votre compte</span>
                <input type="email" name="email" placeholder="Email" id="email-log" required>
                <input type="password" name="password" placeholder="Mot de passe" id="password-log" required>
                <select style="background-color: #ffffff;    width: 60px;" id="role">
                    <option value="user">Client</option>
                    <option value="avoc">Avocat</option>

                </select>

                <button id="button2">Connecter</button>
                <!-- <input type="submit" value="Submit" id="button2"> -->

            </form>

        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>

                    <p>Pour rester en contact avec nous, veuillez vous connecter avec vos informations personnelles</p>
                    <button class="ghost" id="signIn">Se connecter</button>

                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Bonjour !</h1>
                    <p>Entrez vos coordonnées et commencez à réserver avec nous</p>
                    <button class="ghost" id="signUp">Inscrire</button>

                    <a href="avocat/avocatreg.php">
                        <h3 class="rounded-[20px] border-[1px] border-[solid] border-[#20a87e] bg-[#20a87e] text-[#FFFFFF] text-[12px] font-bold px-[45px] py-[12px] tracking-[1px] uppercase [transition:transform_80ms_ease-in]" h>Inscription d'un avocat</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>



</body>

</html>

<script type="text/javascript" src="../assets/js/userloginscript.js">






</script>