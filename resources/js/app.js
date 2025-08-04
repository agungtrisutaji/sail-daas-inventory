import './bootstrap';

import 'datatables.net-buttons';
import 'datatables.net-buttons/js/buttons.colVis.js';
import 'laravel-datatables-vite';
import 'datatables.net-rowgroup-bs5';

import '../css/app.css';
import '../sass/app.scss';
import './adminlte';
import 'jquery-ui';

import './images';
import moment from 'moment';

window.moment = moment;

import '@eonasdan/tempus-dominus/src/scss/tempus-dominus.scss';
import { TempusDominus } from '@eonasdan/tempus-dominus';

$(() => {
  ClassicEditor.create(document.querySelector('#editor'), {
    toolbar: {
      items: [
        'heading',
        '|',
        'bold',
        'italic',
        'link',
        '|',
        'blockQuote',
        '|',
        'bulletedList',
        'numberedList',
        '|',
        'undo',
        'redo',
      ],
    },
    language: 'en',
    placeholder: 'Start typing here...',
    removePlugins: [
      'CKFinderUploadAdapter',
      'CKFinder',
      'EasyImage',
      'Image',
      'ImageCaption',
      'ImageStyle',
      'ImageToolbar',
      'ImageUpload',
      'MediaEmbed',
    ],
  }).catch((error) => {
    console.error(error);
  });

  $('#createModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let url = button.data('url');
    let modal = $(this);

    $.ajax({
      url: url,
      type: 'GET',
      success: function (response) {
        modal.find('.modal-body').html(response);

        window.getDateTime();
        window.getCompanyLocations();
      },
      error: function (xhr) {
        console.error(xhr.status + ': ' + xhr.statusText);
      },
    });
  });

  function getDateTime() {
    let datetimepicker = document.querySelectorAll('.datetimepicker');
    if (!datetimepicker) {
      return;
    }

    datetimepicker.forEach((datetimepicker) => {
      new TempusDominus(datetimepicker, {
        display: {
          icons: {
            type: 'icons',
            time: 'fa-solid fa-clock',
            date: 'fa-solid fa-calendar',
            up: 'fa-solid fa-arrow-up',
            down: 'fa-solid fa-arrow-down',
            previous: 'fa-solid fa-chevron-left',
            next: 'fa-solid fa-chevron-right',
            today: 'fa-solid fa-calendar-check',
            clear: 'fa-solid fa-trash',
            close: 'fa-solid fa-xmark',
          },
          buttons: {
            today: true,
            clear: true,
            close: false,
          },
          components: {
            calendar: true,
            date: true,
            month: true,
            year: true,
            decades: true,
            clock: true,
            hours: true,
            minutes: true,
            seconds: false,
          },
          theme: 'auto',
        },
        localization: {
          format: 'yyyy-MM-dd HH:mm:ss',
          hourCycle: 'h24',
        },
      });
    });
  }

  function updateAddressDropdown(companyId, addressSelectId) {
    $.ajax({
      url: '/api/get-addresses/' + companyId,
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        var addressSelect = $('#' + addressSelectId);
        addressSelect.empty().append('<option value=""></option>');

        $.each(data, function (key, value) {
          addressSelect.append(
            $('<option>', {
              value: value.id,
              text: value.location,
            })
          );
        });

        addressSelect.trigger('change');
      },
      error: function (xhr, status, error) {
        console.error('Error fetching addresses: ' + error);
      },
    });
  }

  function getCompanyLocations() {
    let companySelect = $('#company-select');
    let addressSelect = $('#address-select');
    if (!companySelect) {
      return;
    }

    companySelect.on('change', (event) => {
      let companyId = event.target.value;
      let addressSelectId = $(addressSelect).attr('id');
      if (companyId) {
        updateAddressDropdown(companyId, addressSelectId);
      } else {
        addressSelect
          .empty()
          .append('<option value="">Select an address</option>')
          .trigger('change');
      }
    });
  }

  getCompanyLocations();
  getDateTime();

  window.getCompanyLocations = getCompanyLocations;
  window.getDateTime = getDateTime;
});
