@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* fade-in animatie */
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="antialiased min-h-screen bg-cover bg-center" style="font-family: 'Cormorant Garamond', serif;">
    <div class="h-full min-h-screen flex flex-wrap lg:flex-nowrap px-6 py-10">
        <div class="w-full border border-gray-300 shadow-xl flex flex-col bg-gray-100 rounded-2xl p-6">

            <!-- Terug knop -->
            <div class="mt-4">
                <a class="text-lg text-gray-600 hover:underline" href="{{ route('docent.dashboard') }}">&larr; Terug naar Groep overzicht</a>
            </div>

            <!-- Student Info -->
            <div class="bg-white rounded-xl shadow mt-4 p-6">
                <h2 class="text-xl font-semibold mb-1">Individueel Student Overzicht</h2>
                <p class="text-gray-600 mb-4">Student: {{ $studentnummer }} | Groep: {{ $filters['jaar'] ?? '...' }}</p>

                <!-- Statistieken -->
                <div class="grid grid-cols md:grid-cols-6 gap-4 text-center mb-6">
                    @php
                    $borderColor = match (true) {
                    $gemiddelde == 100 => 'border-purple-600 text-purple-600',
                    $gemiddelde >= 95 => 'border-blue-500 text-blue-500',
                    $gemiddelde >= 80 => 'border-green-500 text-green-500',
                    $gemiddelde >= 65 => 'border-yellow-400 text-yellow-400',
                    $gemiddelde >= 50 => 'border-orange-400 text-orange-400',
                    $gemiddelde > 0 => 'border-red-500 text-red-500',
                    default => 'border-red-800 text-red-800',
                    };
                    @endphp
                    <div class="bg-white rounded-lg shadow p-4 border {{ $borderColor }} ">
                        <p class="text-3xl font-bold {{ $borderColor }}">{{ $gemiddelde ?? '-' }}%</p>
                        <p class="text-sm text-gray-600">Gemiddelde aanwezigheid</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border border-red-200">
                        <p class="text-3xl font-bold text-red-400">{{ $weken_onder_50 ?? '-' }}</p>
                        <p class="text-sm text-gray-600">Weken onder 50%</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border border-green-200">
                        <p class="text-3xl font-bold text-green-600">{{ $weken_boven_80 ?? '-' }}</p>
                        <p class="text-sm text-gray-600">Weken boven 80%</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                        <p class="text-3xl font-bold text-gray-800">{{ $totaal_weken ?? '-' }}</p>
                        <p class="text-sm text-gray-600">Totaal weken</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border border-purple-200">
                        <p class="text-3xl font-bold text-purple-800">{{ $student->rooster }}</p>
                        <p class="text-sm text-gray-600">Totaal rooster minuten</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border border-pink-200">
                        <p class="text-3xl font-bold text-pink-800">{{ $student->aanwezigheid }}</p>
                        <p class="text-sm text-gray-600">Totaal aanwezig minuten</p>
                    </div>
                </div><br>
                <div class="w-full h-[2px] bg-gray-300 my-4"></div>

                <!--  als je als student onder de categorie 'kritiek' valt krijg je deze melding te zien -->    
                @if($heeftKritiek)
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6 border-l-4 border-yellow-500">
                        <p><strong>⚠️ Aandachtspunt:</strong> Deze student heeft de afgelopen {{ $filters['tot_week'] - $filters['van_week'] + 1 }} weken een gemiddelde aanwezigheid van slechts {{ $gemiddelde ?? '-' }}%. Gesprek aanbevolen.</p>
                    </div>
                @endif

                <!-- Aanwezigheid per week -->
                <h3 class="text-xl font-semibold mt-8 mb-4">Aanwezigheid per week</h3>
                <div class="flex flex-wrap gap-3 fade-in">
                    @foreach ($aanwezigheidPerWeek as $aanwezigheid)
                        <div class="bg-{{ $aanwezigheid->kleur }}-500 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-300 hover:scale-105">
                            {{ round($aanwezigheid->percentage) }}%<br><span class="text-xs">W{{ $aanwezigheid->week }}</span><br>
                        </div>
                    @endforeach
                </div>

                <!-- Acties -->
                <h3 class="text-xl font-semibold mt-8 mb-4">Acties</h3>
                <div class="mt-6 flex flex-wrap gap-2">
                    <button id="btnStopStudying" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Markeer als gestopt</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variabele studentnummer beschikbaar maken in JS
    const studentnummer = @json($studentnummer);

    // animatie fade-in
    document.addEventListener('DOMContentLoaded', function () {
        const sections = document.querySelectorAll('.fade-in');

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.6 });

        sections.forEach(section => {
            observer.observe(section);
        });
    });

    // stop student knop functie
    document.getElementById('btnStopStudying').addEventListener('click', function() {
        if (!confirm('Weet je zeker dat je deze student als gestopt wilt markeren?')) {
            return;
        }

        fetch(`/student/${studentnummer}/stop`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Netwerkfout of server gaf een fout');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                const btn = document.getElementById('btnStopStudying');
                btn.disabled = true;
                btn.textContent = 'Gestopt gemarkeerd';
            } else {
                alert('Fout: ' + data.message);
            }
        })
        .catch(error => {
            alert('Er is iets misgegaan: ' + error.message);
        });
    });
</script>

@endsection
