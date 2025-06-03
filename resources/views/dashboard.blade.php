@extends('layouts.app')

@section('content')

<!-- CSS voor Animatie -->
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



<section class="py-12">

</section>


<section class="py-12">

</section>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
    @foreach ($studenten as $student)
        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-200 hover:shadow-lg transition">
            <h2 class="text-xl font-semibold text-indigo-600 mb-2">
                {{ $student->studentnummer }}
            </h2>
            <ul class="text-gray-700 space-y-1text-sm">
                <li><span class="font-medium text-gray-900">Aanwezigheid:</span> {{ $student->aanwezigheid }}</li>
                <li><span class="font-medium text-gray-900">Rooster:</span> {{ $student->rooster }}</li>
                <li><span class="font-medium text-gray-900">Week:</span> {{ $student->week }}</li>
                <li><span class="font-medium text-gray-900">Jaar:</span> {{ $student->jaar }}</li>
            </ul>
        </div>
    @endforeach
</div>



<section class="py-12 ">
    
</section>

<!-- JavaScript voor Scroll Animaties -->
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
