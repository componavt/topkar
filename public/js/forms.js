function clearForm(e) {    
    var f = $(e).closest('form'); 
    $.each(f[0].elements, function(index, elem){
//    f.each(function(){
console.log(index, elem);        
//        this.reset();
  //      this.val(null).trigger('change');
    });
}

function toggleChecked(el, select_fields) {
    if ($(el).prop('checked')) {
    //console.log($(select_fields));            
        $(select_fields).prop('checked', true);
    } else {
        //console.log($(select_fields));            
        $(select_fields).prop('checked', false);
    }    
}