import ConfigManager from './config-manager.js';
import FilterManager from './filter-manager.js';
import EventManager from './event-manager.js';

class DataTableManager {
  constructor(config) {
    try {
      this.configManager = new ConfigManager(config);
      this.filterManager = new FilterManager(this.configManager.getConfig());
      this.eventManager = new EventManager(this);
      this.table = null;
      this.init();
      this.setupSidebarListeners();
    } catch (error) {
      console.error('Error initializing DataTableManager:', error);
      // Consider sending error to a logging service
    }
  }

  init() {
    this.initDataTable();
    this.eventManager.initEventListeners();
    this.filterManager.fillFilterFromURL();
  }

  initDataTable() {
    const dtConfig = this.configManager.getDataTableConfig();
    dtConfig.ajax.data = (d) => this.filterManager.getAjaxData(d);
    dtConfig.drawCallback = (settings) => this.onDrawCallback(settings);
    dtConfig.deferRender = true; // Tunda render baris
    dtConfig.scroller = true; // Aktifkan virtual scrolling

    this.table = $(`#${this.configManager.getConfig().tableId}`).DataTable(
      dtConfig
    );
  }

  setupSidebarListeners() {
    const sidebar = document.querySelector('.app-sidebar');
    const observer = new ResizeObserver(() => this.debounceAdjustTable());

    observer.observe(sidebar);
  }

  debounceAdjustTable() {
    if (this.adjustTimeout) clearTimeout(this.adjustTimeout);

    this.adjustTimeout = setTimeout(() => {
      requestAnimationFrame(() => {
        if (this.table) this.table.columns.adjust();
      });
    }, 200); // Debounce dengan 200ms
  }

  onDrawCallback(settings) {
    // Implementasi kustom setelah tabel di-render
  }

  refreshTable() {
    this.table.ajax.reload();
  }

  getSelectedData() {
    return this.table.rows({ selected: true }).data().toArray();
  }
}

export default DataTableManager;
