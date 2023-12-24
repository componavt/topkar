        {!! Form::open(['url' => route('geotypes.index'), 
                             'method' => 'get']) 
        !!}
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
                ])                               
    <!-- 'special_symbol' => true,
                 //'help_func' => "callHelp('help-text-fields')",
    -->
@include('widgets.form._output_fields')
        {!! Form::close() !!}
