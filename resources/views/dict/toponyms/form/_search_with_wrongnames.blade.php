        {!! Form::open(['url' => route('toponyms.with_wrongnames'), 
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
                ['name' => 'search_regions', 
                 'values' => $region_values,
                 'value' => $url_args['search_regions'],
                 'class'=>'select-region form-control'
                 ]) 
    </div>
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
</div>    
<div class="row">
    <div class="col-md-3">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_geotypes', 
                 'values' => $geotype_values,
                 'value' => $url_args['search_geotypes'],
                 'class'=>'select-geotype form-control'
        ]) 
    </div>
    <div class="col-md-3">
        @include('widgets.form.formitem._select', 
                ['name' => 'sort_by', 
                 'values' => $sort_values,
                 'value' => $url_args['sort_by'],
                 ]) 
    </div>
    
    <div class="col-md-2" style='display: flex; flex-direction: column; justify-content: center'>
        @include('widgets.form.formitem._checkbox', 
                ['name' => 'in_desc', 
                'value' => 1,
                'checked' => $url_args['in_desc']==1,
                 'tail' => trans('messages.in_desc'),
                 ]) 
    </div>    
    
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}
