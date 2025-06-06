@extends('layouts.app')

@section('title', 'Docent Dashboard')

@section('content')
<div class="container py-8">
    {{-- Wrap filter options, student list, modal and other elements in a new div with Alpine data --}}
    <div x-data="{ 
        selectedGroep: 'All', 
        selectedPeriode: 'Laatste 4 weken', 
        selectedFilterOp: 'Alle studenten', 
        zoekStudentTerm: '', 
        allStudenten: {{ Js::from($studenten) }}, 
        get filteredStudenten() { 
            return this.allStudenten.filter(student => {
                const matchesGroep = this.selectedGroep === 'All' || (student.groep === this.selectedGroep);
                const matchesPeriode = true; // Tijdelijk altijd true, implementatie afhankelijk van backend data
                const studentPercentage = student.percentage; // Nu direct beschikbaar van controller
                let matchesFilterOp = true;

                // Filteren op status (actief, risico, gestopt) gebaseerd op de navigatietabs
                const matchesStatusTab = () => {
                    switch (this.tab) {
                        case 'overzicht': 
                             // Op het overzicht tabblad tonen we alle studenten, filtering gebeurt via de dropdowns.
                             return student.status === 'Actief' || student.status === 'Risico';
                        case 'risico': return student.status === 'Risico';
                        case 'top': return student.status === 'Actief' && studentPercentage > 80; // Top is onderdeel van Actief
                        case 'gestopt': return student.status === 'Gestopt';
                        default: return true;
                    }
                };

                switch (this.selectedFilterOp) {
                    case '0-20%': matchesFilterOp = studentPercentage >= 0 && studentPercentage <= 20; break;
                    case '20-50%': matchesFilterOp = studentPercentage > 20 && studentPercentage <= 50; break;
                    case '50-80%': matchesFilterOp = studentPercentage > 50 && studentPercentage <= 80; break;
                    case '> 80%': matchesFilterOp = studentPercentage > 80; break;
                    case '< 50%': matchesFilterOp = studentPercentage < 50; break;
                    case 'Alle studenten': matchesFilterOp = true; break;
                    default: matchesFilterOp = true;
                }

                const zoekTerm = this.zoekStudentTerm.toLowerCase();
                const matchesZoekStudent = zoekTerm === '' || 
                    (student.naam && student.naam.toLowerCase().includes(zoekTerm)) || 
                    (student.studentnummer && student.studentnummer.toLowerCase().includes(zoekTerm));

                // Combineer alle filters en de status tab filter
                return matchesGroep && matchesPeriode && matchesFilterOp && matchesZoekStudent && matchesStatusTab();
            }); 
        },
        // Functie om een student te markeren als gestopt - NU BINNEN X-DATA
        stopStudying: async function(studentnummer) {
            if (confirm(`Weet je zeker dat je student ${studentnummer} wilt markeren als gestopt?`)) {
                try {
                    const response = await fetch(`/docent/student/${studentnummer}/stop`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert(`Student ${studentnummer} succesvol gemarkeerd als gestopt.`);
                        
                        // Update de lokale student data in Alpine.js
                        // Vind de student in de allStudenten array en update de status/rooster
                        const studentIndex = this.allStudenten.findIndex(s => s.studentnummer === studentnummer);
                        if (studentIndex !== -1) {
                            // Maak een kopie om Alpine.js te laten reageren
                            const updatedStudent = { ...this.allStudenten[studentIndex] };
                            updatedStudent.rooster = 0;
                            updatedStudent.percentage = 0; // Percentage na stoppen is 0
                            updatedStudent.status = 'Gestopt';
                            
                            // Vervang de student in de array om Alpine.js te trigegeren
                            // Gebruik splice om de array echt te muteren, wat Alpine.js detecteert
                            this.allStudenten.splice(studentIndex, 1, updatedStudent);

                             // Update filteredStudenten door een dummy mutatie of re-assign
                             // Dit zorgt ervoor dat de gefilterde lijst wordt herberekend
                            this.allStudenten = [...this.allStudenten];
                        }

                    } else {
                        alert('Er ging iets mis: ' + data.message);
                    }
                } catch (error) {
                    console.error('Fout bij markeren als gestopt:', error);
                    alert('Er trad een fout op bij het verwerken van het verzoek.');
                }
            }
        },
        // Alpine.js state voor de logboek modal
        showLogbookModal: false,
        logbookStudents: [], // Deze wordt geladen via openLogbook
        logbookLoading: false,
        logbookError: '',

        // Functie om logboek data op te halen en modal te tonen
        openLogbook: async function() {
            this.showLogbookModal = true;
            this.logbookLoading = true;
            this.logbookError = '';
            try {
                // Haal alle studenten op via de nieuwe route
                const response = await fetch('/docent/students/all');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                // Zorg ervoor dat de geladen data ook percentage en status heeft
                this.logbookStudents = data.map(student => {
                     // Ensure numeric conversion for calculations if necessary
                     const rooster = parseInt(student.rooster);
                     const aanwezigheid = parseInt(student.aanwezigheid);

                    student.percentage = rooster > 0 ? Math.round((aanwezigheid / rooster) * 100) : 0;
                    student.status = rooster === 0 ? 'Gestopt' : (student.percentage < 50 ? 'Risico' : 'Actief');
                    return student;
                });

            } catch (error) {
                console.error('Fout bij ophalen logboek data:', error);
                this.logbookError = 'Kon logboek data niet ophalen.';
            } finally {
                this.logbookLoading = false;
            }
        },
        // Functie om logboek modal te sluiten
        closeLogbook: function() {
            this.showLogbookModal = false;
            this.logbookStudents = []; // Opschonen bij sluiten
            this.logbookError = '';
        },
        // Alpine.js state voor de navigatietabs
        tab: 'overzicht', // Default tab is Overzicht

        // Initialisatie logica
        init() {
             console.log('Alpine.js initialized with', this.allStudenten.length, 'students for the main list.');
        }

    }" class="flex flex-col gap-6">
        
        {{-- BELANGRIJK: Alle inhoud die Alpine.js state gebruikt, moet hier binnen staan --}}

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Groepsoverzicht Aanwezigheid</h2>

            {{-- Groepsrapportage Download Knop --}}
            @if(isset($groepen[0]['naam']))
                <a href="{{ route('rapportage.groep.pdf', $groepen[0]['naam']) }}"
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors mb-4">
                    Groepsrapportage Downloaden
                </a>
            @endif

            {{-- Logboek Knop --}}
            <button @click="openLogbook()"
                    class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors mb-4 ml-4">
                Logboek Studenten
            </button>

            <!-- Filter/Navigation Tabs -->
            {{-- Tabs zijn nu onderdeel van de hoofd x-data scope --}}
            <div class="flex flex-wrap gap-2 mb-6">
                <button @click="tab = 'overzicht'" :class="tab === 'overzicht' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Overzicht</button>
                <button @click="tab = 'risico'" :class="tab === 'risico' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Risicostudenten</button>
                <button @click="tab = 'top'" :class="tab === 'top' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Toppresteerders</button>
                <button @click="tab = 'gestopt'" :class="tab === 'gestopt' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Gestopte studenten</button>
            </div>

            <!-- Filter Options -->
            <div class="flex flex-wrap items-center gap-4 mb-6">
                {{-- Groep Select Dropdown --}}
                <div class="min-w-[150px] flex-1 sm:flex-none">
                    <label for="groep_select" class="block text-sm font-medium text-gray-700">Selecteer Groep</label>
                    <select x-model="selectedGroep" id="groep_select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="All">Alle Groepen</option>
                        @foreach($groepen as $groep)
                            <option value="{{ $groep['naam'] }}">{{ $groep['naam'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[150px] flex-1 sm:flex-none">
                    <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                    <select x-model="selectedPeriode" id="periode" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="Laatste 1 week">Laatste 1 week</option>
                        <option value="Laatste 2 weken">Laatste 2 weken</option>
                        <option value="Laatste 3 weken">Laatste 3 weken</option>
                        <option value="Laatste 4 weken">Laatste 4 weken</option>
                        <option value="Laatste 8 weken">Laatste 8 weken</option>
                        <option value="Laatste 12 weken">Laatste 12 weken</option>
                        <option value="Dit schooljaar">Dit schooljaar</option>
                        <option value="Vorig schooljaar">Vorig schooljaar</option>
                    </select>
                </div>

                <div class="min-w-[150px] flex-1 sm:flex-none">
                    <label for="filter_op" class="block text-sm font-medium text-gray-700">Filter op %</label>
                    <select x-model="selectedFilterOp" id="filter_op" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="Alle studenten">Alle studenten</option>
                        <option value="0-20%">0-20%</option>
                        <option value="20-50%">20-50%</option>
                        <option value="50-80%">50-80%</option>
                        <option value="> 80%">> 80%</option>
                        <option value="< 50%">< 50%</option>
                    </select>
                </div>

                <div class="flex-grow min-w-[200px]">
                    <label for="zoek_student" class="block text-sm font-medium text-gray-700">Zoek student</label>
                    <input type="text" x-model="zoekStudentTerm" id="zoek_student" placeholder="Naam of studentnr..." class="mt-1 block w-full pl-3 pr-3 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-green-100 rounded-lg p-4 text-center">
                    <p class="text-4xl font-bold text-green-600">{{ $gemiddelde }}%</p>
                    <p class="text-gray-600">Groepsgemiddelde</p>
                </div>
                <div class="bg-red-100 rounded-lg p-4 text-center">
                    <p class="text-4xl font-bold text-red-600">{{ $risico }}</p>
                    <p class="text-gray-600">Risicostudenten (&lt;50%)</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <p class="text-4xl font-bold text-green-600">{{ $top }}</p>
                    <p class="text-gray-600">Goede prestaties (&gt; 80%)</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 text-center">
                    <p class="text-4xl font-bold text-gray-600">{{ $gestopt }}</p>
                    <p class="text-gray-600">Gestopte studenten</p>
                </div>
            </div>

            <!-- Student List -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aanwezigheid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laatste week</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Loop over filteredStudenten om de lijst te tonen --}}
                        <template x-for="student in filteredStudenten" :key="student.studentnummer">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="student.naam ?? student.studentnummer"></div>
                                    <div class="text-sm text-gray-500" x-text="student.studentnummer"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-green-200 text-green-800': student.percentage > 80,
                                        'bg-red-200 text-red-800': student.percentage < 50,
                                        'bg-yellow-200 text-yellow-800': student.percentage >= 50 && student.percentage <= 80
                                    }" class="px-2 py-1 rounded" x-text="student.percentage + '%'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded" x-text="student.laatste_week ?? student.percentage + '%'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-gray-100 text-gray-800': student.status === 'Gestopt',
                                        'bg-red-100 text-red-800': student.status === 'Risico',
                                        'bg-green-100 text-green-800': student.status === 'Actief'
                                    }" class="px-2 py-1 rounded" x-text="student.status"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a :href="'/docent/rapportage/student/' + student.studentnummer"
                                       class="text-blue-600 hover:text-blue-900">
                                        Download PDF
                                    </a>
                                    {{-- Nieuwe knop voor stoppen met studeren --}}
                                    {{-- Toon de knop alleen als de student niet gestopt is --}}
                                    <template x-if="student.status !== 'Gestopt'">
                                        <button @click="stopStudying(student.studentnummer)"
                                                class="ml-2 text-red-600 hover:text-red-900 focus:outline-none">
                                            Stop Studeren
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vergelijking Groepen -->
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
        </div>

        {{-- Logboek Modal - Zorg dat deze binnen de x-data div staat --}}
        <div x-cloak x-show="showLogbookModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
             @click.away="closeLogbook()" {{-- Sluit modal bij klikken buiten de modal --}}
             @keydown.escape.window="closeLogbook()" {{-- Sluit modal bij drukken op ESC --}} >

            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white"
                 @click.stop {{-- Voorkom dat klikken in de modal de modal sluit --}} >

                <!-- Modal header -->
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-2xl font-bold text-gray-900">Logboek Alle Studenten</h3>
                    <button @click="closeLogbook()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="mt-2 py-3">
                    <div x-show="logbookLoading" class="text-center text-gray-500">Laden...</div>
                    <div x-show="logbookError" class="text-center text-red-500" x-text="logbookError"></div>
                    <div x-show="!logbookLoading && !logbookError" class="overflow-x-auto" style="max-height: 60vh;">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aanwezigheid</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rooster</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Groep</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laatste week</th>
                                    {{-- Voeg hier eventueel meer kolommen toe --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Loop over logbookStudents om de volledige lijst te tonen in de modal --}}
                                <template x-for="student in logbookStudents" :key="student.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900" x-text="student.naam ?? student.studentnummer"></div>
                                            <div class="text-sm text-gray-500" x-text="student.studentnummer"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="student.aanwezigheid"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="student.rooster"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'bg-green-200 text-green-800': student.percentage > 80,
                                                'bg-red-200 text-red-800': student.percentage < 50,
                                                'bg-yellow-200 text-yellow-800': student.percentage >= 50 && student.percentage <= 80
                                            }" class="px-2 py-1 rounded" x-text="student.percentage + '%'"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'bg-gray-100 text-gray-800': student.status === 'Gestopt',
                                                'bg-red-100 text-red-800': student.status === 'Risico',
                                                'bg-green-100 text-green-800': student.status === 'Actief'
                                            }" class="px-2 py-1 rounded" x-text="student.status"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="student.groep"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="student.laatste_week ?? 'N/A'"></td>
                                        {{-- Voeg hier eventueel meer cellen toe --}}
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- Einde van de hoofd x-data div --}}
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const groepenData = @json($groepen);
        window.groepenNamen = groepenData.map(g => g.naam);

         // Hulpfunctie voor round beschikbaar maken in Alpine.js indien nodig
         // window.round = (value) => Math.round(value); // Reeds gedefinieerd in x-data
    });
</script>
@endpush
@endsection