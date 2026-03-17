@include('widgets.modal',['name'=>'modalAddGeotype',
                       'title'=>trans('misc.add_geotype'),
                       'submit_onClick' => 'saveGeotype()',
                       'submit_title' => trans('messages.save'),
                       'modal_view'=>'misc.geotypes._form_create_edit'])

@include('widgets.modal',['name'=>'modalAddStruct',
                       'title'=>trans('misc.add_struct'),
                       'submit_onClick' => 'saveStruct()',
                       'submit_title' => trans('messages.save'),
                       'modal_view'=>'misc.structs._form_create_edit'])
