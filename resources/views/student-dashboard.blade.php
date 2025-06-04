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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="flex flex-col">
                    <x-input-label for="Van week">Van week</x-label>
                        <x-select id="Van week" class="p-2 border rounded-md w-full">
                            <option>Week 1</option>
                        </x-select>
                </div>

                <div class="flex flex-col">
                    <x-input-label for="Tot week">Tot week</x-label>
                        <x-select id="Tot week" class="p-2 border rounded-md w-full">
                            <option>Week 49</option>
                        </x-select>
                </div>

                <div class="flex flex-col">
                    <x-input-label for="jaar">Jaar</x-label>
                        <x-select id="jaar" class="p-2 border rounded-md w-full">
                            <option>2024</option>
                            <option>2025</option>
                        </x-select>
                </div>
                <x-primary-button class="w-32">
                    {{ __('Toon periode') }}
                </x-primary-button>
            </div>

            <!-- Statistieken -->
            <div class="grid grid-cols md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg shadow p-4 border border-yellow-400">
                    <p class="text-3xl font-bold text-yellow-400">73%</p>
                    <p class="text-sm text-gray-600">Gemiddelde aanwezigheid</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-red-200">
                    <p class="text-3xl font-bold text-red-400">7</p>
                    <p class="text-sm text-gray-600">Weken onder de 50%</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-green-200">
                    <p class="text-3xl font-bold text-green-600">12</p>
                    <p class="text-sm text-gray-600">Weken boven de 80%</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <p class="text-3xl font-bold text-gray-800">24</p>
                    <p class="text-sm text-gray-600">Totaal weken</p>
                </div>
            </div>

            <!-- Aanwezigheid per week -->
            <h3 class="text-xl font-semibold mt-8 mb-4">Aanwezigheid per week</h3>
            <div class="flex flex-wrap gap-3 fade-in">
                <div class="bg-purple-600 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">100%<br><span class="text-xs">W1</span></div>
                <div class="bg-blue-500 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">95%<br><span class="text-xs">W2</span></div>
                <div class="bg-green-500 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">85%<br><span class="text-xs">W3</span></div>
                <div class="bg-yellow-400 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">72%<br><span class="text-xs">W4</span></div>
                <div class="bg-orange-500 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">55%<br><span class="text-xs">W5</span></div>
                <div class="bg-red-500 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">30%<br><span class="text-xs">W6</span></div>
                <div class="bg-red-800 text-white px-10 py-8 rounded-md shadow transform transition-transform duration-200 hover:scale-105">0%<br><span class="text-xs">W7</span></div>
            </div>

            <!-- Legenda -->
            <div class="mt-6 border-t pt-4 text-sm text-gray-600 flex flex-wrap gap-4">
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 bg-purple-600 rounded"></div> Perfect (100%)
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div> Excellent (95–99%)
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 bg-green-500 rounded"></div> Goed (80–94%)
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 bg-yellow-400 rounded"></div> Redelijk (65–79%)
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 bg-orange-400 rounded"></div> Onvoldoende (50–64%)
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 bg-red-500 rounded"></div> Kritiek (0–49%)
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 bg-red-800 rounded"></div> Fail (0%)
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('.fade-in');

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.6
        });

        // Observe each section
        sections.forEach(section => {
            observer.observe(section);
        });
    });
</script>
@endsection