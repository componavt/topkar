        {!! Form::open(['url' => route('structs.index'), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-4">
            @include('widgets.form.formitem._select', 
                    ['name' => 'search_structhiers', 
                     'values' => $structhier_values,
                     'value' => $url_args['search_structhiers'],
                     'grouped' => true,
                     'attributes'=>['placeholder'=>trans('misc.structhier')]
            ]) 
    </div>            
    <div class="col-md-4">
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
