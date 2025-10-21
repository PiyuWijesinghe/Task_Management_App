import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import scrollbar from 'tailwind-scrollbar';

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
                // Custom gradient colors with hex codes
                'gradient-start': '#667eea',
                'gradient-end': '#764ba2',
                'ocean-start': '#2E3192',
                'ocean-end': '#1BFFFF',
                'sunset-start': '#FF512F',
                'sunset-end': '#F09819',
                'forest-start': '#134E5E',
                'forest-end': '#71B280',
                'purple-dream-start': '#8360c3',
                'purple-dream-end': '#2ebf91',
                'fire-start': '#FF416C',
                'fire-end': '#FF4B2B',
                'sky-start': '#4facfe',
                'sky-end': '#00f2fe',
            },
            backgroundImage: {
                // Custom gradient backgrounds
                'gradient-ocean': 'linear-gradient(135deg, #2E3192 0%, #1BFFFF 100%)',
                'gradient-sunset': 'linear-gradient(135deg, #FF512F 0%, #F09819 100%)',
                'gradient-forest': 'linear-gradient(135deg, #134E5E 0%, #71B280 100%)',
                'gradient-purple-dream': 'linear-gradient(135deg, #8360c3 0%, #2ebf91 100%)',
                'gradient-fire': 'linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%)',
                'gradient-sky': 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'gradient-royal': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            },
        },
    },

    plugins: [forms, scrollbar],
};
