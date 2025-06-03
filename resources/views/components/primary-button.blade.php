<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-t from-[#6E7671] to-[#21272D] hover:bg-gradient-to-b from-[#6E7671] to-[#21272D] border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest focus:bg-gray-700 dark:active:bg-gray-300 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
