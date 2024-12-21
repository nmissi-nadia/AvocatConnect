<?php
// Démarrer la session
require "../db_connect.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header("Location: ../login.php");
    exit();
}

// Affiche les informations de l'utilisateur connecté
$client_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];



// Requête SQL pour récupérer les informations personnelles du client
$query = "SELECT * FROM utilisateur WHERE us_id = $client_id";
$result = mysqli_query($conn, $query);
$client = mysqli_fetch_assoc($result);

// Requête SQL pour récupérer la liste des avocats
$queryAvocats = "SELECT * FROM utilisateur WHERE role = 'Avocat'";
$resultAvocats = mysqli_query($conn, $queryAvocats);

// Requête SQL pour récupérer les réservations du client
$queryReservations = "
    SELECT r.reservation_id, r.reservation_date, r.statut, 
           u.name AS avocat_name 
    FROM reservations r 
    JOIN utilisateur u ON r.avocat_id = u.us_id 
    WHERE r.client_id = $client_id 
    ORDER BY r.reservation_date DESC
";
$resultReservations = mysqli_query($conn, $queryReservations);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">



<button data-drawer-target="cta-button-sidebar" data-drawer-toggle="cta-button-sidebar" aria-controls="cta-button-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="cta-button-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
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
               <svg class="w-5 h-5 bg-pink text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
        
         
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Avocats</span>
            </a>
         </li>
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Consultation</span>
            </a>
         </li>
        
         <li>
            <a href="../logout.php" class="flex items-center p-2 text-gray-900 bg-gradient-to-r from-[#EC008C] via-[#fc6767] to-[#EC008C] rounded-full shadow-md rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                  <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                  <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
            </a>
         </li>
      </ul>
      
   </div>
</aside>

