import DataTableManager from './data-table-manager.js';
import deploymentConfig from './deployment-config.js';

$(() => {
  const $container = $('#deploymentContainer');
  if ($container.length) {
    const dataUrl = $container.data('url');
    const config = { ...deploymentConfig, dataUrl };
    new DataTableManager(config);
  }
});
