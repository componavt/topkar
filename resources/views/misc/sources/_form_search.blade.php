        {!! Form::open(['url' => route('sources.index'), 
                             'method' => 'get']) 
        !!}
        <div class="row"> 
            <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
                ])  
            </div>
            <div class="col-sm-3">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_year_from',                  
                 'value' => $url_args['search_year_from'] ? $url_args['search_year_from'] : '',
                 'attributes' => ['placeholder' => trans('search.year_from')],
                ])      
            </div>
            <div class="col-sm-3">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_year_to',                  
                 'value' => $url_args['search_year_to'] ? $url_args['search_year_to'] : '',
                 'attributes' => ['placeholder' => trans('search.year_to')],
                ])      
            </div>
        </div>
        @include('widgets.form._output_fields')
        {!! Form::close() !!}
