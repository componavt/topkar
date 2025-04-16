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
function selectDistrict(region_var, locale='ru', placeholder='', allow_clear=true, selector='.select-district', form='', route='/dict/districts/list'){
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL(form + " #"+region_var),
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

function selectDistrict1926(region_var, locale='ru', placeholder='', allow_clear=true, selector='.select-district1926', form=''){
    selectDistrict(region_var, locale, placeholder, allow_clear, selector, form, '/dict/districts1926/list');
}

function selectSelsovet1926(region_var, district_var, locale='ru', placeholder='', allow_clear=true, selector='.select-selsovet1926', form=''){
    var route='/dict/selsovets1926/list';
/*console.log(form);   
console.log(region_var);   
console.log(selectedValuesToURL(form + "#"+region_var));   */
    $(form + ' ' + selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL(form + " #"+region_var),
              districts: selectedValuesToURL(form + " #"+district_var)
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

function selectSettlement(region_var, district_var, locale='ru', placeholder='', allow_clear=true, selector='.select-settlement', form=''){    
    var route='/dict/settlements/list'
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL(form + " #"+region_var),
              districts: selectedValuesToURL(form + " #"+district_var),
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

function selectSettlement1926(region_var, district_var, selsovet_var, locale='ru', placeholder='', allow_clear=true, selector='.select-settlement1926', form=''){
    var route='/dict/settlements1926/list'
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL(form + " #"+region_var),
              districts: selectedValuesToURL(form + " #"+district_var),
              selsovets: selectedValuesToURL(form + " #"+selsovet_var)
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

function selectEventSettlement(region_var, locale='ru', placeholder='', allow_clear=true, selector='.select-settlement', form=''){    
    var route='/dict/settlements/list_with_districts'
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
//        width: 'resolve',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL(form + " #"+region_var),
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

function selectEventSettlement1926(region_var, locale='ru', placeholder='', allow_clear=true, selector='.select-settlement', form=''){    
    var route='/dict/settlements1926/list_with_districts'
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
//        width: 'resolve',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              regions: selectedValuesToURL(form + " #"+region_var),
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

function selectStruct(structhier_var, locale='ru', placeholder='', allow_clear=true, selector='.select-struct', form='', route='/misc/structs/list'){
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              structhiers: selectedValuesToURL(form + " #"+structhier_var),
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

function selectInformant(locale='ru', placeholder='', allow_clear=true, selector='.select-informant', form='', route='/misc/informants/list'){
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
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

function selectRecorder(locale='ru', placeholder='', allow_clear=true, selector='.select-recorder', form='', route='/misc/recorders/list'){
    $(selector).select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: '/'+locale+route,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
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

