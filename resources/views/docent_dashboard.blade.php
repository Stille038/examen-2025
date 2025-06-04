@extends('layouts.app')

@section('title', 'Docent Dashboard')

@section('content')
<div class="container py-8">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Groepsoverzicht Aanwezigheid</h2>
                    <p class="text-gray-600 mb-4">
                        Groep: {{ $groepen[0]['naam'] ?? '-' }} | Actieve studenten: {{ $groepen[0]['aantal'] ?? '-' }}
                    </p>

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
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($studenten as $student)
                                    @php
                                        $gem = $student->rooster ? round(($student->aanwezigheid / $student->rooster) * 100) : 0;
                                        $laatsteWeek = $student->laatste_week ?? $gem; // pas aan als je weekdata hebt
                                        $status = $student->rooster == 0 ? 'Gestopt' : ($gem < 50 ? 'Risico' : 'Actief');
                                    @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->naam ?? $student->studentnummer }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->studentnummer }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="{{ $gem > 80 ? 'bg-green-200 text-green-800' : ($gem < 50 ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }} px-2 py-1 rounded">{{ $gem }}%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded">{{ $laatsteWeek }}%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->rooster == 0)
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">• Gestopt</span>
                                    @elseif($gem < 50)
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">▲ Risico</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">✓ Actief</span>
                                    @endif
                                    </td>
                                </tr>
                                @endforeach
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
    </script>
</div>
@endsection