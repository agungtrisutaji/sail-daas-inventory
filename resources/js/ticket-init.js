import DataTableManager from './data-table-manager.js';
import ticketConfig from './ticket-config.js';

$(() => {
  try {
    const $container = $('#ticketContainer');
    if ($container.length) {
      const dataUrl = $container.data('url');
      const config = { ...ticketConfig, dataUrl };
      new DataTableManager(config);
    }
  } catch (error) {
    console.error('Error initializing ticket:', error);
    // Consider sending error to a logging service
  }
});