<div class="p-4 sm:ml-64">
<div class="min-h-screen">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-semibold text-gray-900">Mon Espace Client :<?php echo "$name "; ?></h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <button onclick="" class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Prendre RDV</h3>
                    <p class="mt-2 text-sm text-gray-500">Réserver une consultation</p>
                </button>

                     <div class="max-w-4xl mx-auto p-6">
                           <div id="calendar" class="bg-white rounded-lg shadow-lg p-6">
                                 <div class="flex items-center justify-between mb-6">
                                    <button id="prevMonth" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                    </button>
                                    <h2 id="currentMonth" class="text-2xl font-semibold"></h2>
                                    <button id="nextMonth" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                    </button>
                                 </div>

                                 <div class="grid grid-cols-7 gap-2 mb-4">
                                    <div class="text-center font-semibold text-gray-600 py-2">Dim</div>
                                    <div class="text-center font-semibold text-gray-600 py-2">Lun</div>
                                    <div class="text-center font-semibold text-gray-600 py-2">Mar</div>
                                    <div class="text-center font-semibold text-gray-600 py-2">Mer</div>
                                    <div class="text-center font-semibold text-gray-600 py-2">Jeu</div>
                                    <div class="text-center font-semibold text-gray-600 py-2">Ven</div>
                                    <div class="text-center font-semibold text-gray-600 py-2">Sam</div>
                                 </div>

                                 <div id="calendarDays" class="grid grid-cols-7 gap-2"></div>
                           </div>
                     </div>

                     <!-- Modal -->
                     <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
                        <div class="bg-white rounded-lg max-w-md w-full p-6">
                              <div class="flex justify-between items-center mb-6">
                                 <h3 id="modalDate" class="text-xl font-semibold"></h3>
                                 <button id="closeModal" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                 </button>
                              </div>

                              <form id="appointmentForm">
                                 <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                          Type de jugement
                                    </label>
                                    <select id="judgmentType" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                          <option value="">Sélectionnez un type</option>
                                          <option value="Civil">Civil</option>
                                          <option value="Criminal">Pénal</option>
                                          <option value="Family">Famille</option>
                                          <option value="Commercial">Commercial</option>
                                          <option value="Administrative">Administratif</option>
                                    </select>
                                 </div>

                                 <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                          Avocat
                                    </label>
                                    <select id="lawyer" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required disabled>
                                          <option value="">Sélectionnez d'abord un type de jugement</option>
                                    </select>
                                 </div>

                                 <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                                          Confirmer le rendez-vous
                                    </button>
                                 </div>
                              </form>
                        </div>
                     </div>
            </div>


                  <div class="max-w-7xl mx-auto px-4 py-8">
                        <div class="mb-8">
                              <h2 class="text-2xl font-semibold mb-4">Rendez-vous à venir</h2>
                              <div id="upcomingAppointments" class="grid gap-4">
                                 <!-- Upcoming appointments will be inserted here -->
                              </div>
                        </div>

                        <div>
                              <h2 class="text-2xl font-semibold mb-4">Historique des rendez-vous</h2>
                              <div id="pastAppointments" class="grid gap-4">
                                 <!-- Past appointments will be inserted here -->
                              </div>
                        </div>
                  </div>

               <!-- Modal de modification -->
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
               <div class="bg-white rounded-lg max-w-md w-full p-6 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                     <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Modifier le rendez-vous</h3>
                        <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                           </svg>
                        </button>
                     </div>
                     <form id="editForm" class="space-y-4">
                        <input type="hidden" id="editAppointmentId">
                        <div>
                           <label class="block text-sm font-medium text-gray-700">Date</label>
                           <input type="date" id="editDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                           <label class="block text-sm font-medium text-gray-700">Heure</label>
                           <input type="time" id="editTime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex justify-end space-x-3">
                           <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                 Annuler
                           </button>
                           <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                 Sauvegarder
                           </button>
                        </div>
                     </form>
               </div>
            </div>
            <!-- Upcoming Appointments -->
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Mes Prochains Rendez-vous</h2>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Me. Marie Dubois</p>
                                        <p class="text-sm text-gray-500">Droit de la famille</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmé</span>
                                    <p class="ml-4 text-sm text-gray-500">Demain à 15:00</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php 
               include "avocat2.php";
            ?>
            
               <!-- Section : Gestion des réservations -->
            <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
               <h2 class="text-2xl font-semibold text-gray-700 mb-6">Vos Réservations</h2>

               <?php if (mysqli_num_rows($resultReservations) > 0): ?>
                  <table class="min-w-full table-auto">
                        <thead>
                           <tr class="bg-gray-200 text-gray-700 text-left">
                              <th class="py-3 px-4">ID</th>
                              <th class="py-3 px-4">Avocat</th>
                              <th class="py-3 px-4">Date</th>
                              <th class="py-3 px-4">Statut</th>
                              <th class="py-3 px-4">Actions</th>
                           </tr>
                        </thead>
                        <tbody class="bg-white">
                           <?php while ($reservation = mysqli_fetch_assoc($resultReservations)): ?>
                              <tr class="border-b hover:bg-gray-100">
                                    <td class="py-3 px-4"><?php echo $reservation['reservation_id']; ?></td>
                                    <td class="py-3 px-4"><?php echo $reservation['avocat_name']; ?></td>
                                    <td class="py-3 px-4"><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                    <td class="py-3 px-4"><?php echo ucfirst($reservation['statut']); ?></td>
                                    <td class="py-3 px-4">
                                       <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Modifier</button>
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
                  </div>
               </div>


               <!-- profile d'utilisateur  -->

               <div id="profil" class="fixed inset-0 w-3/4 sm:scale[1/2] ml-[300px] bg-gray-900 bg-opacity-50 z-100 flex justify-center items-center">
                  <div class="p-8 bg-white shadow-lg rounded-lg">
                     <!-- Informations principales -->
                     <div class="grid grid-cols-1 md:grid-cols-3">
                           <div class="grid grid-cols-3 text-center order-last md:order-first mt-20 md:mt-0">
                              <!-- Nombre de clients -->
                              <div>
                                 <p class="font-bold text-gray-700 text-xl">
                                       <?php 
                                          $query1 = "SELECT COUNT(DISTINCT client_id) as nombre_clients FROM reservations WHERE avocat_id =".avocat['us_id'];
                                          $result = mysqli_query($conn, $query1);
                                          if ($result) {
                                             $row = mysqli_fetch_assoc($result);
                                             echo $row['nombre_clients'];
                                          } else {
                                             echo "Erreur";
                                          }
                                       ?>
                                 </p>
                                 <p class="text-gray-400">Clients</p>
                              </div>

                              <!-- Années d'expérience -->
                              <div>
                                 <p class="font-bold text-gray-700 text-xl"><?php echo $avocat['annee_experience']; ?></p>
                                 <p class="text-gray-400">Années d'expérience</p>
                              </div>

                              <!-- Nombre de commentaires -->
                              <div>
                                 <p class="font-bold text-gray-700 text-xl">10</p>
                                 <p class="text-gray-400">Commentaires</p>
                              </div>
                           </div>

                           <!-- Image de profil -->
                           <div class="relative">
                              <div class="w-48 h-48 bg-indigo-100 mx-auto rounded-full shadow-2xl absolute inset-x-0 top-0 -mt-24 flex items-center justify-center">
                                 <img src="uploads/<?php echo $avocat['picture']; ?>" alt="Profil de l'avocat" class="w-48 h-48 rounded-full object-cover">
                              </div>
                           </div>

                           <!-- Actions -->
                           <div class="space-x-8 flex justify-between mt-32 md:mt-0 md:justify-center">
                              <button class="text-white py-2 px-4 uppercase rounded bg-blue-400 hover:bg-blue-500 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                                 Connecter
                              </button>
                              <button id="closeModalBtn" class="text-white py-2 px-4 rounded bg-gray-700 hover:bg-gray-800 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                                 Fermer
                              </button>
                           </div>
                     </div>

                     <!-- Informations détaillées -->
                     <div class="mt-20 text-center border-b pb-12">
                           <h1 class="text-4xl font-medium text-gray-700">
                              <?php echo htmlspecialchars($avocat['name'] . ' ' . $avocat['first_name']); ?>
                           </h1>
                           <p class="font-light text-gray-600 mt-3"><?php echo htmlspecialchars($avocat['location']); ?></p>
                           <p class="mt-8 text-gray-500">Spécialité : <?php echo htmlspecialchars($avocat['specialite']); ?></p>
                           <p class="mt-2 text-gray-500"><?php echo htmlspecialchars($avocat['email']); ?></p>
                           <p class="mt-2 text-gray-500"><?php echo htmlspecialchars($avocat['phone_number']); ?></p>
                     </div>

                     <!-- Biographie -->
                     <div class="mt-12 flex flex-col justify-center">
                           <p class="text-gray-600 text-center font-light lg:px-16">
                              <?php echo nl2br(htmlspecialchars($avocat['biography'])); ?>
                           </p>
                           <button class="text-indigo-500 py-2 px-4 font-medium mt-4">
                              Afficher plus
                           </button>
                     </div>
                  </div>
               </div>


        </main>
    </div>
