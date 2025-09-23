import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: 'php-project-57-8905.onrender.com',
        hmr: {
            host: 'php-project-57-8905.onrender.com',
        },
    },
});
