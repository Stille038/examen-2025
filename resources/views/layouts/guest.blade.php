<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
 
        <title>{{ config('app.name', 'Laravel') }}</title>
 
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Playwrite+VN:wght@100..400&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
 
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="antialiased min-h-screen bg-cover bg-center bg-gradient-to-r from-blue-500 to-purple-600" style="font-family: 'Cormorant Garamond', serif;">
        <div class="h-full min-h-screen flex flex-wrap lg:flex-nowrap px-6 py-10">
            <div class="w-full shadow-xl flex flex-col items-center justify-center bg-white bg-opacity-80 rounded-2xl p-6">
            <h1 class="text-6xl font-semibold mb-32 text-center" style="font-family: 'Cormorant Garamond', serif;">AARDATA</h1>
                {{ $slot }}
            </div>
        </div>
    </body>
</body>  
</html>