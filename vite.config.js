import {defineConfig,loadEnv} from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';
import path from "path";
import fs from 'fs';

// const host = 'gestionale.dev'
// const env = loadEnv('production',process.cwd(),'')
// const serverConfig = env.APP_ENV === 'production' ? {} : {
//     host,
//     hmr: { host },
//     https: {
//         key: fs.readFileSync('C:\\laragon\\etc\\ssl\\laragon.key'),
//         cert: fs.readFileSync('C:\\laragon\\etc\\ssl\\laragon.crt')
//     }
// }
export default defineConfig({
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '@': '/resources/js',
        }
    },
    // server: serverConfig,
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/tailwind.css'
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
            ],
        }),
    ],
});
