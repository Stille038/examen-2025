@extends('layouts.app')

@section('title', 'Docent Dashboard')

@section('content')
<div class="container py-8">
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Groepsoverzicht Aanwezigheid</h2>
        <p class="text-gray-600 mb-4">Groep: ICT-FLEX | Actieve studenten: 24</p>

        <!-- Filter/Navigation Tabs -->
        <div x-data="{ tab: 'overzicht' }" class="flex flex-wrap gap-2 mb-6">
            <button @click="tab = 'overzicht'" :class="tab === 'overzicht' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Overzicht</button>
            <button @click="tab = 'risico'" :class="tab === 'risico' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Risicostudenten</button>
            <button @click="tab = 'top'" :class="tab === 'top' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Toppresteerders</button>
            <button @click="tab = 'gestopt'" :class="tab === 'gestopt' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Gestopte studenten</button>
        </div>

        <!-- Filter Options -->
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <div class="min-w-[150px] flex-1 sm:flex-none">
                <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                <select id="periode" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>Laatste 4 weken</option>
                </select>
            </div>
            <div class="min-w-[150px] flex-1 sm:flex-none">
                <label for="filter_op" class="block text-sm font-medium text-gray-700">Filter op %</label>
                <select id="filter_op" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>Alle studenten</option>
                </select>
            </div>
            <div class="flex-grow min-w-[200px]">
                <label for="zoek_student" class="block text-sm font-medium text-gray-700">Zoek student</label>
                <input type="text" id="zoek_student" placeholder="Naam of studentnr..." class="mt-1 block w-full pl-3 pr-3 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            </div>
            <button class="mt-5 sm:mt-0 bg-blue-600 text-white px-4 py-2 rounded-md">Filter toepassen</button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-green-100 rounded-lg p-4 text-center">
                <p class="text-4xl font-bold text-green-600">78%</p>
                <p class="text-gray-600">Groepsgemiddelde</p>
            </div>
            <div class="bg-red-100 rounded-lg p-4 text-center">
                <p class="text-4xl font-bold text-red-600">6</p>
                <p class="text-gray-600">Risicostudenten (&lt;50%)</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-4xl font-bold text-green-600">8</p>
                <p class="text-gray-600">Goede prestaties (&gt; 80%)</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 text-center">
                <p class="text-4xl font-bold text-gray-600">2</p>
                <p class="text-gray-600">Gestopte studenten</p>
            </div>
        </div>

        <!-- Student List -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Studenten</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gemiddelde</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laatste week</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Jan de Vries</div>
                                <div class="text-sm text-gray-500">12345678</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-200 text-green-800 px-2 py-1 rounded">95%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-200 text-green-800 px-2 py-1 rounded">100%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">✓ Actief</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Maria Jansen</div>
                                <div class="text-sm text-gray-500">87654321</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-red-200 text-red-800 px-2 py-1 rounded">35%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-red-200 text-red-800 px-2 py-1 rounded">20%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded">▲ Risico</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Piet Smit</div>
                                <div class="text-sm text-gray-500">11223344</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded">87%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded">75%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">✓ Actief</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Lisa van Dam</div>
                                <div class="text-sm text-gray-500">99887766</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded">N/A</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded">N/A</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">• Gestopt (Week 5)</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Vergelijking Groepen -->
    @php
        // Hier kun je later dynamisch data uit de database halen
        $groepen = [
            [ 'naam' => 'ICT-FLEX', 'gemiddelde' => 78, 'aantal' => 24 ],
            [ 'naam' => 'ICT-REGULIER', 'gemiddelde' => 82, 'aantal' => 30 ],
            [ 'naam' => 'ICT-FASTTRACK', 'gemiddelde' => 88, 'aantal' => 12 ],
        ];
    @endphp
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Vergelijk groepsgemiddelden</h2>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Groep</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gemiddelde</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aantal studenten</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($groepen as $groep)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $groep['naam'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $groep['gemiddelde'] }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $groep['aantal'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg shadow-inner p-6 w-full md:w-2/3 mx-auto">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Groepsgemiddelden grafiek</h3>
            <div class="w-full h-64 flex items-center justify-center">
                <canvas id="groepenChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Groepsgemiddelden grafiek -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Hier kun je later dynamisch data uit Laravel/PHP injecteren
        const groepenLabels = @json(collect($groepen)->pluck('naam'));
        const groepenGemiddelden = @json(collect($groepen)->pluck('gemiddelde'));
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('groepenChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: groepenLabels,
                    datasets: [{
                        label: 'Groepsgemiddelde (%)',
                        data: groepenGemiddelden,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(234, 179, 8, 0.7)'
                        ],
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { stepSize: 10 }
                        }
                    }
                }
            });
        });
    </script>
</div>
@endsection