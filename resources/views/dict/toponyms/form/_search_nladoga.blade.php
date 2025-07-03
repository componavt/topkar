        {!! Form::open(['url' => route('toponyms.'.$route), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_toponym',                  
                 'special_symbol' => true,
                 'full_special_list' => true,
                 'value' => $url_args['search_toponym'],
                 'attributes' => ['placeholder' => trans('toponym.toponym')],
                ])                               
    </div>        
    <div class="col-md-6">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_geotypes', 
                 'values' => $geotype_values,
                 'value' => $url_args['search_geotypes'],
                 'class'=>'select-geotype form-control'
        ]) 
    </div>
</div>                 
<div>{{ trans('toponym.cur_adm_div') }}</div>        
<div class="row">    
    <div class="col-md-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_districts', 
                 'values' => $district_values,
                 'value' => $url_args['search_districts'],
                 'attributes' => ['placeholder' => trans('toponym.district')],
        ]) 
    </div>
        
    <div class="col-md-6">
        <!-- Settlement -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_settlements', 
                 'values' => $settlement_values,
                 'value' => $url_args['search_settlements'],
                 'class'=>'select-settlement form-control'
        ]) 
    </div>    
</div>    
<div>{{ trans('toponym.early_adm_div') }}</div>        
<div class="row">
    <div class="col-md-4">
        <!-- District1926 -->
        @include('widgets.form.formitem._select', 
                ['name' => 'search_districts1926', 
                 'values' => $district1926_values,
                 'value' => $url_args['search_districts1926'],
                 'attributes' => ['placeholder' => trans('toponym.district')],
        ]) 
    </div>       
    <div class="col-md-4">        
        <!-- Selsovet1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_selsovets1926', 
                 'values' => $selsovet1926_values,
                 'value' => $url_args['search_selsovets1926'],
                 'class'=>'select-selsovet1926 form-control'
        ]) 
    </div>       
    <div class="col-md-4">        
        <!-- Settlement1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_settlements1926', 
                 'values' => $settlement1926_values,
                 'value' => $url_args['search_settlements1926'],
                 'class'=>'select-settlement1926 form-control'
        ]) 
    </div>
</div>    
@if (!empty($for_map))
    @include("dict.toponyms.form._output_for_map")
@else    
    @include('widgets.form._output_fields')
@endif
        {!! Form::close() !!}
