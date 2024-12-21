<!-- Section : Consultation des profils des avocats -->
            <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
                  <h2 class="text-2xl font-semibold text-gray-700 mb-6">Profils des Avocats</h2>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                     <?php while ($avocat = mysqli_fetch_assoc($resultAvocats)): ?>
                           <div class="bg-white p-6 rounded-lg shadow-lg">
                              <img src="C:/Users/safiy/OneDrive/Images/<?php echo "$avocat['picture']"; ?>" alt="Avatar de l'avocat" class="w-24 h-24 rounded-full mx-auto">
                              <h3 class="text-xl text-center mt-4"><?php echo $avocat['name'] . ' ' . $avocat['first_name']; ?></h3>
                              <p class="text-gray-600 text-center mt-2"><?php echo $avocat['email']; ?></p>
                              <p class="text-center mt-4">
                                 <button onclick="openProfileModal(<?php echo $avocat['us_id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                       voir profil ..
                                 </button>
                              </p>
                           </div>
                     <?php endwhile; ?>
                  </div>
            </div>

            