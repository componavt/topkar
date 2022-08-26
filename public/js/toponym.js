function toFirstLetterUpperCase(str) {
    return str.substr(0, 1).toUpperCase() + str.substr(1);
}

function addDistrict(year='') {
    $("#modalAddDistrict" + year + " #region_id").val($("#toponymForm #region" + year + "_id").val());
    $("#modalAddDistrict" + year).modal('show');
    
    $("#modalAddDistrict" + year + " .close, #modalAddDistrict" + year + " .cancel").click(function () {
        $("#modalAddDistrict" + year).modal('hide');
    });
}

function saveDistrict(year='') {
    var name_ru = $("#modalAddDistrict" + year + " #name_ru").val();
    var name_en = $("#modalAddDistrict" + year + " #name_en").val();
    var region_id = $("#modalAddDistrict" + year + " #region_id").val();
    var locale = $("#locale").val();
    var route = '/dict/districts' + year + '/store';
    var test_url = '?name_ru='+name_ru+'&name_en='+name_en+'&region_id='+region_id;
    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               name_en: name_en, 
               region_id: region_id,
               locale: locale
              },
        type: 'GET',
        success: function(district){       
console.log("#district" + year + "_id");
            if (district) {
                var opt = new Option(district['name'], district['id']);
                $("#district" + year + "_id").append(opt);
                opt.setAttribute('selected','selected')
                var opt2 = new Option(district['region_name'], district['region_id']);
                $("#toponymForm #region" + year + "_id").append(opt2);
                opt2.setAttribute('selected','selected')
            }
            $("#modalAddDistrict" + year).modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('ERROR');
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;
            console.log(text);
        }
    }); 
}

function addSelsovet1926() {
    $("#modalAddSelsovet1926 #region1926_id").val($("#toponymForm #region1926_id").val());
    $("#modalAddSelsovet1926 #district1926_id").val($("#toponymForm #district1926_id").val());
    $("#modalAddSelsovet1926").modal('show');
    
    $("#modalAddSelsovet1926 .close, #modalAddSelsovet1926 .cancel").click(function () {
        $("#modalAddSelsovet1926").modal('hide');
    });
}

function saveSelsovet1926() {
    var name_ru = $("#modalAddSelsovet1926 #name_ru").val();
    var name_en = $("#modalAddSelsovet1926 #name_en").val();
    var name_krl = $("#modalAddSelsovet1926 #name_krl").val();
    var district_id = $("#modalAddSelsovet1926 #district1926_id").val();
    var locale = $("#locale").val();
    var route = '/dict/selsovets1926/store';
    var test_url = '?name_ru='+name_ru+'&name_en='+name_en+'&name_krl='+name_krl+'&district1926_id='+district_id;
    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               name_en: name_en, 
               name_krl: name_krl, 
               district1926_id: district_id,
               locale: locale
              },
        type: 'GET',
        success: function(selsovet){       
            if (selsovet) {
                var opt = new Option(selsovet['name'], selsovet['id']);
                $("#toponymForm #selsovet1926_id").append(opt);
                opt.setAttribute('selected','selected')
                var opt2 = new Option(selsovet['district_name'], selsovet['district_id']);
                $("#toponymForm #district1926_id").append(opt2);
                opt2.setAttribute('selected','selected')
            }
            $("#modalAddSelsovet1926").modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('ERROR');
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;
            console.log(text);
        }
    }); 
}

function addSettlement1926() {
    $("#modalAddSettlement1926 #region1926_id").val($("#toponymForm #region1926_id").val());
    $("#modalAddSettlement1926 #district1926_id").val($("#toponymForm #district1926_id").val());
    $("#modalAddSettlement1926 #selsovet_id").val($("#toponymForm #selsovet1926_id").val());
    $("#modalAddSettlement1926").modal('show');
    
    $("#modalAddSettlement1926 .close, #modalAddSettlement1926 .cancel").click(function () {
        $("#modalAddSettlement1926").modal('hide');
    });
}

function saveSettlement1926() {
    var selsovet_id = $( "#modalAddSettlement1926 #selsovet_id" ).val();
    var name_ru = $( "#modalAddSettlement1926 #name_ru" ).val();
    var name_en = $( "#modalAddSettlement1926 #name_en" ).val();
    var name_krl = $( "#modalAddSettlement1926 #name_krl" ).val();
    var route = '/dict/settlements1926/store';
    var test_url = '?name_ru='+name_ru+'&name_en='+name_en+'&name_krl='+name_krl+'&selsovet_id='+selsovet_id;

    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               name_en: name_en,
               selsovet_id: selsovet_id,
              },
        type: 'GET',
        success: function(settlement){       
            if (settlement) {
                var opt = new Option(settlement['name'], settlement['id']);
                $("#toponymForm #settlement1926_id").append(opt);
                opt.setAttribute('selected','selected')
                var opt2 = new Option(settlement['selsovet_name'], settlement['selsovet_id']);
                $("#toponymForm #selsovet1926_id").append(opt2);
                opt2.setAttribute('selected','selected')
            }
            $("#modalAddSettlement1926").modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;
            alert(text);
        }
    }); 
}

function addGeotype() {
    $("#modalAddGeotype").modal('show');
    
    $("#modalAddGeotype .close, #modalAddGeotype .cancel").click(function () {
        $("#modalAddGeotype").modal('hide');
    });
}

function saveGeotype() {
    var name_ru = $( "#modalAddGeotype #name_ru" ).val();
    var name_en = $( "#modalAddGeotype #name_en" ).val();
    var short_ru = $( "#short_ru" ).val();
    var short_en = $( "#short_en" ).val();
    var desc_ru = $( "#desc_ru" ).val();
    var desc_en = $( "#desc_en" ).val();
    var route = '/dict/geotypes/store';
    var test_url = '?name_ru='+name_ru+'&name_en='+name_en+'&short_ru='+short_ru
            +'&desc_ru='+desc_ru+'&short_en='+short_en+'&desc_en='+desc_en;

    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               name_en: name_en,
               short_ru: short_ru,
               short_en: short_en,
               desc_ru: desc_ru,
               desc_en: desc_en,
              },
        type: 'GET',
        success: function(geotype){       
            if (geotype) {
                var opt = new Option(geotype['name'], geotype['id']);
                $("#toponymForm #geotype_id").append(opt);
                opt.setAttribute('selected','selected')
            }
            $("#modalAddGeotype").modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;
            alert(text);
        }
    }); 
}

function addTopName() {
    $('#new-topnames').append('<input class="form-control" name="new_topname[]" type="text" value="">');
}

