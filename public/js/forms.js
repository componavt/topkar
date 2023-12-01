function clearForm(e) {    
    var f = $(e).closest('form'); 
    $.each(f[0].elements, function(index, elem){
//    f.each(function(){
console.log(index, elem);        
//        this.reset();
  //      this.val(null).trigger('change');
    });
}