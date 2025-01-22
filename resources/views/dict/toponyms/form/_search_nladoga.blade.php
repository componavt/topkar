        {!! Form::open(['url' => route('toponyms.'.$route), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-4">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_toponym',                  
                 'special_symbol' => true,
                 'full_special_list' => true,
                 'value' => $url_args['search_toponym'],
                 'attributes' => ['placeholder' => trans('toponym.toponym')],
                ])                               
    </div>        
    <div class="col-md-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_geotypes', 
                 'values' => $geotype_values,
                 'value' => $url_args['search_geotypes'],
                 'class'=>'select-geotype form-control'
        ]) 
    </div>
    <div class="col-md-4">
        <!-- Settlement -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_settlements', 
                 'values' => $settlement_values,
                 'value' => $url_args['search_settlements'],
                 'class'=>'select-settlement form-control'
        ]) 
    </div>    
</div>                 
@include('widgets.form._output_fields')
        {!! Form::close() !!}
