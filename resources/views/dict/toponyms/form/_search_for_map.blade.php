    <div class="col-md-5">
        <div>{{ __('toponym.coords_min') }} (<a href="#" id="select-min-coords">{{ __('toponym.point') }}</a>)</div>
        <div class="row">
            <div class="col-md-6">        
                @include('widgets.form.formitem._text', 
                        ['name' => 'min_lat',                  
                         'value' => $url_args['min_lat'],
                         'attributes' => ['placeholder' => trans('toponym.latitude')],
                        ])     
            </div>
            <div class="col-md-6">        
                @include('widgets.form.formitem._text', 
                        ['name' => 'min_lon',                  
                         'value' => $url_args['min_lon'],
                         'attributes' => ['placeholder' => trans('toponym.longitude')],
                        ])     
            </div>
        </div>
    </div>        
    <div class="col-md-5">
        <div>{{ __('toponym.coords_max') }} (<a href="#" id="select-max-coords">{{ __('toponym.point') }}</a>)</div>
        <div class="row">
            <div class="col-md-6">        
                @include('widgets.form.formitem._text', 
                        ['name' => 'max_lat',                  
                         'value' => $url_args['max_lat'],
                         'attributes' => ['placeholder' => trans('toponym.latitude')],
                        ])     
            </div>
            <div class="col-md-6">        
                @include('widgets.form.formitem._text', 
                        ['name' => 'max_lon',                  
                         'value' => $url_args['max_lon'],
                         'attributes' => ['placeholder' => trans('toponym.longitude')],
                        ])     
            </div>
        </div>
    </div>        
    <div class="col-md-2">
        <div>{{ __('toponym.map_height') }}</div>
        @include('widgets.form.formitem._text', 
                ['name' => 'map_height',                  
                 'value' => $url_args['map_height']
                ])     
    </div>    
    <div class='col-sm-5 output-fields-b'>
        <div class='output-fields-e'>
            <label><input name="outside_bounds" type="checkbox" hidden value="1"{{ $url_args['outside_bounds']==1 ? ' checked' : '' }}><span></span></label>
            <span>{{ __('toponym.outside_bounds') }}</span>
        </div>
    </div>    
    <div class='col-sm-5 output-fields-b'>
        <div class='output-fields-e'>
            <label><input name="popup_all" type="checkbox" hidden value="1"{{ $url_args['popup_all']==1 ? ' checked' : '' }}><span></span></label>
            <span id='for-portion'>{{ __('toponym.popup_all') }}</span>
        </div>
    </div>

