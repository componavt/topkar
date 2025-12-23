        {!! Form::open(['url' => route('sources.index'), 
                             'method' => 'get']) 
        !!}
        <div class="row"> 
            <div class="col-sm-9">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
                ])  
            </div>
            <div class="col-sm-3">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_year',                  
                 'value' => $url_args['search_year'] ? $url_args['search_year'] : '',
                 'attributes' => ['placeholder' => mb_ucfirst(trans('messages.year'))],
                ])      
            </div>
        </div>
        @include('widgets.form._output_fields')
        {!! Form::close() !!}
