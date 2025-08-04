class FilterManager {
  constructor(config) {
    this.config = config;
  }

  getAjaxData(d) {
    const params = {};
    this.config.filterFields.forEach((field) => {
      const value = $(`#${field}`).val();
      if (value && value !== '') {
        params[field] = value;
      }
    });
    return { ...d, ...params };
  }

  applyFiltersToURL() {
    const params = new URLSearchParams();
    this.config.filterFields.forEach((field) => {
      const value = $(`#${field}`).val();
      if (value && value !== '') {
        params.append(field, value);
      }
    });
    const newURL = `${window.location.pathname}?${params.toString()}`;
    history.pushState(null, '', newURL);
  }

  clearURLParameters() {
    history.pushState(null, '', window.location.pathname);
  }

  fillFilterFromURL() {
    const params = new URLSearchParams(window.location.search);
    params.forEach((value, key) => {
      $(`#${key}`).val(value);
    });
    return params.toString() !== '';
  }
}

export default FilterManager;
