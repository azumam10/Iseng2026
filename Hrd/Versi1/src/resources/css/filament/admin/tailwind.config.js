import preset from './vendor/filament/support/tailwind.config.preset';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                // Palet HRIS: Mint → Ice Blue → Aquamarine → Bright Cyan
                hris: {
                    'mint-bg':  '#f0fafa',
                    'mint-100': '#e0f5f2',
                    'mint-200': '#ccf5f0',
                    'border':   '#c8eee9',
                    'aqua':     '#5eead4',
                    'cyan-lt':  '#22d3ee',
                    'cyan':     '#06b6d4',
                    'teal':     '#0e7490',
                    'teal-dk':  '#0c4a6e',
                    'muted':    '#6aabab',
                    'subtle':   '#94c9c9',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                'xl': '14px',
                '2xl': '20px',
            },
        },
    },
};