        {!! Form::open(['url' => route('selsovets1926.index'), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-2">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
                ])                               
    <!-- 'special_symbol' => true,
                 //'help_func' => "callHelp('help-text-fields')",
    -->
    </div>        
    <div class="col-md-3">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_regions', 
                 'values' => $region_values,
                 'value' => $url_args['search_regions'],
                 'class'=>'select-region form-control'
                 ]) 
    </div>
    <div class="col-md-3">
        <!-- District1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_districts1926', 
                 'values' => $district1926_values,
                 'value' => $url_args['search_districts1926'],
                 'class'=>'select-district1926 form-control'
        ]) 
    </div>       
     
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}