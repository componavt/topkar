function selectDistrict(region_var, placeholder='', allow_clear=false, selector='.select-district'){
    var route = "/dict/districts/list";
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
              region_id: selectedValuesToURL("#"+region_var)
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


// Gets values from input field (e.g. search regions) into one variable
function selectedValuesToURL(varname) {
    var forURL = [];
    $( varname + " option:selected" ).each(function( index, element ){
        forURL.push($(this).val());
    });
    return forURL;
}
