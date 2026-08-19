import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/dashboard.css',
                'resources/css/login.css',
                'resources/js/app.js',
                'resources/js/passkeys.js',
                'resources/js/login.js',
                'resources/js/admin-dashboard.js',
                'resources/js/barbearia-dashboard.js',
                'resources/js/user-dashboard.js',
            ],
            refresh: true,
        }),
    ],
});
