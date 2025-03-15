import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        origin: 'http://localhost:1080',
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
            ],
            refresh: true,
        }),

    ],
    resolve: {
        alias: {

        },
    },
});





