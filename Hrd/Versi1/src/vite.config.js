import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                // ← WAJIB ada di sini agar Vite tahu file ini perlu di-build
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),
    ],

    // Vite 7 + @tailwindcss/vite kadang butuh resolve alias
    resolve: {
        alias: {
            // Tidak perlu vendor alias — theme.css kita sudah standalone
        },
    },
});