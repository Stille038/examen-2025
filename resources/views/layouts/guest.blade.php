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
    <body class="antialiased bg-white min-h-screen" style="font-family: 'Cormorant Garamond', serif;">
        <div class="h-full min-h-screen flex flex flex-wrap lg:flex-nowrap">
            <!-- Left Side -->
            <div class="w-full lg:w-2/3 relative">
                <!-- Achtergrondafbeelding -->
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/Images/placeholder.png');"></div>

            </div>
 
                <!-- Right Side: Registration Form -->
                <div class="w-full lg:w-1/3 px-6 py-10 bg-white shadow-md flex flex-col items-center justify-center">
                    <img src="{{ asset('Images/.png') }}" class="logo h-32 w-32" alt="Logo">
        
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>     
</html>