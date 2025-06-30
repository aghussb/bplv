import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
  // publicPath: './',
  plugins: [
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      // ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue.es.js'),
    //   ziggy: 'vendor/tightenco/ziggy/dist/vue.es.js',
      // '@': path.resolve(__dirname, './src'),
      // '~': fileURLToPath(new URL('./resources', import.meta.url)),
      '~': fileURLToPath(new URL('./node_modules', import.meta.url)),
    },
  },
});
