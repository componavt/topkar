<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._select',
                ['name' => 'geotype_id',
                 'values' =>$geotype_values,
                 'title' => trans('misc.type')])

        @include('widgets.form.formitem._text',
                ['name' => 'name_ru',
                 'title'=>trans('toponym.name').' '.trans('messages.in_russian')])

        @include('widgets.form.formitem._text',
                ['name' => 'name_krl',
                 'special_symbol' => true,
                 'title'=>trans('toponym.name').' '.trans('messages.in_karelian')])

        @include('widgets.form.formitem._text',
                ['name' => 'name_fin',
                 'special_symbol' => true,
                 'title'=>trans('toponym.name').' '.trans('messages.in_finnish')])
    </div>
    <div class="col-sm-6">
        @include('widgets.form.formitem._textarea',
                ['name' => 'history',
                 'value' => isset($street) ? $street->history : null,
                 'attributes' => ['rows'=>7],
                 'title'=>trans('toponym.history')])
    </div>
</div>
