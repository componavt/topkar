        {!! Form::open(['url' => route('sources.index'), 
                             'method' => 'get']) 
        !!}
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
                ])                               
        @include('widgets.form._output_fields')
        {!! Form::close() !!}
