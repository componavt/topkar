        {!! Form::open(['url' => route('streets.index'),
                             'method' => 'get'])
        !!}
<div class="row">
    <div class="col-md-3">
        @include('widgets.form.formitem._text',
                ['name' => 'search_name',
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
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
    <!--div class="col-md-4">
        @include('widgets.form.formitem._text',
                ['name' => 'search_id',
                 'value' => $url_args['search_id'],
                 'attributes' => ['placeholder' => 'ID'],
                ])
    </div-->
    @foreach ([7,8] as $hier_id)
    <div class="col-md-3">        
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_structs['.$hier_id.']', 
                 'values' => $struct_values,
                 'value' => empty($url_args['search_structs'][$hier_id]) 
                        ? [] : $url_args['search_structs'][$hier_id],
                 'class'=>'select-struct'.$hier_id.' form-control'
        ]) 
    </div>
    @endforeach
</div>
@include('widgets.form._output_fields')
        {!! Form::close() !!}
