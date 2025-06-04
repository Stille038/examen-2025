<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('custom.login') }}"> <!--POST action om actie uit tevoeren logica komt in andere bestand -->
        @csrf

        <!-- Studentnummer -->
        <div>
            <x-input-label for="studentennummer" :value="__('Studentennummer')" />
            <x-text-input id="studentennummer" class="block mt-1 w-full"
                type="text"
                name="studentennummer"
                placeholder="Studentennummer"
                :value="old('studentennummer')" />
        </div>

        <!-- Rollen -->
        <div class="mt-4">
            <x-input-label for="rollen" :value="__('Rollen')" />
            <x-select id="rollen" name="rollen">
                <option value="">-- Kies een rol --</option>
                <option value="student">Student</option>
                <option value="docent">Docent</option>
            </x-select>
        </div>

        <!-- Fouten tonen -->
        @if ($errors->any())
        <div class="mt-4 text-red-600 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Knoppen -->
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Inloggen') }}
            </button>
        </div>
    </form>
</x-guest-layout>