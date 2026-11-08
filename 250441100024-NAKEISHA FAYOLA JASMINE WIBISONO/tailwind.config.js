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
                sans: ['Figtree', 'Segoe UI', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                // Warna utama e-Pustaka
                brand: {
                    DEFAULT: '#6d4c2f',
                    dark: '#55371f',
                    light: '#f5ecdf',
                },

                // Abu-abu bawaan diganti nuansa cokelat hangat,
                // sehingga semua kelas gray-* (teks, border, latar, tombol) ikut bertema cokelat.
                gray: {
                    50: '#faf7f2',
                    100: '#f5ecdf',
                    200: '#eadcc8',
                    300: '#d9c6ab',
                    400: '#a8967f',
                    500: '#7a6a58',
                    600: '#5f4f3f',
                    700: '#4a3a2c',
                    800: '#3a2a1d',
                    900: '#2a1d12',
                },

                // Warna aksen bawaan (indigo) diganti cokelat, dipakai fokus input, link, dan checkbox.
                indigo: {
                    50: '#f5ecdf',
                    100: '#eadcc8',
                    200: '#dcc5a5',
                    300: '#c4a279',
                    400: '#a67c52',
                    500: '#8a5f38',
                    600: '#6d4c2f',
                    700: '#55371f',
                    800: '#43291a',
                    900: '#321e12',
                },
            },
        },
    },

    plugins: [forms],
};
