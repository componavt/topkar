        {!! Form::open(['url' => $route, 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-3">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_toponym',                  
                 'special_symbol' => true,
                 'full_special_list' => true,
                 'value' => $url_args['search_toponym'],
                 'attributes' => ['placeholder' => trans('toponym.toponym')],
                ])                               
    </div>        
    <div class="col-md-3">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_geotypes', 
                 'values' => $geotype_values,
                 'value' => $url_args['search_geotypes'],
                 'class'=>'select-geotype form-control'
        ]) 
    </div>
    <div class="col-md-3">        
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_ethnos_territories', 
                 'values' => $ethnos_territory_values,
                 'value' => $url_args['search_ethnos_territories'],
                 'class'=>'select-ethnos_territory form-control'
        ]) 
    </div>
    <div class="col-md-3">        
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_etymology_nations', 
                 'values' => $etymology_nation_values,
                 'value' => $url_args['search_etymology_nations'],
                 'class'=>'select-etymology_nation form-control'
        ]) 
    </div>
</div>
<div class="row">    
    <div class="col-md-3">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_regions', 
                 'values' => $region_values,
                 'value' => $url_args['search_regions'],
                 'class'=>'select-region form-control'
                 ]) 
    </div>
    {{--                                _select2 - helps to write name in search field --}} 
    <div class="col-md-3">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_districts', 
                 'values' => $district_values,
                 'value' => $url_args['search_districts'],
                 'class'=>'select-district form-control'
        ]) 
    </div>
        
    <div class="col-md-3">
        <!-- Settlement -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_settlements', 
                 'values' => $settlement_values,
                 'value' => $url_args['search_settlements'],
                 'class'=>'select-settlement form-control'
        ]) 
    </div>    
    <div class="col-md-3">
        <!-- Record place -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_record_places', 
                 'values' => $settlement_values,
                 'value' => $url_args['search_record_places'],
                 'class'=>'select-record-place form-control'
        ]) 
    </div>    
</div>    
<div class="row">
    <div class="col-md-3">
        <!-- Region 1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_regions1926', 
                 'values' => $region_values,
                 'value' => $url_args['search_regions1926'],
                 'class'=>'select-region1926 form-control'
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
    <div class="col-md-3">        
        <!-- Selsovet1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_selsovets1926', 
                 'values' => $selsovet1926_values,
                 'value' => $url_args['search_selsovets1926'],
                 'class'=>'select-selsovet1926 form-control'
        ]) 
    </div>       
    <div class="col-md-3">        
        <!-- Settlement1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_settlements1926', 
                 'values' => $settlement1926_values,
                 'value' => $url_args['search_settlements1926'],
                 'class'=>'select-settlement1926 form-control'
        ]) 
    </div>
</div>    
<div class="row">
    <div class="col-md-3">        
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_structhiers', 
                 'values' => $structhier_values,
                 'value' => $url_args['search_structhiers'],
                 'grouped' => true,
                 'class'=>'select-structhier form-control'
        ]) 
    </div>
    <div class="col-md-3">        
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_structs', 
                 'values' => $struct_values,
                 'value' => $url_args['search_structs'],
                 'class'=>'select-struct form-control'
        ]) 
    </div>
    <div class="col-md-3">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_informants', 
                 'values' => $informant_values,
                 'value' => $url_args['search_informants'],
                 'class'=>'select-informant form-control'
        ]) 
    </div>
    <div class="col-md-3">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_recorders', 
                 'values' => $recorder_values,
                 'value' => $url_args['search_recorders'],
                 'class'=>'select-recorder form-control'
        ]) 
    </div>
</div>    
<div class="row">    
    <div class="col-md-3">        
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_sources', 
                 'values' => $source_values,
                 'value' => $url_args['search_sources'],
                 'class'=>'select-source form-control'
        ]) 
    </div>
    <div class="col-md-3">
        @include('widgets.form.formitem._select', 
                ['name' => 'sort_by', 
                 'values' => $sort_values,
                 'value' => $url_args['sort_by'],
                 ]) 
    </div>
    
    @include('widgets.form._search_in_desc')
    
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}
