const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
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
                'aesthetic': {
                    'base': '#F8FAFC',    // Brighter, cleaner white
                    'lavender': '#DDD6FE', // Saturated purple
                    'rose': '#FECACA',     // Saturated rose/pink
                    'mint': '#CCFBF1',     // Clearer mint
                    'sky': '#BAE6FD',      // Vibrant sky blue
                    'ink': '#1E293B',      // Deep slate for maximum text readability
                    'dusty': '#475569',    // Mid-tone for secondary info
                },
            },
            boxShadow: {
                'chic': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05)',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};