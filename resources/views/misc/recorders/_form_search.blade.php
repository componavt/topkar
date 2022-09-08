        {!! Form::open(['url' => route('recorders.index'), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-8">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
                ])                               
    <!-- 'special_symbol' => true,
                 //'help_func' => "callHelp('help-text-fields')",
    -->
    </div>        
    
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}
