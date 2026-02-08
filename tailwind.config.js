import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                // Midnight Garage Theme Colors
                garage: {
                    black: '#0a0a0a',
                    charcoal: '#1a1a1a',
                    darkgreen: '#0d2818',
                    forest: '#1a3d2e',
                    emerald: '#2dd4bf',
                    neon: '#10b981',
                    steel: '#94a3b8',
                    offwhite: '#f1f5f9',
                },
            },
            backgroundImage: {
                'garage-gradient': 'linear-gradient(135deg, #000000 0%, #0d2818 100%)',
                'carbon-fiber': 'repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(255,255,255,.03) 2px, rgba(255,255,255,.03) 4px)',
            },
            boxShadow: {
                'neon-green': '0 0 20px rgba(16, 185, 129, 0.3)',
                'neon-green-lg': '0 0 30px rgba(16, 185, 129, 0.5)',
                'garage': '0 8px 32px rgba(0, 0, 0, 0.4)',
            },
        },
    },

    plugins: [forms, typography],
};
