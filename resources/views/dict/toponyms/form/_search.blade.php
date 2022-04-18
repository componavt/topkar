        {!! Form::open(['url' => '/dict/toponyms/', 
                             'method' => 'get']) 
        !!}
<div class="row">
    
    <div class="col-md-4">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_toponym', 
                 
                 'value' => $url_args['search_toponym'],
                 'attributes' => ['placeholder' => trans('toponym.toponym')],
                ])                               
    </div>
    
    <!-- 'special_symbol' => true,
                 //'help_func' => "callHelp('help-text-fields')",
    -->
    
    
    <div class="col-md-4">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_region', 
                 'values' => $region_values,
                 'value' => $url_args['search_region'],
                 'attributes' => ['placeholder' => trans('toponym.region')] 
                 ]) 
    </div>
    {{--                                _select2 - helps to write name in search field --}} 
    <div class="col-md-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_district', 
                 'values' => $district_values,
                 'value' => $url_args['search_district'],
                 'class'=>'select-district form-control'
        ]) 
    </div>
        
    <div class="col-md-4">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_settlement', 
                 
                 'value' => $url_args['search_settlement'],
                 'attributes' => ['placeholder' => trans('toponym.settlement')],
                ])                               
    </div>    
    
    {{-- 
    <div class="col-md-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_place', 
                 'values' => $place_values,
                 'value' => $url_args['search_place'],
                 'title' => trans('corpus.place'). ' '. trans('corpus.of_recording'),
                 'class'=>'select-place form-control'
        ]) 
    </div>
    --}}
    
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}
