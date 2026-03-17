<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._select',
                ['name' => 'geotype_id',
                 'values' =>$geotype_values,
                 'value' => isset($street) ? $street->geotype_id : \App\Models\Dict\Street::Types[0],
                 'title' => trans('misc.geotype')])

        @include('widgets.form.formitem._text',
                ['name' => 'name_ru',
                 'title'=>trans('toponym.name').' '.trans('messages.in_russian')])

        @include('widgets.form.formitem._text',
                ['name' => 'name_krl',
                 'special_symbol' => true,
                 'title'=>trans('toponym.name').' '.trans('messages.in_karelian')])

        @include('widgets.form.formitem._text',
                ['name' => 'name_fi',
                 'special_symbol' => true,
                 'title'=>trans('toponym.name').' '.trans('messages.in_finnish')])


        <!--p><b>{{ trans('misc.struct') }}</b></p-->
        @foreach ($structhier_values as $structhier_id => $structhier_name)
        <p><b>{{ $structhier_name }}</b> <i onclick="addStruct({{ $structhier_id }})" class="call-add fa fa-plus fa-lg" title="{{trans('messages.insert_new_field')}}"></i></p>
            @include('widgets.form.formitem._select2',
                    ['name' => 'structs['.$structhier_id.']',
                     'values' => $struct_values[$structhier_id],
                     'value' => empty($street) ? []
                        : $street->structs()->where('structhier_id', $structhier_id)->pluck('struct_id')->toArray(),
                     'is_multiple' => false,
                     'class'=>'select-struct'.$structhier_id.' form-control'
            ])
        @endforeach

    </div>
    <div class="col-sm-6">
        @include('widgets.form.formitem._textarea',
                ['name' => 'main_info',
                 'value' => isset($street) ? $street->main_info : null,
                 'attributes' => ['rows'=>10],
                 'title'=>trans('toponym.main_info')])

        @include('widgets.form.formitem._textarea',
                ['name' => 'history',
                 'value' => isset($street) ? $street->history : null,
                 'attributes' => ['rows'=>9],
                 'title'=>trans('toponym.history')])
    </div>
</div>
