// Gets values from input field (e.g. search regions) into one variable
function selectedValuesToURL(varname) {
    var forURL = [];
    $( varname + " option:selected" ).each(function( index, element ){
        forURL.push($(this).val());
    });
    return forURL;
}
/*
function selectAjax(route, data, placeholder, allow_clear, selector){
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: route,
          dataType: 'json',
          delay: 250,
          data: data,
          processResults: function (result) {
            return {
              results: result
            };
          },          
          cache: true
        }
    });   
}
*/
function selectDistrict(region_var, placeholder='', allow_clear=true, selector='.select-district', route='/dict/districts/list'){
//console.log(selectedValuesToURL("#"+region_var));   
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL("#"+region_var)
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });   
}

function selectDistrict1926(region_var, placeholder='', allow_clear=true, selector='.select-district1926'){
    selectDistrict(region_var, placeholder, allow_clear, selector, route='/dict/districts1926/list');
}

function selectSelsovet1926(region_var, district_var, placeholder='', allow_clear=true, selector='.select-selsovet1926'){
    var route='/dict/selsovets1926/list'
//console.log(selectedValuesToURL("#"+region_var));   
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL("#"+region_var),
              districts: selectedValuesToURL("#"+district_var)
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });   
}

function selectSettlement1926(region_var, district_var, selsovet_var, placeholder='', allow_clear=true, selector='.select-settlement1926'){
    var route='/dict/settlements1926/list'
//console.log(selectedValuesToURL("#"+region_var));   
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL("#"+region_var),
              districts: selectedValuesToURL("#"+district_var),
              selsovets: selectedValuesToURL("#"+selsovet_var)
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });   
}
