import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/booking-hourly.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/booking-hourly.js',
                'resources/js/booking-monthly.js',
                'resources/js/payment-success.js',
                'resources/js/payment-vietqr.js',
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
