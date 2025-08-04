import DataTableManager from './data-table-manager.js';
import deliveryCreateConfig from './delivery-create-config.js';

$(() => {
  try {
    const $container = $('#deliveryCreateContainer');
    if ($container.length) {
      const dataUrl = $container.data('url');
      const config = { ...deliveryCreateConfig, dataUrl };
      new DataTableManager(config);
    }
  } catch (error) {
    console.error('Error initializing inventory:', error);
    // Consider sending error to a logging service
  }
});
