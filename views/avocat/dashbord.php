<?php
// Démarrer la session
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Avocat') {
    header("Location: ../login.php");
    exit();
}

// Affiche les informations de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];


// Connexion à la base de données pour récupérer plus d'infos
$conn = mysqli_connect('localhost', 'root', '', 'avocat');
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$query = "SELECT * FROM infos WHERE avocat_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $avocatInfo = mysqli_fetch_assoc($result);
    
    $picture=$avocatInfo['picture'];
    
} else {
    echo "<p>Aucune information supplémentaire disponible</p>";
}

// Requête SQL pour récupérer les réservations liées à l'avocat connecté
$query3 = "
    SELECT r.reservation_id, r.reservation_date, r.statut, 
           u1.name AS client_name, u2.name AS avocat_name 
    FROM reservations r 
    JOIN utilisateur u1 ON r.client_id = u1.us_id 
    JOIN utilisateur u2 ON r.avocat_id = u2.us_id 
    WHERE r.avocat_id = $user_id 
    ORDER BY r.reservation_date DESC
";

$result1 = mysqli_query($conn, $query3);

if (!$result1) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

$query4 = "
    SELECT DISTINCT u1.us_id, u1.name, u1.email, u1.phone_number 
    FROM reservations r 
    JOIN utilisateur u1 ON r.client_id = u1.us_id 
    WHERE r.avocat_id = $user_id
    ORDER BY u1.name ASC
";

$result3 = mysqli_query($conn, $query4);

if (!$result3) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}
// Requête SQL pour récupérer les informations de l'avocat
$query6 = "
    SELECT u.name, u.first_name, u.email, u.phone_number, 
           i.specialite, i.biography, i.annee_experience, i.picture, i.location 
    FROM utilisateur u 
    JOIN infos i ON u.us_id = i.avocat_id 
    WHERE u.us_id = $user_id
";

$result4 = mysqli_query($conn, $query6);

