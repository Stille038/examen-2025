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

@foreach ($studenten as $student)
    <div class="mb-4">
        <p><strong>{{ $student->studentnummer }}</strong></p>
        <p>Aanwezigheid: {{ $student->aanwezigheid }}</p>
        <p>Rooster: {{ $student->rooster }}</p>
        <p>Week: {{ $student->week }}</p>
        <p>Jaar: {{ $student->jaar }}</p>
    </div>
@endforeach



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
