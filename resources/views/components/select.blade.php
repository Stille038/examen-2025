@props(['value'])

<select {{ $attributes->merge(['class' => 'p-2 border rounded-md w-full focus:border-grayish focus:ring-grayish']) }}>
    {{ $value ?? $slot }}
</select>