if (mysqli_num_rows($result4) > 0) {
    $avocat = mysqli_fetch_assoc($result4);
} else {
    die("Aucun avocat trouvé.");
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Avocat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<button data-drawer-target="cta-button-sidebar" data-drawer-toggle="cta-button-sidebar" aria-controls="cta-button-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="cta-button-sidebar" class="fixed z-20  top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 " aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
      <ul class="space-y-2 font-medium">
        <li>
                <div class="flex  w-full h-auto">
                    <a href="#" class="scale-[4]">
                        <img class="w-10 m-5 -mr-5" src="../../assets/images/logo.png" alt="Logo du site" />
                    </a>
                </div>
        </li>
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li>
            <button  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <!-- <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
               </svg> -->
               <span class="flex-1 ms-3 whitespace-nowrap"></span>
            </button>
         </li>

         <button class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
    </svg>
    ok
</button>

<!-- Le modal que tu veux afficher -->
<div id="affiche" class="hidden">
    <p>Voici le contenu du modal.</p>
    <button id="closeModalBtn">Fermer</button>
</div>

<script>
    const showProfileBtn = document.getElementById('showProfileBtn');
    const affiche = document.getElementById('affiche');

    // Affiche le modal lorsqu'on clique sur le bouton
    showProfileBtn.addEventListener('click', () => affiche.classList.remove('hidden'));
</script>




<script>
    const showProfileBtn = document.getElementById('showProfileBtn');
    const affiche = document.getElementById('affiche');

    // Affiche le modal lorsqu'on clique sur le bouton
    showProfileBtn.addEventListener('click', () => affiche.classList.remove('hidden'));
</script>









         <!--  -->
         <li>
            <a href="#reservation" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Réservations</span>
            </a>
         </li>
         <li>
            <a href="#client" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Mes Clients</span>
            </a>
         </li>
        
         <li>
            <a href="../logout.php" class="flex items-center p-2 text-[15px] font-medium text-white leading-[26px] py-[9px] px-[25px] bg-gradient-to-r from-[#EC008C] via-[#fc6767] to-[#EC008C] rounded-full shadow-md transition-all duration-200 ease-in-out hover:bg-gradient-to-r hover:from-[#EC008C] hover:to-[#fc6767] group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                  <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                  <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
            </a>
         </li>
      </ul>
      <div id="dropdown-cta" class="p-4 mt-6 rounded-lg bg-blue-50 dark:bg-blue-900" role="alert">
     
   </div>
</aside>

<div class="p-4 sm:ml-64">
<div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Tableau de Bord du  <?php echo "$name"; ?> </h1>
                <div class="flex items-center space-x-4">
                    <button id="showProfileBtn"  class="relative">
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                    <img class="h-10 w-10 rounded-full" src="<?php echo'$picture'; ?>" alt="Profile">
                </div>
            </div>
        </header>
        <div id="affiche" class="fixed inset-0 w-3/4 sm:scale[1/2] ml-[300px] bg-gray-900 bg-opacity-50 z-100 flex justify-center items-center hidden">
    <div class="p-16">
        <div class="p-8 bg-white shadow mt-24">
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="grid grid-cols-3 text-center order-last md:order-first mt-20 md:mt-0">
                    <div>
                        <p class="font-bold text-gray-700 text-xl"><?php $query1 = "SELECT COUNT(DISTINCT client_id) AS nombre_clients FROM reservations WHERE avocat_id = $user_id";

                                $result = mysqli_query($conn, $query1);

                                if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $nombre_clients = $row['nombre_clients'];
                                    echo "$nombre_clients";
                                } else {
                                    echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
                                } ?></p>
                        <p class="text-gray-400">Clients</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 text-xl"><?php echo $avocat['annee_experience']; ?></p>
                        <p class="text-gray-400">Années d'expérience</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 text-xl">10</p>
                        <p class="text-gray-400">Commentaires</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="w-48 h-48 bg-indigo-100 mx-auto rounded-full shadow-2xl absolute inset-x-0 top-0 -mt-24 flex items-center justify-center">
                        <img src="C:/Users/safiy/OneDrive/Images/<?php echo $avocat['picture']; ?>" alt="Profil de l'avocat" class="w-48 h-48 rounded-full object-cover">
                    </div>
                </div>

                <div class="space-x-8 flex justify-between mt-32 md:mt-0 md:justify-center">
                    <button class="text-white py-2 px-4 uppercase rounded bg-blue-400 hover:bg-blue-500 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                        Connecter
                    </button>
                    <button id="closeModalBtn" class="text-white py-2 px-4  rounded bg-gray-700 hover:bg-gray-800 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                        Close
                    </button>
                </div>
            </div>

            <div class="mt-20 text-center border-b pb-12">
                <h1 class="text-4xl font-medium text-gray-700">
                    <?php echo $avocat['name'] . ' ' . $avocat['first_name']; ?>
                </h1>
                <p class="font-light text-gray-600 mt-3"><?php echo $avocat['location']; ?></p>
                <p class="mt-8 text-gray-500">Spécialité : <?php echo $avocat['specialite']; ?></p>
                <p class="mt-2 text-gray-500"><?php echo $avocat['email']; ?></p>
                <p class="mt-2 text-gray-500"><?php echo $avocat['phone_number']; ?></p>
            </div>

            <div class="mt-12 flex flex-col justify-center">
                <p class="text-gray-600 text-center font-light lg:px-16">
                    <?php echo $avocat['biography']; ?>
                </p>
                <button class="text-indigo-500 py-2 px-4 font-medium mt-4">
                    Afficher plus
                </button>  
            </div>
        </div>
    </div>
