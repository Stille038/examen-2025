import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                belleza: ["Belleza", "sans-serif"],
            },

            colors: {
                beige: "#F9F6ED",
                grayish: "#6E7671",
                dark: "#21272D",
                redish: "#EA5141",
                lichtpaars: "#697ae5",
                donkerpaars: "#7650a9",
            },
        },
    },

    plugins: [forms],
};
