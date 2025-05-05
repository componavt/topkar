{!! Form::open(['url' => $route, 
                             'method' => 'get']) 
        !!}
<p>Дубликатами считаются топонимы с одинаковыми названием, видом объекта и выбранным атрибутам:</p>

<div class="row">    
@foreach ($group_by_fields->chunk(5) as $chunk)
    <div class="col-md-12 output-fields-b">
    @foreach ($chunk    as $field => $f_title)
        <div class='output-fields-e'>
            <label><input name="group_by[]" type="checkbox" hidden value="{{ $field }}"{{ 
                          in_array($field, $url_args['group_by']) ? ' checked' : '' 
                          }}><span></span></label>
            <span>{{ $f_title }}</span>
        </div>
    @endforeach
    </div>        
@endforeach
    <div class="col-md-12 output-fields-b">
    @foreach ($check_by_fields  as $field => $f_title)
        <div class='output-fields-e'>
            <label><input name="check_by[{{ $field }}]" type="checkbox" hidden value="1"{{ 
                          !empty($url_args['check_by'][$field]) ? ' checked' : '' 
                          }}><span></span></label>
            <span>{{ $f_title }}</span>
        </div>
    @endforeach
    </div>   
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
    <div class="col-md-3" style="text-align: right">
        {{ __('messages.created_at') }}
    </div>
    <div class="col-md-3">
        @include('widgets.form.formitem._DATE', 
                ['name' => 'created_at', 
                 'value' => $url_args['created_at']
        ]) 
    </div>
</div>
<div>{{ trans('toponym.cur_adm_div') }}</div>        
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
    @include('widgets.form._output_fields')
        {!! Form::close() !!}
