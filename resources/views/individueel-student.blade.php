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
                 <!-- Terug knop -->
                <div class="mt-4">
                    <button class="text-lg text-gray-600 hover:underline">&larr; Terug naar overzicht</button>
                </div>

                <!-- Student Info -->
                <div class="bg-white rounded-xl shadow mt-4 p-6">
                    <h2 class="text-xl font-semibold mb-1">Individueel Student Overzicht</h2>
                    <p class="text-gray-600 mb-4">Maria Jansen (87654321) | Groep: HBO-ICT-2A</p>

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
                    </div><br>
                    <div class="w-full h-[2px] bg-gray-300 my-4"></div>


                <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6 border-l-4 border-yellow-500">
                    <p><strong>⚠️ Aandachtspunt:</strong> Deze student heeft de afgelopen 8 weken een gemiddelde aanwezigheid van slechts 28%. Gesprek aanbevolen.</p>
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

                <!-- Acties -->
                <h3 class="text-xl font-semibold mt-8 mb-4">Acties</h3>
                <div class="mt-6 flex flex-wrap gap-2">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Email naar student</button>
                    <button class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">Contact ouders/verzorgers</button>
                    <button class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Notitie toevoegen</button>
                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">⚠️ Markeer als risicostudent</button>
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

        sections.forEach(section => {
            observer.observe(section);
        });
    });
</script>
@endsection
