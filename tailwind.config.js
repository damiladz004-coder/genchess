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
            colors: {
                brand: {
                    50: '#f2f7ff',
                    100: '#e3eeff',
                    200: '#bfd7ff',
                    300: '#8fbbff',
                    400: '#5f98f5',
                    500: '#3d79d9',
                    600: '#2f5fb5',
                    700: '#274b8f',
                    800: '#243f73',
                    900: '#23375f',
                },
                accent: {
                    50: '#fff8e8',
                    100: '#ffefc7',
                    200: '#ffe094',
                    300: '#ffd05f',
                    400: '#f9bc2f',
                    500: '#e3a11a',
                    600: '#c28012',
                    700: '#9d6011',
                    800: '#824d15',
                    900: '#6f4116',
                },
            },
            fontFamily: {
                sans: ['Sora', ...defaultTheme.fontFamily.sans],
                display: ['"Source Serif 4"', ...defaultTheme.fontFamily.serif],
            },
            boxShadow: {
                soft: '0 10px 30px -16px rgba(16, 24, 40, 0.35)',
            },
            borderRadius: {
                xl2: '1.25rem',
            },
        },
    },

    plugins: [forms],
};
