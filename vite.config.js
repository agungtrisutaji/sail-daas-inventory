import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/inventory-init.js',
        'resources/js/deployment-init.js',
        'resources/js/staging-init.js',
        'resources/js/delivery-create-init.js',
        'resources/js/request-upgrade-init.js',
        'resources/js/ticket-init.js',
        'resources/js/asset-transfer-init.js',
      ],
      refresh: true,
      publicDirectory: 'public',
    }),
  ],
  resolve: {
    alias: {
      '@': '/resources',
    },
  },
  build: {
    rollupOptions: {
      input: {
        main: 'resources/js/app.js',
        inventory: 'resources/js/inventory-init.js',
        deployment: 'resources/js/deployment-init.js',
        staging: 'resources/js/staging-init.js',
        deliveryCreate: 'resources/js/delivery-create-init.js',
        requestUpgrade: 'resources/js/request-upgrade-init.js',
        ticket: 'resources/js/ticket-init.js',
        assetTransfer: 'resources/js/asset-transfer-init.js',
      },
    },
  },
});
