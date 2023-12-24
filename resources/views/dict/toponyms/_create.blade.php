@section('headTitle', trans('messages.new_m'). ' '. trans('toponym.toponym'))

@section('page_top')   
    <h2>{{ trans('messages.new_m'). ' '. trans('toponym.toponym') }}</h2>
@endsection

@section('top_links')   
    {!! to_list('toponym', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('toponym', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['toponyms.store'], 'id'=>'toponymForm']) !!}
    @include('dict.toponyms.form._create_edit', 
                ['submit_title' => trans('messages.create'),
                 'action' => 'create',
                 'toponym' => NULL,
                 'topnames' => [],
                 'wrongnames' => []])
    {!! Form::close() !!}
@endsection
