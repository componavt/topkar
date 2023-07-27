        {!! Form::open(['url' => route('toponyms.with_wd'), 
                             'method' => 'get']) 
        !!}
<div class="row">    
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}
