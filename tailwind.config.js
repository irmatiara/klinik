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
                sans: ['Montserrat', 'Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                hfc: {
                    primary: '#b224ae',
                    hover: '#8e1d8b',
                    light: '#fcf2fc',
                    dark: '#2b161b',
                    body: '#554b4e',
                    bg: '#f6f3f5',
                }
            }
        },
    },

    plugins: [forms],
};
