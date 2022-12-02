import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import path from "path";
import mkcert from 'vite-plugin-mkcert'

export default defineConfig({
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '@': '/resources/js',
        }
    },
    server: {
        https: false, // mettere a true in produzione
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        mkcert(),
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/tailwind.css'
            ],
            refresh: true,
        }),
    ],
});
