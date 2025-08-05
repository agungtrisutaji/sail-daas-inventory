import DataTableManager from './data-table-manager.js';
import stagingConfig from './staging-config.js';

$(() => {
  try {
    const $container = $('#stagingContainer');
    if ($container.length) {
      const dataUrl = $container.data('url');
      const config = { ...stagingConfig, dataUrl };
      new DataTableManager(config);
    }
  } catch (error) {
    console.error('Error initializing staging:', error);
    // Consider sending error to a logging service
  }
});
// $(() => {
//     const $container = $('#stagingContainer');
//     if ($container.length) {
//         const dataUrl = $container.data('url');
//         const config = { ...stagingConfig, dataUrl };
//         new DataTableManager(config);
//     }
// });