</div>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Grid -->
                <div id="stats" class="flex flex-row flex-wrap">
                    <div class="w-full max-w-full flex-shrink px-4">
                        <p class="mb-5 mt-3 text-xl font-bold">Statistiques</p>
                    </div>
                    <div class="mb-10 w-full max-w-full flex-shrink px-8 sm:w-1/2 lg:w-1/4">
                        <div class="h-full rounded-xl bg-white shadow-2xl">
                        <div x-data="{ tooltips: false }" class="relative px-6 pt-6 text-sm font-semibold">
                            Total Client
                            <div x-on:mouseover="tooltips = true" x-on:mouseleave="tooltips = false" class="text-green-500 ltr:float-right rtl:float-left">
                            +12%
                            <div class="absolute bottom-full top-auto mb-3" x-show.transition.origin.top="tooltips" style="display: none;">
                                <div class="z-40 -mb-1 w-32 rounded-lg bg-black p-2 text-center text-sm leading-tight text-white shadow-lg">Since last month</div>
                                <div class="absolute bottom-0 -mb-2 w-1 -rotate-45 transform bg-black p-1 ltr:ml-6 rtl:mr-6"></div>
                            </div>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between px-6 py-4">
                            <div class="relative h-14 w-14 self-center rounded-full bg-rose-500 text-center text-pink-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person absolute left-1/2 top-1/2 h-8 w-8 -translate-x-1/2 -translate-y-1/2 transform" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"></path>
                                </svg>
                            </div>
                            <h2 class="self-center text-3xl font-bold"><?php $query1 = "SELECT COUNT(DISTINCT client_id) AS nombre_clients FROM reservations WHERE avocat_id = $user_id";

                            $result = mysqli_query($conn, $query1);

                            if ($result) {
                                $row = mysqli_fetch_assoc($result);
                                $nombre_clients = $row['nombre_clients'];
                                echo "$nombre_clients";
                            } else {
                                echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
                            } ?></h2>
                        </div>
                        <div class="px-6 pb-6">
                            <a class="text-sm hover:text-indigo-500" href="#">View more...</a>
                        </div>
                        </div>
                    </div>
                    <div class="mb-10 w-full max-w-full flex-shrink px-8 sm:w-1/2 lg:w-1/4">
                        <div class="h-full rounded-xl bg-white shadow-2xl">
                        <div x-data="{ tooltips: false }" class="relative px-6 pt-6 text-sm font-semibold">
                        Nombre de demandes en attente.
                            <div x-on:mouseover="tooltips = true" x-on:mouseleave="tooltips = false" class="text-green-500 ltr:float-right rtl:float-left">
                            +15%
                            <div class="absolute bottom-full top-auto mb-3" x-show.transition.origin.top="tooltips" style="display: none;">
                                <div class="z-40 -mb-1 w-32 rounded-lg bg-black p-2 text-center text-sm leading-tight text-white shadow-lg">Since last month</div>
                                <div class="absolute bottom-0 -mb-2 w-1 -rotate-45 transform bg-black p-1 ltr:ml-6 rtl:mr-6"></div>
                            </div>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between px-6 py-4">
                            <div class="relative h-14 w-14 self-center rounded-full bg-yellow-500 text-center text-yellow-50">
                            <img src="../../assets/images/consultation.svg" alt="">
                            </div>
                            <h2 class="self-center text-3xl font-bold"><?php $query2 = "SELECT COUNT(*) AS nombre_demandes_en_attente FROM reservations WHERE avocat_id = $user_id AND statut = 'en_attente'";

                                                                            $result = mysqli_query($conn, $query2);

                                                                            if ($result) {
                                                                                $row = mysqli_fetch_assoc($result);
                                                                                $nombre_demandes_en_attente = $row['nombre_demandes_en_attente'];
                                                                                echo "$nombre_demandes_en_attente";
                                                                            } else {
                                                                                echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
                                                                            } ?></h2>
                        </div>
                        <div class="px-6 pb-6">
                            <a class="text-sm hover:text-indigo-500" href="#">View more...</a>
                        </div>
                        </div>
                    </div>
                    <div class="mb-10 w-full max-w-full flex-shrink px-8 sm:w-1/2 lg:w-1/4">
                        <div class="h-full rounded-xl bg-white shadow-2xl">
                        <div x-data="{ tooltips: false }" class="relative px-6 pt-6 text-sm font-semibold">
                        Nombre de demandes approuvées pour la journée.
                            <div x-on:mouseover="tooltips = true" x-on:mouseleave="tooltips = false" class="text-pink-500 ltr:float-right rtl:float-left">
                            -5%
                            <div class="absolute bottom-full top-auto mb-3" x-show.transition.origin.top="tooltips" style="display: none;">
                                <div class="z-40 -mb-1 w-32 rounded-lg bg-black p-2 text-center text-sm leading-tight text-white shadow-lg">Since last month</div>
                                <div class="absolute bottom-0 -mb-2 w-1 -rotate-45 transform bg-black p-1 ltr:ml-6 rtl:mr-6"></div>
                            </div>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between px-6 py-4">
                            <div class="relative h-14 w-14 self-center rounded-full bg-green-500 text-center text-green-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person absolute left-1/2 top-1/2 h-8 w-8 -translate-x-1/2 -translate-y-1/2 transform" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"></path>
                                </svg>
                            </div>
                            <h2 class="self-center text-3xl font-bold"><?php $query = "SELECT COUNT(*) AS nombre_demandes_approuvees 
                                                                                        FROM reservations 
                                                                                        WHERE avocat_id = $user_id 
                                                                                        AND statut = 'confirmee' 
                                                                                        AND DATE(reservation_date) = CURDATE()";

                                                                                $result = mysqli_query($conn, $query);

                                                                                if ($result) {
                                                                                    $row = mysqli_fetch_assoc($result);
                                                                                    $nombre_demandes_approuvees = $row['nombre_demandes_approuvees'];
                                                                                    echo "$nombre_demandes_approuvees";
                                                                                } else {
                                                                                    echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
                                                                                } ?></h2>
                        </div>
                        <div class="px-6 pb-6">
                            <a class="text-sm hover:text-indigo-500" href="#">View more...</a>
                        </div>
                        </div>
                    </div>
                    <div class="mb-10 w-full max-w-full flex-shrink px-8 sm:w-1/2 lg:w-1/4">
                        <div class="h-full rounded-xl bg-white shadow-2xl">
                        <div x-data="{ tooltips: false }" class="relative px-6 pt-6 text-sm font-semibold">Nombre de demandes approuvées pour le jour suivant.<span class="mt-1 h-2 w-2 animate-pulse rounded-full bg-green-500 ltr:float-right rtl:float-left"></span></div>
                        <div class="flex flex-row justify-between px-6 py-4">
                            <div class="relative h-14 w-14 self-center rounded-full bg-indigo-500 text-center text-indigo-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-people absolute left-1/2 top-1/2 h-8 w-8 -translate-x-1/2 -translate-y-1/2 transform" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path>
                            </svg>
                            </div>
                            <h2 class="self-center text-3xl font-bold"><?php $query = "SELECT COUNT(*) AS nombre_demandes_approuvees 
                                                                                FROM reservations 
                                                                                WHERE avocat_id = $user_id 
                                                                                AND statut = 'confirmee' 
                                                                                AND DATE(reservation_date) = CURDATE() + INTERVAL 1 DAY";

                                                                        $result = mysqli_query($conn, $query);

                                                                        if ($result) {
                                                                            $row = mysqli_fetch_assoc($result);
                                                                            $nombre_demandes_approuvees = $row['nombre_demandes_approuvees'];
                                                                            echo "$nombre_demandes_approuvees";
                                                                        } else {
                                                                            echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
                                                                        } ?></h2>
                        </div>
                        <div class="px-6 pb-6">
                            <a class="text-sm hover:text-indigo-500" href="#">View more...</a>
                        </div>
                        </div>
                    </div>
                </div>

            <!-- Détails du prochain client et de sa réservation. -->
            <div id="detail"  class="mt-8">
                <h2 class="text-lg font-medium text-gray-900">Rendez-vous à venir</h2>
                <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Jean Martin</p>
                                        <p class="text-sm text-gray-500">Consultation initiale</p>
                                    </div>
                                </div>
                                <div class="ml-6 flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmé</span>
                                    <p class="ml-4 text-sm text-gray-500">Aujourd'hui à 14:30</p>
                                </div>
                            </div>
                        </li>
                        <!-- More appointments... -->
                    </ul>
                </div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 py-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Demandes en attente</h2>
                    <div id="pendingAppointments" class="grid gap-4">
                        <!-- Pending appointments will be inserted here -->
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-semibold mb-4">Rendez-vous confirmés</h2>
                    <div id="confirmedAppointments" class="grid gap-4">
                        <!-- Confirmed appointments will be inserted here -->
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-[#EC008C] via-[#fc6767] to-[#EC008C] rounded-full shadow-md w-1/2 justify-self-center  text-white text-center p-6">
                
                <p class="text-sm mt-2">Consultez toutes vos réservations et suivez leur statut</p>
            </div>
            <div id="reservation" class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
                            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Vos Réservations</h2>

                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-200 text-gray-700 text-left">
                                            <th class="py-3 px-4">ID Réservation</th>
                                            <th class="py-3 px-4">Client</th>
                                            <th class="py-3 px-4">Date de Réservation</th>
                                            <th class="py-3 px-4">Statut</th>
                                            <th class="py-3 px-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <?php while ($row = mysqli_fetch_assoc($result1)): ?>
                                            <tr class="border-b hover:bg-gray-100">
                                                <td class="py-3 px-4"><?php echo $row['reservation_id']; ?></td>
                                                <td class="py-3 px-4"><?php echo $row['client_name']; ?></td>
                                                <td class="py-3 px-4"><?php echo date('d/m/Y', strtotime($row['reservation_date'])); ?></td>
                                                <td class="py-3 px-4">
                                                    <?php 
                                                        $statutClass = $row['statut'] === 'confirmee' ? 'bg-green-500' : ($row['statut'] === 'en_attente' ? 'bg-yellow-500' : 'bg-red-500');
                                                    ?>
                                                    <span class="text-white py-1 px-3 rounded-full text-sm <?php echo $statutClass; ?>">
                                                        <?php echo ucfirst($row['statut']); ?>
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Voir</button>
                                                    <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Annuler</button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-center text-gray-500 mt-8">Aucune réservation trouvée.</p>
                            <?php endif; ?>

            </div>
            <div class="bg-gradient-to-r from-[#EC008C] via-[#fc6767] to-[#EC008C] rounded-full shadow-md shadow-md w-1/2 justify-self-center text-white text-center p-6 pt-6">
                <h1 class="text-3xl font-bold">Liste des clients</h1>
                <p class="text-sm mt-2">Consultez la liste des clients ayant réservé vos services</p>
            </div>
            <div id="client" class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Clients ayant réservé vos services</h2>

                        <?php if (mysqli_num_rows($result3) > 0): ?>
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-200 text-gray-700 text-left">
                                        <th class="py-3 px-4">ID Client</th>
                                        <th class="py-3 px-4">Nom du client</th>
                                        <th class="py-3 px-4">Email</th>
                                        <th class="py-3 px-4">Numéro de téléphone</th>
                                        <th class="py-3 px-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                                        <tr class="border-b hover:bg-gray-100">
                                            <td class="py-3 px-4"><?php echo $row['us_id']; ?></td>
                                            <td class="py-3 px-4"><?php echo $row['name']; ?></td>
                                            <td class="py-3 px-4"><?php echo $row['email']; ?></td>
                                            <td class="py-3 px-4"><?php echo $row['phone_number']; ?></td>
                                            <td class="py-3 px-4">
                                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Voir Profil</button>
                                                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Supprimer</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center text-gray-500 mt-8">Aucun client n'a encore réservé vos services.</p>
                        <?php endif; ?>

            </div>
        </main>
    </div>
</div>
<!-- profile d'utilisateur  -->

<div  class="fixed inset-0 w-3/4 sm:scale[1/2] ml-[300px] bg-gray-900 bg-opacity-50 z-100 flex justify-center items-center hidden">
<div class="p-16">
    <div class="p-8 bg-white shadow mt-24">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="grid grid-cols-3 text-center order-last md:order-first mt-20 md:mt-0">
                <div>
                    <p class="font-bold text-gray-700 text-xl"><?php $query1 = "SELECT COUNT(DISTINCT client_id) AS nombre_clients FROM reservations WHERE avocat_id = $user_id";

                            $result = mysqli_query($conn, $query1);

                            if ($result) {
                                $row = mysqli_fetch_assoc($result);
                                $nombre_clients = $row['nombre_clients'];
                                echo "$nombre_clients";
                            } else {
                                echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
                            } ?></p>
                    <p class="text-gray-400">Clients</p>
                </div>
                <div>
                    <p class="font-bold text-gray-700 text-xl"><?php echo $avocat['annee_experience']; ?></p>
                    <p class="text-gray-400">Années d'expérience</p>
                </div>
                <div>
                    <p class="font-bold text-gray-700 text-xl">10</p>
                    <p class="text-gray-400">Commentaires</p>
                </div>
            </div>

            <div class="relative">
                <div class="w-48 h-48 bg-indigo-100 mx-auto rounded-full shadow-2xl absolute inset-x-0 top-0 -mt-24 flex items-center justify-center">
                    <img src="C:/Users/safiy/OneDrive/Images/<?php echo $avocat['picture']; ?>" alt="Profil de l'avocat" class="w-48 h-48 rounded-full object-cover">
                </div>
            </div>

            <div class="space-x-8 flex justify-between mt-32 md:mt-0 md:justify-center">
                <button class="text-white py-2 px-4 uppercase rounded bg-blue-400 hover:bg-blue-500 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                    Connecter
                </button>
                <button id="closeModalBtn" class="text-white py-2 px-4  rounded bg-gray-700 hover:bg-gray-800 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                    Close
                </button>
            </div>
        </div>

        <div class="mt-20 text-center border-b pb-12">
            <h1 class="text-4xl font-medium text-gray-700">
                <?php echo $avocat['name'] . ' ' . $avocat['first_name']; ?>
            </h1>
            <p class="font-light text-gray-600 mt-3"><?php echo $avocat['location']; ?></p>
            <p class="mt-8 text-gray-500">Spécialité : <?php echo $avocat['specialite']; ?></p>
            <p class="mt-2 text-gray-500"><?php echo $avocat['email']; ?></p>
            <p class="mt-2 text-gray-500"><?php echo $avocat['phone_number']; ?></p>
        </div>

        <div class="mt-12 flex flex-col justify-center">
            <p class="text-gray-600 text-center font-light lg:px-16">
                <?php echo $avocat['biography']; ?>
            </p>
            <button class="text-indigo-500 py-2 px-4 font-medium mt-4">
                Afficher plus
            </button>  
        </div>
    </div>
</div>
</div>

 <!-- -------------------------------------------------------------------------------------- -->
    <!-- <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/utils/date-formatter.js"></script> -->
    <!-- <script src="../../assets/js/avocat.js"></script> -->
   <?php mysqli_close($conn); ?>
</body>
</html>