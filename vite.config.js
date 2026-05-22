import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/booking-hourly.css',
                'resources/css/profile.css',
                'resources/js/app.js',
                'resources/js/booking-hourly.js',
                'resources/js/booking-monthly.js',
                'resources/js/profile.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
