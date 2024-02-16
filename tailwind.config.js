const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/components/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                'raleway': ['Raleway']
            },
            colors: {
                primary: {
                    "light": "#D29760",
                    DEFAULT: "#CA8141",
                    "dark": "#9F642D"
                },
                secondary: "#33302b",
                accent: "#f6f1ed"
            },
            screens: {
                'print': {'raw': 'print'}
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

};
