        {!! Form::open(['url' => route('informants.index'), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    <div class="col-md-9">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_name',                  
                 'value' => $url_args['search_name'],
                 'attributes' => ['placeholder' => mb_ucfirst(trans('messages.name'))],
                ])                               
    </div>        
    <div class="col-md-3">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_date',                  
                 'value' => $url_args['search_date'],
                 'attributes' => ['placeholder' => mb_ucfirst(trans('misc.birth_date'))],
                ])                               
    </div>        
</div>                 
@include('widgets.form._output_fields')
        {!! Form::close() !!}
