@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'name', 
                 'title'=>trans('toponym.name')])
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'region_id', 
                 'values' => $region_values,
                 'value' => optional($toponym)->DISTRICT_ID && $toponym->district 
                                ? $toponym->district->region_id
                                : $url_args['search_region'],
                 'title' => trans('toponym.region') 
                 ]) 

        @include('widgets.form.formitem._select2', 
                ['name' => 'district_id', 
                 'values' => $district_values,
                 'value' => (array)(optional($toponym)->DISTRICT_ID) ?? [],
                 'title' => trans('toponym.district'),
                 'is_multiple' => false,
                 'class'=>'select-district form-control'
        ]) 
        
        @include('widgets.form.formitem._text', 
                ['name' => 'SETTLEMENT',                  
                 'title' => trans('toponym.settlement'),
                ])                               
    </div>
    
</div>
@include('widgets.form.formitem._submit', ['title' => $submit_title])
