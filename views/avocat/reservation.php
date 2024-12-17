<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-semibold text-gray-900">Réserver une Consultation</h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form class="space-y-8 divide-y divide-gray-200 p-6">
                    <div class="space-y-6">
                        <div>
                            <label for="avocat" class="block text-sm font-medium text-gray-700">Choisir un avocat</label>
                            <select id="avocat" name="avocat" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option>Me. Jean Dupont - Droit des affaires</option>
                                <option>Me. Marie Martin - Droit de la famille</option>
                                <option>Me. Pierre Durant - Droit pénal</option>
                            </select>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date souhaitée</label>
                            <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700">Horaire</label>
                            <select id="time" name="time" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option>09:00</option>
                                <option>10:00</option>
                                <option>11:00</option>
                                <option>14:00</option>
                                <option>15:00</option>
                                <option>16:00</option>
                            </select>
                        </div>

                        <div>
                            <label for="motif" class="block text-sm font-medium text-gray-700">Motif de la consultation</label>
                            <textarea id="motif" name="motif" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Annuler</button>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Confirmer la réservation</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>