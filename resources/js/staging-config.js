export default {
  tableId: 'stagingTable',
  stateSave: false,
  columns: [
    { data: 'ref', name: 'ref', visible: true },
    { data: 'reffcustomer', name: 'reffcustomer', visible: true },
    { data: 'title', name: 'title', visible: true },
    {
      data: 'caller_id_friendlyname',
      name: 'caller_id_friendlyname',
      visible: true,
    },
    {
      data: 'team_id_friendlyname',
      name: 'team_id_friendlyname',
      visible: true,
    },
    { data: 'org_id_friendlyname', name: 'org_id_friendlyname', visible: true },
    {
      data: 'agent_id_friendlyname',
      name: 'agent_id_friendlyname',
      visible: true,
    },
    {
      data: 'location_id_friendlyname',
      name: 'location_id_friendlyname',
      visible: true,
    },
    {
      data: 'daascontact_id_friendlyname',
      name: 'daascontact_id_friendlyname',
      visible: true,
    },
    {
      data: 'daascustomer_id_friendlyname',
      name: 'daascustomer_id_friendlyname',
      visible: true,
    },
    { data: 'status', name: 'status', visible: true },
    { data: 'request_type', name: 'request_type', visible: true },
    {
      data: 'start_date',
      name: 'start_date',
      visible: true,
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
    },
    {
      data: 'end_date',
      name: 'end_date',
      visible: true,
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
    },
    {
      data: 'last_update',
      name: 'last_update',
      visible: true,
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
    },
    {
      data: 'close_date',
      name: 'close_date',
      visible: true,
      render: function (data) {
        return moment(data).format('DD-MM-YYYY HH:mm');
      },
    },
    { data: 'description', name: 'description', visible: true },
    { data: 'urgency', name: 'urgency', visible: true },
    { data: 'impact', name: 'impact', visible: true },
    { data: 'priority', name: 'priority', visible: true },
    {
      data: 'service_id_friendlyname',
      name: 'service_id_friendlyname',
      visible: true,
    },
    {
      data: 'servicesubcategory_id_friendlyname',
      name: 'servicesubcategory_id_friendlyname',
      visible: true,
    },
    // { data: 'action', name: 'action', orderable: false, searchable: false },
  ],
  filterFields: [
    'ref',
    'reffcustomer',
    'caller_id_friendlyname',
    'team_id_friendlyname',
    'org_id_friendlyname',
    'agent_id_friendlyname',
    'location_id_friendlyname',
    'daascontact_id_friendlyname',
    'daascustomer_id_friendlyname',
    'status',
    'request_type',
    'start_date',
    'end_date',
    'last_update',
    'close_date',
    'description',
    'title',
    'urgency',
    'impact',
    'priority',
    'service_id_friendlyname',
    'servicesubcategory_id_friendlyname',
    'impacted_ci_friendlyname',
  ],
  formId: 'filter-form',
  resetButtonId: 'reset-filter',
  liveSearch: false,
  pageLength: 100,
  order: [12, 'asc'],
};
