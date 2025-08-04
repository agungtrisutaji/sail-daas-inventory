import DataTableManager from './data-table-manager.js';
import inventoryConfig from './inventory-config.js';

$(() => {
  try {
    const $container = $('#inventoryContainer');
    if ($container.length) {
      const dataUrl = $container.data('url');
      const config = { ...inventoryConfig, dataUrl };
      new DataTableManager(config);
    }
  } catch (error) {
    console.error('Error initializing inventory:', error);
    // Consider sending error to a logging service
  }
});
