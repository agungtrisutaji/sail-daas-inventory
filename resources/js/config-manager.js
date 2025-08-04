import moment from 'moment';
class ConfigManager {
  constructor(config) {
    this.config = {
      ...this.getDefaultConfig(),
      ...config,
    };
  }

  formatDate(date) {
    return moment(date).format('YYYY-MM-DD HH:mm:ss');
  }

  getDefaultConfig() {
    return {
      processing: true,
      serverSide: true,
      stateSave: true,
      colvis: true,
      paging: true,
      responsive: true,
      searching: false,
      scrollX: true,
      deferRender: true,
      buttons: [{ text: 'ColVis', extend: 'colvis' }],
      pageLength: 100,
      lengthMenu: [
        [10, 50, 100, 1000, 2000],
        [10, 50, 100, 1000, 2000],
      ],
      dom: '<"top"Bl><"clear">rt<"bottom"ip>',
      language: {
        processing: function (e, settings, processing) {
          $('.table_placeholder').css('display', 'none');
          if (processing) {
            $(e.currentTarget).show();
          }

          return ' <span class="table_spinner text-info spinner-border border-5" style="width: 7rem; height: 7rem;" role="status" aria-hidden="true"></span>';
        },

        emptyTable: 'No data available in table',
        info: 'Showing _START_ to _END_ of _TOTAL_ entries',
        infoEmpty: 'Showing 0 to 0 of 0 entries',
        infoFiltered: '(filtered from _MAX_ total entries)',
        lengthMenu: 'Show _MENU_ entries',
        loadingRecords: 'Loading...',
        search: 'Search:',
        zeroRecords: 'No matching records found',
      },
      scrollY: '50vh',
      scrollCollapse: true,
      scroller: true,
    };
  }

  getConfig() {
    return this.config;
  }

  getDataTableConfig() {
    return {
      processing: this.config.processing,
      serverSide: this.config.serverSide,
      stateSave: this.config.stateSave,
      colvis: this.config.colvis,
      paging: this.config.paging,
      responsive: this.config.responsive,
      searching: this.config.searching,
      searchPanes: this.config.searchPanes,
      scrollX: this.config.scrollX,
      buttons: this.config.buttons,
      pageLength: this.config.pageLength,
      lengthMenu: this.config.lengthMenu,
      dom: this.config.dom,
      rowGroup: this.config.rowGroup,
      order: this.config.order,
      language: this.config.language,
      ajax: {
        type: 'GET',
        url: this.config.dataUrl,
        dataSrc: 'data',
      },
      columns: this.config.columns,
    };
  }
}

export default ConfigManager;
