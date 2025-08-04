export default {
  tableId: 'inventoryTable',
  buttons: [{ text: 'ColVis', extend: 'colvis' }],
  stateSave: false,
  pageLength: 100,
  columns: [
    { data: 'name', name: 'name' },
    { data: 'organization_name', name: 'organization_name' },
    { data: 'asset_number', name: 'asset_number' },
    { data: 'customerservice', name: 'customerservice' },
    { data: 'model_name', name: 'model_name' },
    { data: 'brand_name', name: 'brand_name' },
    {
      data: 'type',
      name: 'type',
      render: function (data) {
        if (typeof data === 'string') {
          return data
            .split(' ')
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
        }
        return data;
      },
    },
    { data: 'description', name: 'description' },
    {
      data: 'status',
      name: 'status',
      render: function (data) {
        if (typeof data === 'string') {
          return data
            .split(' ')
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
        }
        return data;
      },
    },
    { data: 'location_id_friendlyname', name: 'location_id_friendlyname' },
    {
      data: 'daascustomer_id_friendlyname',
      name: 'daascustomer_id_friendlyname',
    },
    {
      data: 'daascontact_id_friendlyname',
      name: 'daascontact_id_friendlyname',
    },
    {
      data: 'purchase_date',
      name: 'purchase_date',
      render: function (data) {
        if (data) {
          return moment(data).format('DD-MM-YYYY HH:mm');
        } else {
          return '-';
        }
      },
    },
    {
      data: 'created_at',
      name: 'created_at',
      visible: false,
      render: function (data) {
        if (data) {
          return moment(data).format('DD-MM-YYYY');
        } else {
          return '-';
        }
      },
    },
    {
      data: 'updated_at',
      name: 'updated_at',
      visible: false,
      render: function (data) {
        if (data) {
          return moment(data).format('DD-MM-YYYY');
        } else {
          return '-';
        }
      },
    },
    // { data: 'action', name: 'action', orderable: false, searchable: false },
  ],
  filterFields: [
    'name',
    'brand_name',
    'model_name',
    'status',
    'type',
    'asset_number',
    'customerservice',
    'organization_name',
    'purchase_date',
    'location_id_friendlyname',
    'daascontact_id_friendlyname',
    'daascustomer_id_friendlyname',
  ],
  formId: 'filter-form',
  resetButtonId: 'reset-filter',
  liveSearch: false,
  order: [5, 'desc'],
};
