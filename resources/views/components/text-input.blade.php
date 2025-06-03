@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-redish dark:focus:border-indigo-600 focus:ring-redish dark:focus:ring-redish rounded-md shadow-sm']) }}>
