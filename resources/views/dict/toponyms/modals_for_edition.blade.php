@include('widgets.modal',['name'=>'modalAddGeotype',
                      'title'=>trans('misc.add_geotype'),
                      'submit_onClick' => 'saveGeotype()',
                      'submit_title' => trans('messages.save'),
                      'modal_view'=>'misc.geotypes._form_create_edit'])

@include('widgets.modal',['name'=>'modalAddDistrict',
                      'title'=>trans('toponym.add_district'),
                      'submit_onClick' => 'saveDistrict()',
                      'submit_title' => trans('messages.save'),
                      'modal_view'=>'dict.districts._form_create_edit'])

@include('widgets.modal',['name'=>'modalAddSettlement',
                      'title'=>trans('toponym.add_settlement'),
                      'submit_onClick' => 'saveSettlement()',
                      'submit_title' => trans('messages.save'),
                      'modal_view'=>'dict.settlements._form_create_edit'])
                      
@include('widgets.modal',['name'=>'modalAddDistrict1926',
                      'title'=>trans('toponym.add_district1926'),
                      'submit_onClick' => 'saveDistrict(1926)',
                      'submit_title' => trans('messages.save'),
                      'modal_view'=>'dict.districts1926._form_create_edit'])

@include('widgets.modal',['name'=>'modalAddSelsovet1926',
                      'title'=>trans('toponym.add_selsovet1926'),
                      'submit_onClick' => 'saveSelsovet1926()',
                      'submit_title' => trans('messages.save'),
                      'modal_view'=>'dict.selsovets1926._form_create_edit'])

@include('widgets.modal',['name'=>'modalAddSettlement1926',
                      'title'=>trans('toponym.add_settlement1926'),
                      'submit_onClick' => 'saveSettlement1926()',
                      'submit_title' => trans('messages.save'),
                      'modal_view'=>'dict.settlements1926._form_create_edit'])

@include('widgets.modal',['name'=>'modalMap',
                      'title'=>trans('toponym.coords_from_map'),
                      'modal_view'=>'widgets.leaflet.karelia_on_map'])
                      