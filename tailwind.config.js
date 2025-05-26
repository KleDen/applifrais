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
      },
      colors: {
        gsbblue: {
          DEFAULT: '#72a8d4', // фирменный голубой
          dark:    '#5b8cb2', // чуть темнее для hover
        },
        gsbnavy: {
          DEFAULT: '#193c5b', // фирменный темно-синий
          dark:    '#142d48', // чуть темнее для hover
        },
      },
    },
  },

  plugins: [
    forms,
  ],
};
// https://tailwindcss.com/docs/configuration