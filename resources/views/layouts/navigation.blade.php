<nav x-data="{ open: false }" class="top-0 bg-gradient-to-r from-blue-500 to-purple-600 text-white border-b border-gray-100 dark:border-gray-700 shadow">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <h1 class="text-xl font-bold flex justify-center items-center gap-2">
            📚 Docent Dashboard – Groepsoverzicht
        </h1>
            <!-- Settings Dropdown -->
           <!-- Settings Dropdown (altijd zichtbaar) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 justify-end">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::check() ? Auth::user()->name : 'Navigatie' }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (Auth::check())
                            <x-dropdown-link :href="route('profile.edit')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                {{ __('Profiel') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    {{ __('Log uit') }}
                                </x-dropdown-link>
                            </form>
                        @else
                            <x-dropdown-link :href="route('login')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                {{ __('Log in') }}
                            </x-dropdown-link>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>

        

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('test')" :active="request()->routeIs('gordijnen')">
                {{ __('test') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('test1')" :active="request()->routeIs('tapijt')">
                {{ __('test1') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('test2')" :active="request()->routeIs('laminaat')">
                {{ __('test2') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                {{ __('Contact') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-3 pb-3 border-t border-gray-200 dark:border-gray-600">
 
            @if (Auth::check()) 
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-500 dark:text-gray-400 hover:text-dark dark:hover:text-gray-300">
                    {{ __('Profiel') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="block px-4 py-2 text-gray-500 dark:text-gray-400 hover:text-dark dark:hover:text-gray-300">
                        {{ __('Log uit') }}
                    </a>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-500 dark:text-gray-400 hover:text-dark dark:hover:text-gray-300">
                    {{ __('Log in') }}
                </a>
            @endif
        </div>
    </div>
</nav>