export default {
  tableId: 'terminationTable',
  stateSave: true,
  columns: [
    { data: 'DT_RowIndex', searchable: false, orderable: false },
    { data: 'serial_action', name: 'serial_action' },
    { data: 'staging_monitor', name: 'staging_monitor' },
    { data: 'staging_number', name: 'staging_number' },
    { data: 'holder_name', name: 'holder_name' },
    { data: 'service_label', name: 'service_label' },
    {
      data: 'operational_name',
      name: 'operational_name',
    },
    { data: 'brand', name: 'brand' },
    { data: 'model', name: 'model' },
    { data: 'unit_category', name: 'unit_category' },
    { data: 'company_name', name: 'company_name' },
    { data: 'company_code', name: 'company_code' },
    { data: 'company_group', name: 'company_group' },
    { data: 'company_location', name: 'company_location' },
    { data: 'request_category_label', name: 'request_category' },
    { data: 'status_badge', name: 'status' },
    { data: 'deployment_state', name: 'is_deployed' },
    { data: 'sla', name: 'sla' },
    {
      data: 'staging_start',
      name: 'staging_start',
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
      visible: true,
    },
    {
      data: 'staging_finish',
      name: 'staging_finish',
      render: function (data) {
        if (!data) {
          return '';
        }
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
      visible: true,
    },
    {
      data: 'created_at',
      name: 'created_at',
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
      visible: true,
    },
    {
      data: 'updated_at',
      name: 'updated_at',
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
      visible: true,
    },
    // { data: 'action', name: 'action', orderable: false, searchable: false },
  ],
  filterFields: [
    'serial',
    'brand',
    'model',
    'unit_status',
    'service_code',
    'status',
    'is_deployed',
  ],
  formId: 'filter-form',
  resetButtonId: 'reset-filter',
  liveSearch: false,
  pageLength: 100,
  order: [20, 'desc'],
};
