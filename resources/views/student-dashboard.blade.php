@extends('layouts.app')

@section('content')

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
            <h1 class="text-2xl font-semibold mb-2">Mijn Aanwezigheid</h1>
            <p class="text-gray-600 mb-4">Student: 12345678 | Periode: 2024-2025</p>

            <!-- Filters -->
            <form method="GET" action="{{ route('student-dashboard') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="flex flex-col">
                        <x-input-label for="van_week" value="Van week" />
                        <x-select id="van_week" name="van_week" class="p-2 border rounded-md w-full">
                            @for ($i = 1; $i <= 52; $i++)
                                <option value="{{ $i }}" {{ (old('van_week', $filters['van_week'] ?? '') == $i) ? 'selected' : '' }}>
                                    Week {{ $i }}
                                </option>
                            @endfor
                        </x-select>
                    </div>

                    <div class="flex flex-col">
                        <x-input-label for="tot_week" value="Tot week" />
                        <x-select id="tot_week" name="tot_week" class="p-2 border rounded-md w-full">
                            @for ($i = 1; $i <= 52; $i++)
                                <option value="{{ $i }}" {{ (old('tot_week', $filters['tot_week'] ?? '') == $i) ? 'selected' : '' }}>
                                    Week {{ $i }}
                                </option>
                            @endfor
                        </x-select>
                    </div>

                    <div class="flex flex-col">
                        <x-input-label for="jaar" value="Jaar" />
                        <x-select id="jaar" name="jaar" class="p-2 border rounded-md w-full">
                            @foreach ([2024, 2025] as $jaar)
                                <option value="{{ $jaar }}" {{ (old('jaar', $filters['jaar'] ?? '') == $jaar) ? 'selected' : '' }}>
                                    {{ $jaar }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="flex items-end">
                        <x-primary-button class="w-full">Toon periode</x-primary-button>
                    </div>
                </div>
            </form>

            <!-- Statistieken -->
            <div class="grid grid-cols md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg shadow p-4 border border-yellow-400">
                    <p class="text-3xl font-bold text-yellow-400">{{ $stats['gemiddelde'] ?? '-' }}%</p>
                    <p class="text-sm text-gray-600">Gemiddelde aanwezigheid</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-red-200">
                    <p class="text-3xl font-bold text-red-400">{{ $stats['onder50'] ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Weken onder de 50%</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-green-200">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['boven80'] ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Weken boven de 80%</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['totaalWeken'] ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Totaal weken</p>
                </div>
            </div>


            <!-- Aanwezigheid per week -->
            <h3 class="text-xl font-semibold mt-8 mb-4">Aanwezigheid per week</h3>
            <div class="flex flex-wrap gap-3 fade-in">
                @foreach ($aanwezigheidPerWeek as $week => $percentage)
                    @php
                        // Kleuren bepalen op basis van percentage (zelfde als legenda)
                        if ($percentage == 100) {
                            $bgColor = 'bg-purple-600';
                        } elseif ($percentage >= 95) {
                            $bgColor = 'bg-blue-500';
                        } elseif ($percentage >= 80) {
                            $bgColor = 'bg-green-500';
                        } elseif ($percentage >= 65) {
                            $bgColor = 'bg-yellow-400';
                        } elseif ($percentage >= 50) {
                            $bgColor = 'bg-orange-400';
                        } elseif ($percentage > 0) {
                            $bgColor = 'bg-red-500';
                        } else {
                            $bgColor = 'bg-red-800';
                        }
                    @endphp
                    <div class="{{ $bgColor }} text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">
                        {{ $percentage }}%<br><span class="text-xs">W{{ $week }}</span>
                    </div>
                @endforeach
            </div>


            <!-- Legenda -->
            <div class="mt-6 border-t pt-4 text-sm text-gray-600 flex flex-wrap gap-4">
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-purple-600 rounded"></div> Perfect (100%)</div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-blue-500 rounded"></div> Excellent (95–99%)</div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-green-500 rounded"></div> Goed (80–94%)</div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-yellow-400 rounded"></div> Redelijk (65–79%)</div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-orange-400 rounded"></div> Onvoldoende (50–64%)</div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-red-500 rounded"></div> Kritiek (0–49%)</div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-red-800 rounded"></div> Fail (0%)</div>
            </div>
        </div>
    </div>
</div>

<script>
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

        // Observe each section
        sections.forEach(section => {
            observer.observe(section);
        });
    });
</script>

@endsection
