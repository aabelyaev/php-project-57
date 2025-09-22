import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        assetsDir: 'assets',
        emptyOutDir: true,
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
    },
});
