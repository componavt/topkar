        {!! Form::open(['url' => route('streets.index'),
                             'method' => 'get'])
        !!}
<div class="row">
    <div class="col-md-4">
        @include('widgets.form.formitem._text',
                ['name' => 'search_name',
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => trans('toponym.name')],
                ])
    </div>
    <div class="col-md-4">
        @include('widgets.form.formitem._select2',
                ['name' => 'search_geotype',
                 'values' => $geotype_values,
                 'value' => $url_args['search_geotype'],
                 'class'=>'select-geotype form-control'
                 ])
    </div>
    <div class="col-md-4">
        @include('widgets.form.formitem._text',
                ['name' => 'search_id',
                 'value' => $url_args['search_id'],
                 'attributes' => ['placeholder' => 'ID'],
                ])
    </div>
</div>
@include('widgets.form._output_fields')
        {!! Form::close() !!}
