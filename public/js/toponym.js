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

function addSettlement() {
    $("#modalAddSettlement #region_id").val($("#toponymForm #region_id").val());
    $("#modalAddSettlement #district_id").val($("#toponymForm #district_id").val());
    $("#modalAddSettlement").modal('show');
    
    $("#modalAddSettlement .close, #modalAddSettlement .cancel").click(function () {
        $("#modalAddSettlement").modal('hide');
    });
}

function saveSettlement() {
    var route = '/dict/settlements/store';
    var data = {'name_ru': $( "#modalAddSettlement #name_ru" ).val(),
                'name_en': $( "#modalAddSettlement #name_en" ).val(),
                'name_krl': $( "#modalAddSettlement #name_krl" ).val(),
                'name_vep': $( "#modalAddSettlement #name_vep" ).val(),
                'district_id': $( "#modalAddSettlement #districts_0__id_" ).val(),
                'district_from': $( "#modalAddSettlement #districts_0__from_").val(),
                'district_to':   $( "#modalAddSettlement #districts_0__to_" ).val(),
                'geotype_id': $("#modalAddSettlement #geotype_id").val(),
                'locale': $("#locale").val()
            };
console.log(data);

    $.ajax({
        url: route, 
        data: data,
        type: 'GET',
        success: function(settlement){       
            if (settlement) {
                var opt = new Option(settlement['name'], settlement['id']);
                $("#toponymForm #settlement_id").append(opt);
                opt.setAttribute('selected','selected')
                var opt2 = new Option(settlement['district_name'], settlement['district_id']);
                $("#toponymForm #district_id").append(opt2);
                opt2.setAttribute('selected','selected')
            }
            $("#modalAddSettlement").modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var text = 'ERROR';/*'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;*/
            alert(text);
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
    var locale = $("#locale").val();

    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               name_en: name_en,
               selsovet_id: selsovet_id,
               locale: locale
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

function addTopName(locale) {
    var num = parseInt($('#add-top-name').attr('data-count'));
    $.ajax({
        url: '/'+locale+'/dict/topnames/create', 
        data: {num: num},
        type: 'GET',
        success: function(html){       
            $('#new-topnames').append(html);
            $('#add-top-name').attr('data-count',1+num);
        },
        error: function () {
           alert('ERROR');
        }
    }); 
}

function addWrongName(locale) {
    var num = parseInt($('#add-wrong-name').attr('data-count'));
    $.ajax({
        url: '/'+locale+'/dict/wrongnames/create', 
        data: {num: num},
        type: 'GET',
        success: function(html){       
            $('#new-wrongnames').append(html);
            $('#add-wrong-name').attr('data-count',1+num);
        },
        error: function () {
           alert('ERROR');
        }
    }); 
}

function addSourceToponym(locale) {
    var num = $('#next-source_toponym-num').val();
    $.ajax({
        url: '/'+locale+'/misc/source_toponym/create', 
        data: {num: num},
        type: 'GET',
        success: function(html){       
            $('#new-source_toponym').append(html);
            $('#next-source_toponym-num').val(1+num);
        },
        error: function () {
           alert('ERROR');
        }
    }); 
}

function callMap() {
    $("#modalMap").modal('show'); 
}

function moveMarker(map, marker, lng, lat) {
    if (marker === undefined) {
        marker=L.marker({lon:lng , lat: lat}).bindPopup($('#name').val()).addTo(map);
        map.setView({lon:lng , lat: lat}, 12);
    } else {
        marker.setLatLng({lat,lng}).update();
    }
}