</div>

<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/client.js"></script>
<script src="../../assetsjs/data/apointment.js"></script>
    <script src="../../assets/js/data/avocatt.js"></script>
    <script src="../../assets/js/utils/date-formatter.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>

<script>
   function openProfileModal(avocatId) {
    // Sélecteurs des éléments de la modal
    const modal = document.getElementById('profileModal');
    const modalName = document.getElementById('modalName');
    const modalSpecialite = document.getElementById('modalSpecialite');
    const modalEmail = document.getElementById('modalEmail');
    const modalPhone = document.getElementById('modalPhone');
    const modalBio = document.getElementById('modalBio');
    const modalImage = document.getElementById('modalImage');

    // Effectuer une requête AJAX pour récupérer les données de l'avocat
    fetch(`get_avocat_data.php?avocat_id=${avocatId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remplir la modal avec les données reçues
                modalName.textContent = `${data.name} ${data.first_name}`;
                modalSpecialite.textContent = data.specialite;
                modalEmail.textContent = data.email;
                modalPhone.textContent = data.phone_number;
                modalBio.textContent = data.biography;
                modalImage.src = `uploads/${data.picture}`;

                // Afficher la modal
                modal.classList.remove('hidden');
            } else {
                alert("Erreur : Impossible de charger les données de l'avocat.");
            }
        })
        .catch(error => {
            console.error("Erreur lors de la récupération des données :", error);
            alert("Erreur lors du chargement des données.");
        });
}

// Fonction pour fermer la modal
function closeProfileModal() {
    const modal = document.getElementById('profileModal');
    modal.classList.add('hidden');
}

</script>
