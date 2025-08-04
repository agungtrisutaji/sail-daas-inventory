import DataTableManager from '../data-table-manager.js';
import terminationConfig from '../config/termination-config.js';

$(() => {
  const $container = $('#terminationContainer');
  if ($container.length) {
    const dataUrl = $container.data('url');
    const config = { ...terminationConfig, dataUrl };
    new DataTableManager(config);
  }
});
