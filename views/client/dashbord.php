<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-semibold text-gray-900">Mon Espace Client</h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <button class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Prendre RDV</h3>
                    <p class="mt-2 text-sm text-gray-500">Réserver une consultation</p>
                </button>

                <!-- More quick actions... -->
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
        </main>
    </div>
</body>
</html>