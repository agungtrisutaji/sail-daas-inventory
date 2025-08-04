import DataTableManager from './data-table-manager.js';
import stagingConfig from './staging-config.js';

$(() => {
    const $container = $('#stagingContainer');
    if ($container.length) {
        const dataUrl = $container.data('url');
        const config = { ...stagingConfig, dataUrl };
        new DataTableManager(config);
    }
});
