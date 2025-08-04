import DataTableManager from './data-table-manager.js';
import requestUpgradeConfig from './request-upgrade-config.js';

$(() => {
  try {
    const $container = $('#requestUpgradeContainer');
    if ($container.length) {
      const dataUrl = $container.data('url');
      const config = { ...requestUpgradeConfig, dataUrl };
      new DataTableManager(config);
    }
  } catch (error) {
    console.error('Error initializing request upgrade:', error);
    // Consider sending error to a logging service
  }
});
