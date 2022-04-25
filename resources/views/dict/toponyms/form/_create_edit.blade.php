@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row"><!-- First row -->
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'name', 
                 'title'=>trans('toponym.name')])
        
        <!-- Region -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'region_id', 
                 'values' => $region_values,
                 'value' => optional($toponym)->regionValue(),
                 'title' => trans('toponym.region'), 
                 'is_multiple' => false,
                 'class'=>'select-region form-control'
                 ])

        <!-- District -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'district_id', 
                 'values' => $district_values,
                 'value' => optional($toponym)->districtValue(),
                 'title' => trans('toponym.district'),
                 'is_multiple' => false,
                 'class'=>'select-district form-control'
        ]) 
        
        <!-- Settlement -->
        @include('widgets.form.formitem._text', 
                ['name' => 'SETTLEMENT',                  
                 'title' => trans('toponym.settlement'),
                ])     
                
        @include('widgets.form.formitem._text', 
                ['name' => 'caseform', 
                 'title'=>trans('toponym.caseform')])
    </div>
    <div class="col-sm-6"><!-- Second column -->
        <!-- Geotype -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'geotype_id', 
                 'values' => $geotype_values,
                 'value' => optional($toponym)->geotypeValue(),
                 'title' => trans('aux.geotype'),
                 'is_multiple' => false,
                 'class'=>'select-geotype form-control'
        ])
        
        <!-- Region 1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'region1926_id', 
                 'values' => $region_values,
                 'value' => optional($toponym)->region1926Value(),
                 'title' => trans('toponym.region1926'), 
                 'is_multiple' => false,
                 'class'=>'select-region1926 form-control'
                 ])
                 
        <!-- District1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'district1926_id', 
                 'values' => $district1926_values,
                 'value' => optional($toponym)->district1926Value(),
                 'title' => trans('toponym.district1926'),
                 'is_multiple' => false,
                 'class'=>'select-district1926 form-control'
        ]) 
        
        <!-- Selsovet1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'selsovet1926_id', 
                 'values' => $selsovet1926_values,
                 'value' => optional($toponym)->selsovet1926Value(),
                 'title' => trans('toponym.selsovet1926'),
                 'is_multiple' => false,
                 'class'=>'select-selsovet1926 form-control'
        ]) 
        
        <!-- Settlement1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'settlement1926_id', 
                 'values' => $settlement1926_values,
                 'value' => optional($toponym)->settlement1926Value(),
                 'title' => trans('toponym.settlement1926'),
                 'is_multiple' => false,
                 'class'=>'select-settlement1926 form-control'
        ]) 
    </div><!-- eo Second column -->    
</div><!-- eo First row -->

<div class="row"><!-- Second row-->
    <div class="col-sm-6">
        <!-- Etymology nation -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'etymology_nation_id', 
                 'values' => $etymology_nation_values,
                 'value' => optional($toponym)->etymologyNationValue(),
                 'title' => trans('aux.etymology_nation'),
                 'is_multiple' => false,
                 'class'=>'select-etymology-nation form-control'
        ])
        @include('widgets.form.formitem._select2', 
                ['name' => 'ethnos_territory_id', 
                 'values' => $ethnos_territory_values,
                 'value' => optional($toponym)->ethnosTerritoryValue(),
                 'title' => trans('aux.ethnos_territory'),
                 'is_multiple' => false,
                 'class'=>'select-ethnos-territory form-control'
        ])
        @include('widgets.form.formitem._textarea', 
                ['name' => 'etymology', 
                 'attributes' => ['rows' => 3],
                 'title'=>trans('toponym.etymology')])
    </div>
    <div class="col-sm-6">
    </div>
</div><!-- eo Second row -->

@include('widgets.form.formitem._submit', ['title' => $submit_title])
