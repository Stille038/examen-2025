<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docent Dashboard - Groepsoverzicht</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-gradient-to-r {
            background-image: linear-gradient(to right, #667eea, #764ba2);
        }
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 1px solid #e0e0e0;
        }
        .sidebar a {
            display: block;
            padding: 10px 0;
            color: #4a5568;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #2b6cb0;
        }
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            text-align: center;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-risk {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-stopped {
            background-color: #e2e3e5;
            color: #383d41;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .percentage-green {
            background-color: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
        }
        .percentage-red {
            background-color: #dc3545;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
        }
        .percentage-orange {
            background-color: #ffc107;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
        }
    </style>
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex h-screen">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 bg-gradient-to-r from-purple-600 to-indigo-800 text-white p-4 flex justify-between items-center shadow-md z-10" x-data="{ navOpen: false }">
            <div class="flex items-center">
                <img src="{{ asset('Images/placeholder.png') }}" alt="Logo" class="h-8 w-8 mr-2">
                <h1 class="text-xl font-bold">Docent Dashboard - Groepsoverzicht</h1>
                <span class="ml-4 text-sm">URL:/docent/groep/{groepnaam}</span>
            </div>
            <div class="relative">
                <!-- Toggle-knop (hamburger) voor de navigatie -->
                <button @click="navOpen = ! navOpen" class="p-2 rounded hover:bg-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
                <!-- Navigatie (wordt getoond/verborgen op basis van navOpen) -->
                <div x-show="navOpen" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded shadow-lg z-20" @click.outside="navOpen = false">
                    <a href="#" class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Link 1</a>
                    <a href="#" class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Link 2</a>
                    <a href="#" class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Link 3</a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex flex-1 pt-16">
            <main class="flex-1 p-6">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Groepsoverzicht Aanwezigheid</h2>
                    <p class="text-gray-600 mb-4">Groep: ICT-FLEX | Actieve studenten: 24</p>

                    <!-- Filter/Navigation Tabs -->
                    <div class="flex space-x-2 mb-6">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Overzicht</button>
                        <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Risicostudenten</button>
                        <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Toppresteerders</button>
                        <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Gestopte studenten</button>
                    </div>

                    <!-- Filter Options -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div>
                            <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                            <select id="periode" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option>Laatste 4 weken</option>
                            </select>
                        </div>
                        <div>
                            <label for="filter_op" class="block text-sm font-medium text-gray-700">Filter op %</label>
                            <select id="filter_op" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option>Alle studenten</option>
                            </select>
                        </div>
                        <div class="flex-grow">
                            <label for="zoek_student" class="block text-sm font-medium text-gray-700">Zoek student</label>
                            <input type="text" id="zoek_student" placeholder="Naam of studentnr..." class="mt-1 block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        </div>
                        <button class="mt-5 bg-blue-600 text-white px-4 py-2 rounded-md">Filter toepassen</button>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="card">
                            <p class="text-4xl font-bold text-green-600">78%</p>
                            <p class="text-gray-600">Groepsgemiddelde</p>
                        </div>
                        <div class="card">
                            <p class="text-4xl font-bold text-red-600">6</p>
                            <p class="text-gray-600">Risicostudenten (<50%)</p>
                        </div>
                        <div class="card">
                            <p class="text-4xl font-bold text-green-600">8</p>
                            <p class="text-gray-600">Goede prestaties (> 80%)</p>
                        </div>
                        <div class="card">
                            <p class="text-4xl font-bold text-gray-600">2</p>
                            <p class="text-gray-600">Gestopte studenten</p>
                        </div>
                    </div>

                    <!-- Student List -->
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Student</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gemiddelde</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laatste week</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Student Row 1 -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">Jan de Vries</div>
                                        <div class="text-sm text-gray-500">12345678</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="percentage-green">95%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="percentage-green">100%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-active">✓ Actief</span>
                                    </td>
                                </tr>
                                <!-- Student Row 2 -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">Maria Jansen</div>
                                        <div class="text-sm text-gray-500">87654321</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="percentage-red">35%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="percentage-red">20%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-risk">▲ Risico</span>
                                    </td>
                                </tr>
                                <!-- Student Row 3 -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">Piet Smit</div>
                                        <div class="text-sm text-gray-500">11223344</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="percentage-orange">87%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="percentage-orange">75%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-active">✓ Actief</span>
                                    </td>
                                </tr>
                                <!-- Student Row 4 -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">Lisa van Dam</div>
                                        <div class="text-sm text-gray-500">99887766</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-gray-300 text-gray-700 px-2 py-1 rounded">N/A</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-gray-300 text-gray-700 px-2 py-1 rounded">N/A</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-stopped">• Gestopt (Week 5)</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>