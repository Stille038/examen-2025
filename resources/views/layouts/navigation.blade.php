<nav x-data="{ open: false }" class="top-0 bg-gradient text-white border-b border-gray-100 dark:border-gray-700 shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 relative">
        <h1 class="justify-start text-sm lg:text-xl font-bold flex lg:justify-center item-center items-center gap-2 text-center"> <!-- Hier straks check om heen doen, wie is ingelogd docent of student, H1 veranderen op basis daarvan en welke pagina dat is moet tekst ook veranderen -->
            📚 Docent Dashboard – Groepsoverzicht
        </h1>
        <div class="absolute pr-2 right-0 top-0 mt-2">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::check() ? Auth::user()->name : 'Navigatie' }}</div> <!-- check of gebruiker is ingelogd zo niet? navigatie komt te staan -->
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-2 w-52 text-sm">
                        <h3 class="font-bold text-gray-800 dark:text-gray-100 px-4 pb-1">Navigatie</h3>

                        <x-dropdown-link :href="route('dashboard')" class="text-sky-600 hover:underline px-4 py-1 block">
                            Student Dashboard
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('docent.dashboard')" class="text-sky-600 hover:underline px-4 py-1 block">
                            Docent Overzicht
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('test1')" class="text-sky-600 hover:underline px-4 py-1 block">
                            Individuele Student
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('test2')" class="text-sky-600 hover:underline px-4 py-1 block">
                            Import Interface
                        </x-dropdown-link>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
    </div>


    <!-- Mobiele navigatie -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Home
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('test')" :active="request()->routeIs('test')">
                test
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('test1')" :active="request()->routeIs('test1')">
                test1
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('test2')" :active="request()->routeIs('test2')">
                test2
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                Contact
            </x-responsive-nav-link>
        </div>
    </div>
</nav>