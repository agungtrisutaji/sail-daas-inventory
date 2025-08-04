export default {
  tableId: 'requestUpgradeTable',
  columns: [
    { data: 'DT_RowIndex', searchable: false, orderable: false },
    { data: 'ticket.ticket_number', name: 'ticket.ticket_number' },
    { data: 'operational_unit_name', name: 'operational_unit_name' },
    { data: 'unit_serial_number', name: 'unit_serial_number' },
    { data: 'unit_model', name: 'unit_model' },
    { data: 'offering_price', name: 'offering_price' },
    { data: 'status_badge', name: 'status' },
    {
      data: 'upgrade_type',
      name: 'upgrade_type',
    },
    {
      data: 'upgrade_remark',
      name: 'upgrade_remark',
    },
    { data: 'expense_part', name: 'expense_part' },
    { data: 'expense_engineer', name: 'expense_engineer' },
    { data: 'expense_delivery', name: 'expense_delivery' },
    { data: 'expense_total', name: 'expense_total' },
    {
      data: 'bast_date',
      name: 'bast_date',
      render: function (data) {
        return moment(data).format('YYYY-MM-DD HH:mm:ss');
      },
    },
    { data: 'engineer', name: 'engineer' },
    { data: 'action', name: 'action', orderable: false, searchable: false },
  ],
  filterFields: ['serial', 'brand', 'model', 'unit_status', 'service_code'],
  formId: 'filter-upgrade',
  resetButtonId: 'reset-upgrade-filter',
  liveSearch: false,
};
