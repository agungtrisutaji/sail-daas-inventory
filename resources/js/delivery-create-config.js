export default {
  tableId: 'deliveryItemTable',
  scrollX: true,
  searching: true,
  stateSave: false,
  serverSide: false,
  dom: 'Bfrtip',
  buttons: [],
  rowGroup: {
    dataSrc: ['company_name', 'location'],
    emptyDataGroup: ['No Company and Location'],
    startRender: function (rows, group, level) {
      return $('<tr/>').append(
        '<th colspan="10">' + group + ' (' + rows.count() + ')</td>'
      );
    },
  },
  columns: [
    { data: 'check', searchable: false, orderable: false },
    { data: 'serial', name: 'serial', orderable: false },
    { data: 'staging_monitor', name: 'staging_monitor', orderable: false },
    { data: 'unit_category', name: 'unit_category', orderable: true },
    { data: 'service_name', name: 'service_name' },
    { data: 'model', name: 'model', orderable: false },
    { data: 'brand', name: 'brand', orderable: false },
    {
      data: 'company_name',
      name: 'company_name',
    },
    {
      data: 'location',
      name: 'location',
    },
    {
      data: 'created_at',
      name: 'created_at',
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
      visible: false,
    },
    {
      data: 'updated_at',
      name: 'updated_at',
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
      visible: false,
    },
  ],
  filterFields: ['serial', 'brand', 'model', 'service_code', 'company_name'],
  formId: 'filter-form',
  resetButtonId: 'reset-filter',
  liveSearch: false,
  searchPanes: true,
  order: [9, 'desc'],
};
