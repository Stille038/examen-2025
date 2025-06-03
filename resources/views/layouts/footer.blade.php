<footer class="bg-beige text-dark py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

            <!-- logo -->
                <a href="/">
                    <img src="{{ asset('Images/test.png') }}" class="logo h-32 w-32" alt="logo">
                </a>

    
        </div>


        <!-- Copyright Section -->
        <div class="text-center mt-8 border-t border-gray-700 pt-4">
            <p class="text-sm text-gray-400">&copy; 2025 test. Alle rechten voorbehouden.</p>
            <a href="{{ route('privacy') }}" class="hover:text-redish">Privacybeleid</a><br>
            <a href="{{ route('terms') }}" class="hover:text-redish">Algemene Voorwaarden</a>
        </div>
    </div>
</footer>
