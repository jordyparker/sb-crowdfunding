import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true
        }),
        vue({
            template: {
                transformAssetUrlsOptions: {  //  transformAssetUrls (vue 3)
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ]
});
