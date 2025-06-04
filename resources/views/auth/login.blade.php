<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form>
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Studentennummer')" />
            <x-text-input id="email" class="block mt-1 w-full" 
                                type="number" 
                                name="studentennummer" 
                                placeholder="Studentennummer"
                                :value="old('email')" 
                                />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="rollen" :value="__('Rollen')" />
                <x-select id="rollen" name="rollen" >
                    <option value="">-- Kies een rol --</option>
                    <option value="student">Student</option>
                    <option value="docent">Docent</option>
                </x-select>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <!-- <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-redish border-gray-300 dark:border-gray-700 text-redish shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Onthoudt') }}</span>
            </label> -->

            <!-- Registratie link -->
            <!-- <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Heb je nog geen account?") }}
                <a href="{{ route('register') }}" class="text-redish dark:text-indigo-400 hover:underline">
                    {{ __("Klik hier om te registreren.") }}
                </a>
            </p> -->
        </div>


        <div class="flex items-center justify-end mt-4">

            <a href="{{ route('student-dashboard') }}" class=" inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Studentenportaal') }}
            </a>

            <a class="ms-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Docentenportaal') }}
            </a>
        </div>
    </form>
</x-guest-layout>
