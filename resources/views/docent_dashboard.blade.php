@extends('layouts.app')

@section('title', 'Docent Dashboard')

@section('content')
<div class="container py-8">
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Groepsoverzicht Aanwezigheid</h2>
        <p class="text-gray-600 mb-4">Groep: ICT-FLEX | Actieve studenten: 24</p>

        <!-- Filter/Navigation Tabs -->
        <div class="flex flex-wrap gap-2 mb-6">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Overzicht</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Risicostudenten</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Toppresteerders</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Gestopte studenten</button>
        </div>

        <!-- Filter Options -->
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <div>
                <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                <select id="periode" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>Laatste 4 weken</option>
                </select>
            </div>
            <div>
                <label for="filter_op" class="block text-sm font-medium text-gray-700">Filter op %</label>
                <select id="filter_op" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>Alle studenten</option>
                </select>
            </div>
            <div class="flex-grow">
                <label for="zoek_student" class="block text-sm font-medium text-gray-700">Zoek student</label>
                <input type="text" id="zoek_student" placeholder="Naam of studentnr..." class="mt-1 block w-full pl-3 pr-3 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            </div>
            <button class="mt-5 bg-blue-600 text-white px-4 py-2 rounded-md">Filter toepassen</button>
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
</div>
@endsection