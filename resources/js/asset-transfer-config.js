export default {
  tableId: 'assetTransferTable',
  columns: [
    { data: 'DT_RowIndex', searchable: false, orderable: false },
    { data: 'transfer_number_action', name: 'transfer_number_action' },
    { data: 'jarvis_ticket', name: 'jarvis_ticket' },
    { data: 'unit_serial', name: 'unit_serial' },
    { data: 'unit_model', name: 'unit_model' },
    { data: 'unit_category', name: 'unit_category' },
    { data: 'status_badge', name: 'status' },
    { data: 'company_customer', name: 'company_customer' },
    { data: 'from_holder', name: 'from_holder' },
    { data: 'to_holder', name: 'to_holder' },
    { data: 'from_company', name: 'from_company' },
    { data: 'from_location', name: 'from_location' },
    { data: 'to_company', name: 'to_company' },
    { data: 'to_location', name: 'to_location' },
    { data: 'qc_pass', name: 'qc_pass' },
    { data: 'qc_by', name: 'qc_by' },
    { data: 'qc_date', name: 'qc_date' },
    { data: 'unit_service', name: 'unit_service' },
    { data: 'restaging', name: 'restaging' },
    { data: 'operational_unit_name', name: 'operational_unit_name' },
    {
      data: 'start_date',
      name: 'start_date',
      render: function (data) {
        return moment(data).format('YYYY-MM-DD HH:mm:ss');
      },
    },
    {
      data: 'finish_date',
      name: 'finish_date',
      render: function (data) {
        if (data == null) {
          return '-';
        }
        return moment(data).format('YYYY-MM-DD HH:mm:ss');
      },
    },
  ],
  filterFields: ['serial', 'brand', 'model', 'unit_status', 'service_code'],
  formId: 'filter-upgrade',
  resetButtonId: 'reset-upgrade-filter',
  liveSearch: false,
};
