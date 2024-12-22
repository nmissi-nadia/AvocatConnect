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


// Si une requête POST est reçue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $type = $_POST['type'] ?? ''; // Récupérer le type de jugement envoyé via POST

   // Construire la requête SQL
   $query24 = $type 
       ? "SELECT u.us_id AS id, u.name, u.first_name FROM utilisateur u JOIN infos i ON u.us_id = i.avocat_id WHERE i.specialite = '$type'"
       : "SELECT u.us_id AS id, u.name, u.first_name FROM utilisateur u WHERE u.role = 'Avocat'";

   $result12 = mysqli_query($conn, $query24);
   $lawyers = [];
   while ($row = mysqli_fetch_assoc($result12)) {
       $lawyers[] = $row;
   }

   // Générer le tableau JSON en tant que tableau JavaScript
   echo "<script>";
   echo "const lawyers = " . json_encode($lawyers) . ";";
   echo "console.log(lawyers);"; // Affichez les avocats dans la console JS
   echo "</script>";
}

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



<nav class="fixed top-0 w-full h-[70px] flex items-center justify-between bg-white shadow-md z-[100]">
      <div class="flex items-center w-[150px] justify-between ml-[20px] md:ml-[40px]">
        <a href="#" class="scale-[3] md:scale-[4]">
          <img class="w-10 ml-10" src="../../assets/images/logo.png" alt="Logo du site" />
        </a>
      </div>
      <div class="flex items-center">
        <button class="block md:hidden text-2xl text-gray-700 mr-4" id="menu-toggle">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
      <div id="navbar-links" class="hidden md:flex justify-between items-center gap-6">
        <a href="#avocat" class="text-[15px] text-gray-600 hover:text-pink-300 transition-all duration-300 ease-in-out">
          Les avocats
        </a>
        <a href="#rdv" class="text-[15px] text-gray-600 hover:text-pink-300 transition-all duration-300 ease-in-out">
          Rendez vous
        </a>
        <a href="#reserv" class="text-[15px] text-gray-600 hover:text-pink-300 transition-all duration-300 ease-in-out">
          Mes réservations 
        </a>
        <p class="flex items-center text-[15px] text-gray-600 hover:text-pink-300 transition-all duration-300 ease-in-out">
          <i class="fa-regular fa-user text-pink-500"></i>&nbsp;&nbsp;<span id="profile">Profile</span>
        </p>
      </div>
    
      <!-- Espace et bouton de déconnexion -->
      <div class="hidden md:flex items-center">
        <a href="../logout.php" class="text-[15px] font-medium text-white leading-[26px] py-[9px] px-[25px] bg-gradient-to-r from-[#EC008C] via-[#fc6767] to-[#EC008C] rounded-full shadow-md transition-all duration-200 ease-in-out hover:bg-gradient-to-r hover:from-[#EC008C] hover:to-[#fc6767]">
          Déconnexion
        </a>
        <div class="w-[50px]"></div>
      </div>
    </nav>
    
    
    <div id="mobile-menu" class="hidden flex-col bg-white shadow-md absolute top-[70px] w-full p-4 md:hidden">
      <a href="#avocat" class="py-2 text-gray-600 hover:text-pink-300">Les avocats</a>
      <a href="#rdv" class="py-2 text-gray-600 hover:text-pink-300">Rendez vous</a>
      <a href="#reserv" class="py-2 text-gray-600 hover:text-pink-300">Mes réservations</a>
      <a href="userlogin.php" class="py-2 text-white bg-gradient-to-r from-[#EC008C] via-[#fc6767] to-[#EC008C] rounded-full text-center">Connexion</a>
    </div>


   <div class="min-h-screen mt-14">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-semibold text-gray-900">Mon Espace Client :<?php echo "$name "; ?></h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Quick Actions -->
            <div id="rdv" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <button onclick="" class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Prendre RDV</h3>
                    <p class="mt-2 text-sm text-gray-500">Réserver une consultation</p>
                </button>

               <div  class="max-w-4xl mx-auto p-6">
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

                              <form action="rdv.php" id="appointmentForm">
                                 <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                          Type de jugement
                                    </label>
                                    <select id="judgmentType" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                          <option value="">Sélectionnez un type</option>
                                          <option value="droit_numérique">Civil</option>
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
            <script>
               // Gestion du calendrier
                  document.getElementById("calendarDays").addEventListener("click", (event) => {
                     if (event.target.classList.contains("day")) {
                        const selectedDate = event.target.dataset.date;
                        document.getElementById("modalDate").innerText = `Rendez-vous pour le ${selectedDate}`;
                        document.getElementById("appointmentForm").date.value = selectedDate; // Stocker la date
                        document.getElementById("appointmentModal").classList.remove("hidden");
                     }
                  });

                  // Gestion du bouton pour ouvrir le calendrier
                  document.querySelector("#rdv button").addEventListener("click", () => {
                     document.getElementById("calendar").classList.toggle("hidden");
                  });

                  // Gestion du type de jugement -> Charge les avocats associés
                  document.getElementById("judgmentType").addEventListener("change", (event) => {
                     const judgmentType = event.target.value;
                     const lawyerSelect = document.getElementById("lawyer");
                     lawyerSelect.innerHTML = '<option value="">Chargement...</option>';
                     lawyerSelect.disabled = true;
                     console.log(judgmentType);
                     console.log(`Requête envoyée à : avocates.php?type=${judgmentType}`);

                     fetch(`avocates.php?type=${judgmentType}`)
                        .then(response => response.json())
                        .then(data => {
                              lawyerSelect.innerHTML = '<option value="">Sélectionnez un avocat</option>';
                              console.log(data);
                              data.forEach(lawyer => {
                                 lawyerSelect.innerHTML += `<option value="${lawyer.id}">${lawyer.name} ${lawyer.first_name}</option>`;
                              });
                              lawyerSelect.disabled = false;
                        })
                        .catch(error => {
                              console.error("Erreur :", error);
                              lawyerSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                        });
                  });

                  // Fermeture de la modal
                  document.getElementById("closeModal").addEventListener("click", () => {
                     document.getElementById("appointmentModal").classList.add("hidden");
                  });

            </script>
              
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

            


        </main>

</div>

<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/client.js"></script>
<script src="../../assets/js/avocatt.js"></script>
<script src="../../assets/js/date-formatter.js"></script>
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
