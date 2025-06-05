@extends('layouts.app')

@section('title', 'Docent Dashboard')

@section('content')
<div class="container py-8">
    {{-- Wrap filter options and student list in a new div with Alpine data --}}
    <div x-data="{ selectedGroep: 'All', selectedPeriode: 'Laatste 4 weken', selectedFilterOp: 'Alle studenten', zoekStudentTerm: '', allStudenten: {{ Js::from($studenten) }}, get filteredStudenten() { return this.allStudenten.filter(student => {
        const matchesGroep = this.selectedGroep === 'All' || (student.groep === this.selectedGroep);
        
        // Implementatie voor Periode filter zou hier komen. Dit vereist dat de student data vanuit de backend
        // informatie bevat over aanwezigheid per periode (bv. per week of per schooljaar).
        // De huidige student data bevat alleen totale aanwezigheid en rooster. Extra data is nodig voor deze filter.
        const matchesPeriode = true; // Tijdelijk altijd true, implementatie afhankelijk van backend data.

        // Implementatie voor Filter op %
        const studentPercentage = student.rooster > 0 ? (student.aanwezigheid / student.rooster) * 100 : 0;
        let matchesFilterOp = true;

        switch (this.selectedFilterOp) {
            case '0-20%':
                matchesFilterOp = studentPercentage >= 0 && studentPercentage <= 20;
                break;
            case '20-50%':
                matchesFilterOp = studentPercentage > 20 && studentPercentage <= 50;
                break;
            case '50-80%':
                matchesFilterOp = studentPercentage > 50 && studentPercentage <= 80;
                break;
            case '> 80%':
                matchesFilterOp = studentPercentage > 80;
                break;
            case '< 50%':
                matchesFilterOp = studentPercentage < 50;
                break;
            case 'Alle studenten':
            default:
                matchesFilterOp = true;
        }

        // Implementatie voor Zoek student filter
        const zoekTerm = this.zoekStudentTerm.toLowerCase();
        const matchesZoekStudent = zoekTerm === '' || 
                                 (student.naam && student.naam.toLowerCase().includes(zoekTerm)) || 
                                 (student.studentnummer && student.studentnummer.toLowerCase().includes(zoekTerm));

        return matchesGroep && matchesPeriode && matchesFilterOp && matchesZoekStudent;
    }); } }" class="flex flex-col gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Groepsoverzicht Aanwezigheid</h2>

            {{-- Groepsrapportage Download Knop --}}
            @if(isset($groepen[0]['naam']))
                <a href="{{ route('rapportage.groep.pdf', $groepen[0]['naam']) }}"
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors mb-4">
                    Groepsrapportage Downloaden
                </a>
            @endif

            <!-- Filter/Navigation Tabs -->
            <div x-data="{ tab: 'overzicht' }" class="flex flex-wrap gap-2 mb-6">
                <button @click="tab = 'overzicht'" :class="tab === 'overzicht' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Overzicht</button>
                <button @click="tab = 'risico'" :class="tab === 'risico' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Risicostudenten</button>
                <button @click="tab = 'top'" :class="tab === 'top' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Toppresteerders</button>
                <button @click="tab = 'gestopt'" :class="tab === 'gestopt' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-md transition-colors">Gestopte studenten</button>
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
                        {{-- Voeg hier meer periode opties toe --}}
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
                        {{-- Voeg hier meer filter opties toe --}}
                    </select>
                </div>
                <div class="flex-grow min-w-[200px]">
                    <label for="zoek_student" class="block text-sm font-medium text-gray-700">Zoek student</label>
                    <input type="text" x-model="zoekStudentTerm" id="zoek_student" placeholder="Naam of studentnr..." class="mt-1 block w-full pl-3 pr-3 py-2 text-base border border-primary focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                </div>
            </div>

            <!-- Summary Cards -->
            @php
                $aantalStudenten = $studenten->count();
                $gemiddelde = $aantalStudenten > 0 ? round($studenten->avg(function($s) { return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0; }), 0) : 0;
                $risico = $studenten->filter(function($s) { return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 < 50; })->count();
                $top = $studenten->filter(function($s) { return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 > 80; })->count();
                $gestopt = $studenten->filter(function($s) { return $s->rooster == 0; })->count();
            @endphp
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
                                {{-- PDF Download Header --}}
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rapportage</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="student in filteredStudenten" :key="student.studentnummer">
                                @php
                                    // These Blade variables are not available inside x-for
                                    // We'll calculate status/gem using Alpine.js if needed for display
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" x-text="student.naam ?? student.studentnummer"></div>
                                        <div class="text-sm text-gray-500" x-text="student.studentnummer"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{'bg-green-200 text-green-800': (student.rooster && (student.aanwezigheid / student.rooster) * 100 > 80), 'bg-red-200 text-red-800': (student.rooster && (student.aanwezigheid / student.rooster) * 100 < 50), 'bg-yellow-200 text-yellow-800': (student.rooster && (student.aanwezigheid / student.rooster) * 100 >= 50 && (student.aanwezigheid / student.rooster) * 100 <= 80) }" class="px-2 py-1 rounded" x-text="(student.rooster ? Math.round((student.aanwezigheid / student.rooster) * 100) : 0) + '%'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded" x-text="student.laatste_week ?? (student.rooster ? Math.round((student.aanwezigheid / student.rooster) * 100) : 0) + '%'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{'bg-gray-100 text-gray-800': student.rooster == 0, 'bg-red-100 text-red-800': (student.rooster > 0 && (student.aanwezigheid / student.rooster) * 100 < 50), 'bg-green-100 text-green-800': (student.rooster > 0 && (student.aanwezigheid / student.rooster) * 100 >= 50) }" class="px-2 py-1 rounded" x-text="student.rooster == 0 ? '• Gestopt' : ((student.rooster > 0 && (student.aanwezigheid / student.rooster) * 100 < 50) ? '▲ Risico' : '✓ Actief')"></span>
                                    </td>
                                    {{-- PDF Download Knop --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a :href="'/docent/rapportage/student/' + student.studentnummer"
                                           class="text-blue-600 hover:text-blue-900">
                                            Download PDF
                                        </a>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
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
            <div x-data="{
                    vergelijk: false,
                    chartType: 'gemiddelde',
                    chartStyle: 'bar',
                    selectedGroups: [],
                    init() {
                        this.selectedGroups = window.groepenNamen;
                        this.$watch('chartType', () => this.updateChart());
                        this.$watch('chartStyle', () => this.updateChart());
                        this.$watch('selectedGroups', () => this.updateChart());
                        this.$watch('vergelijk', () => this.updateChart());
                    },
                    updateChart() {
                        renderChart(this.chartType, this.chartStyle, this.selectedGroups, this.vergelijk);
                    }
                }"
                x-init="renderChart(chartType, chartStyle, selectedGroups, vergelijk)"
                class="flex flex-col items-center justify-center bg-gray-50 rounded-lg shadow-inner p-6 w-full md:w-2/3 mx-auto">
                <div class="w-full flex flex-col sm:flex-row items-center justify-between mb-4 gap-2">
                    <h3 class="text-lg font-semibold text-gray-700">Kies een grafiek en type</h3>
                    <div class="flex items-center gap-2">
                        <label class="font-medium text-gray-700 flex items-center gap-1">
                            <input type="checkbox" x-model="vergelijk" class="form-checkbox h-4 w-4 text-blue-600">
                            Vergelijkingsmodus
                        </label>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto mb-4">
                    <select x-model="chartType" class="border border-primary rounded px-3 py-2 focus:outline-none focus:ring-blue-500">
                        <option value="gemiddelde">Groepsgemiddelden</option>
                        <option value="aantal">Aantal studenten per groep</option>
                        <option value="trend">Trend (dummy)</option>
                    </select>
                    <select x-model="chartStyle" class="border border-primary rounded px-3 py-2 focus:outline-none focus:ring-blue-500">
                        <option value="bar">Staafdiagram</option>
                        <option value="line">Lijngrafiek</option>
                        <option value="pie">Cirkeldiagram</option>
                        <option value="doughnut">Donutdiagram</option>
                    </select>
                </div>
                <template x-if="vergelijk">
                    <div class="w-full flex flex-col sm:flex-row items-center gap-2 mb-4">
                        <label class="font-medium text-gray-700">Selecteer 2+ groepen:</label>
                        <select x-model="selectedGroups" multiple class="border border-primary rounded px-3 py-2 focus:outline-none focus:ring-blue-500 w-full sm:w-auto min-w-[180px]" size="3">
                            @foreach($groepen as $groep)
                                <option value="{{ $groep['naam'] }}">{{ $groep['naam'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>
                <div class="w-full h-96 flex items-center justify-center border border-gray-200 rounded-lg bg-white p-4">
                    <canvas id="groepenChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </div> {{-- End of new wrapper div --}}

    <!-- Groepsgemiddelden grafiek -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Haal de groepsdata nu uit de database
            const groepenData = @json($groepen);
            const groepenLabelsAll = groepenData.map(g => g.naam);
            const groepenGemiddeldenAll = groepenData.map(g => g.gemiddelde);
            const groepenAantalAll = groepenData.map(g => g.aantal);
            const trendData = [70, 75, 80, 85, 90, 88, 82];
            const trendLabels = ['Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul'];

            let groepenChartInstance = null;

            function renderChart(type, style, selectedGroups, vergelijk) {
                const ctx = document.getElementById('groepenChart');
                if (!ctx) {
                    console.error('Canvas element not found');
                    return;
                }

                if (groepenChartInstance) {
                    groepenChartInstance.destroy();
                }

                let groepenLabels, groepenGemiddelden, groepenAantal;
                if (vergelijk && selectedGroups.length > 0) {
                    const indices = groepenLabelsAll.map((naam, i) => selectedGroups.includes(naam) ? i : -1).filter(i => i !== -1);
                    groepenLabels = indices.map(i => groepenLabelsAll[i]);
                    groepenGemiddelden = indices.map(i => groepenGemiddeldenAll[i]);
                    groepenAantal = indices.map(i => groepenAantalAll[i]);
                } else {
                    groepenLabels = groepenLabelsAll;
                    groepenGemiddelden = groepenGemiddeldenAll;
                    groepenAantal = groepenAantalAll;
                }

                if (groepenLabels.length === 0) {
                    console.error('No data available for chart');
                    return;
                }

                // Kleurenschema's per type/stijl
                let backgroundColor, borderColor;
                if (style === 'bar') {
                    backgroundColor = 'rgba(59, 130, 246, 0.7)'; // blauw
                    borderColor = 'rgba(59, 130, 246, 1)';
                } else if (style === 'line') {
                    backgroundColor = 'rgba(251, 146, 60, 0.3)'; // oranje
                    borderColor = 'rgba(251, 146, 60, 1)';
                } else if (style === 'pie') {
                    backgroundColor = [
                        'rgba(34,197,94,0.7)', // groen
                        'rgba(132,204,22,0.7)',
                        'rgba(16,185,129,0.7)',
                        'rgba(59,130,246,0.7)'
                    ];
                    borderColor = [
                        'rgba(34,197,94,1)',
                        'rgba(132,204,22,1)',
                        'rgba(16,185,129,1)',
                        'rgba(59,130,246,1)'
                    ];
                } else if (style === 'doughnut') {
                    backgroundColor = [
                        'rgba(168,85,247,0.7)', // paars
                        'rgba(139,92,246,0.7)',
                        'rgba(192,38,211,0.7)',
                        'rgba(236,72,153,0.7)'
                    ];
                    borderColor = [
                        'rgba(168,85,247,1)',
                        'rgba(139,92,246,1)',
                        'rgba(192,38,211,1)',
                        'rgba(236,72,153,1)'
                    ];
                } else {
                    backgroundColor = 'rgba(59, 130, 246, 0.7)';
                    borderColor = 'rgba(59, 130, 246, 1)';
                }

                let data, options, chartType;
                if (type === 'gemiddelde') {
                    data = {
                        labels: groepenLabels,
                        datasets: [{
                            label: 'Groepsgemiddelde (%)',
                            data: groepenGemiddelden,
                            backgroundColor: backgroundColor,
                            borderColor: borderColor,
                            borderWidth: 2,
                            borderRadius: style === 'bar' ? 8 : 0,
                            fill: style === 'line' ? false : true,
                            tension: style === 'line' ? 0.4 : 0
                        }]
                    };
                    chartType = style;
                } else if (type === 'aantal') {
                    data = {
                        labels: groepenLabels,
                        datasets: [{
                            label: 'Aantal studenten',
                            data: groepenAantal,
                            backgroundColor: backgroundColor,
                            borderColor: borderColor,
                            borderWidth: 2,
                            borderRadius: style === 'bar' ? 8 : 0,
                            fill: style === 'line' ? false : true,
                            tension: style === 'line' ? 0.4 : 0
                        }]
                    };
                    chartType = style;
                } else if (type === 'trend') {
                    data = {
                        labels: trendLabels,
                        datasets: [{
                            label: 'Groepsgemiddelde trend',
                            data: trendData,
                            backgroundColor: style === 'line' ? 'rgba(251, 146, 60, 0.3)' : backgroundColor,
                            borderColor: style === 'line' ? 'rgba(251, 146, 60, 1)' : borderColor,
                            borderWidth: 2,
                            fill: style === 'line' ? false : true,
                            tension: style === 'line' ? 0.4 : 0
                        }]
                    };
                    chartType = style === 'bar' || style === 'line' ? style : 'line';
                }

                if ((type === 'gemiddelde' || type === 'aantal') && (style === 'pie' || style === 'doughnut')) {
                    chartType = style;
                }

                options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    scales: (chartType === 'bar' || chartType === 'line') ? {
                        y: {
                            beginAtZero: true,
                            max: type === 'gemiddelde' || type === 'trend' ? 100 : undefined,
                            ticks: { stepSize: 10 }
                        }
                    } : {}
                };

                try {
                    groepenChartInstance = new Chart(ctx, {
                        type: chartType,
                        data: data,
                        options: options
                    });
                } catch (error) {
                    console.error('Error creating chart:', error);
                }
            }

            // Initial render
            const initialType = 'gemiddelde';
            const initialStyle = 'bar';
            const initialGroups = @json(collect($groepen)->pluck('naam'));
            renderChart(initialType, initialStyle, initialGroups, false);

            // Make renderChart available globally for Alpine.js
            window.renderChart = renderChart;
        });
    </script>
    <script>
        window.groepenNamen = @json(collect($groepen)->pluck('naam'));
        // Also make studentenData globally available or pass to the correct Alpine scope
        window.studentenData = @json($studenten);
    </script>
</div>
@endsection