import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'dark-blue': {
                    DEFAULT: '#3B0EFF',
                    50: '#FFFFFF',
                    100: '#FFFFFF',
                    200: '#FFFFFF',
                    300: '#E1DAFF',
                    400: '#C0B1FF',
                    500: '#9F88FF',
                    600: '#7D60FF',
                    700: '#5C37FF',
                    800: '#3B0EFF',
                    900: '#2800D5',
                    950: '#2300B9'
                },
            }, 
        },
    },

    plugins: [forms, typography],
};
