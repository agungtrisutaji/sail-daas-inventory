import { debounce } from './utility-functions.js';

class EventManager {
    constructor(dataTableManager) {
        this.dtManager = dataTableManager;
        this.config = this.dtManager.configManager.getConfig();
    }

    initEventListeners() {
        this.initFormSubmitListener();
        this.initResetButtonListener();
        this.initLiveSearchListener();
    }

    initFormSubmitListener() {
        $(`#${this.config.formId}`).on('submit', (e) => {
            e.preventDefault();
            this.dtManager.filterManager.applyFiltersToURL();
            this.dtManager.refreshTable();
        });
    }

    initResetButtonListener() {
        $(`#${this.config.resetButtonId}`).on('click', (e) => {
            e.preventDefault();
            $(`#${this.config.formId}`)[0].reset();
            this.dtManager.filterManager.clearURLParameters();
            this.dtManager.refreshTable();
        });
    }

    initLiveSearchListener() {
        if (this.config.liveSearch) {
            this.config.filterFields.forEach(field => {
                $(`#${field}`).on('input', debounce(() => {
                    this.dtManager.filterManager.applyFiltersToURL();
                    this.dtManager.refreshTable();
                }, 300));
            });
        }
    }
}

export default EventManager;
