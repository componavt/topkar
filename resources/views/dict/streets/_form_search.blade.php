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
    <div class="col-md-12">
        @include('widgets.form.formitem._radio',
                ['name' => 'with_geometry',
                 'checked' => $url_args['with_geometry'],
                 'values' => trans('messages.with_answers'),
                 'title' => trans('toponym.with_geometry'),
                ])
    </div>
</div>
@include('widgets.form._output_fields')
        {!! Form::close() !!}
