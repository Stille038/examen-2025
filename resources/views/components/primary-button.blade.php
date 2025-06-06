<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-t from-[#6E7671] to-[#21272D] hover:bg-gradient-to-b from-[#6E7671] to-[#21272D] border border-transparent rounded-md font-semibold text-xs text-white   uppercase tracking-widest focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>