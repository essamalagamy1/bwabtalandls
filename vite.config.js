import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],

    build: {
        sourcemap: false,
        minify: 'esbuild',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    alpine: ['alpinejs'],
                    cropper: ['cropperjs'],
                    sortable: ['sortablejs'],
                    easymde: ['easymde'],
                    'intl-tel-input': ['intl-tel-input'],
                },
            },
        },
    },
});
