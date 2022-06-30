        {!! Form::open(['url' => route('settlements1926.index'), 
                             'method' => 'get']) 
        !!}
<div class="row">    
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
    <div class="col-md-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_regions', 
                 'values' => $region_values,
                 'value' => $url_args['search_regions'],
                 'class'=>'select-region form-control'
                 ]) 
    </div>
    <div class="col-md-4">
        <!-- District1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_districts1926', 
                 'values' => $district1926_values,
                 'value' => $url_args['search_districts1926'],
                 'class'=>'select-district1926 form-control'
        ]) 
    </div>       
    <div class="col-md-8">        
        <!-- Selsovet1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_selsovets1926', 
                 'values' => $selsovet1926_values,
                 'value' => $url_args['search_selsovets1926'],
                 'class'=>'select-selsovet1926 form-control'
        ]) 
    </div>       
     
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}
