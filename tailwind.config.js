const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: "class",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "rgb(var(--color-primary) / <alpha-value>)",
            },
            screens: {
                '3xl': '2100px',
                '4xl': '3200px',
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms")({
            strategy: 'base', // only generate global styles
          }),
    ],
};
