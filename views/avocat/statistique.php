<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Avocat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-semibold text-gray-900">Statistiques</h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Performance Overview -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Consultations ce mois</h3>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-semibold text-gray-900">45</p>
                            <p class="ml-2 text-sm font-medium text-green-600">+12.5%</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Voir détails</a>
                        </div>
                    </div>
                </div>

                <!-- More stat cards... -->
            </div>

            <!-- Charts Section -->
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition des Consultations</h3>
                    <div class="h-64 bg-gray-100 rounded-lg"></div>
                </div>
                
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution des Revenus</h3>
                    <div class="h-64 bg-gray-100 rounded-lg"></div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>