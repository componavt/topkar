        {!! Form::open(['url' => route('informants.index'), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('messages.name')],
                ])                               
    <!-- 'special_symbol' => true,
                 //'help_func' => "callHelp('help-text-fields')",
    -->
    </div>        
    <div class="col-md-2">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_date',                  
                 'value' => $url_args['search_date'],
                 'attributes' => ['placeholder' => trans('misc.birth_date')],
                ])                               
    <!-- 'special_symbol' => true,
                 //'help_func' => "callHelp('help-text-fields')",
    -->
    </div>        
    
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}
