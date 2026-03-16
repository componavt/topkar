<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._select',
                ['name' => 'geotype_id',
                 'values' =>$geotype_values,
                 'value' => isset($street) ? $street->geotype_id : \App\Models\Dict\Street::Types[0],
                 'title' => trans('misc.type')])

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

        <p><b>{{ trans('misc.struct') }}</b></p>
        @for ($i=0; $i < sizeof($structs); $i++)
        <div class='row'>
            <div class="col-sm-6">
            @include('widgets.form.formitem._select', 
                    ['name' => 'structhiers['.$i.']', 
                     'values' => $structhier_values,
                     'value' => $structhiers[$i],
                     'grouped' => true,
                     'attributes'=>['placeholder'=>trans('misc.structhier')]
            ]) 
            </div>
            <div class="col-sm-6">
            @include('widgets.form.formitem._select2', 
                    ['name' => 'structs['.$i.']', 
                     'values' => $struct_values,
                     'value' => $structs[$i],
                     'is_multiple' => false,
                     'class'=>'select-struct'.$i.' form-control'
            ]) 
            </div>            
        </div>
        @endfor
                 
    </div>
    <div class="col-sm-6">
        @include('widgets.form.formitem._textarea',
                ['name' => 'history',
                 'value' => isset($street) ? $street->history : null,
                 'attributes' => ['rows'=>10],
                 'title'=>trans('toponym.history')])
                 
        @include('widgets.form.formitem._textarea',
                ['name' => 'main_info',
                 'value' => isset($street) ? $street->main_info : null,
                 'attributes' => ['rows'=>10],
                 'title'=>trans('toponym.main_info')])
    </div>
</div>
