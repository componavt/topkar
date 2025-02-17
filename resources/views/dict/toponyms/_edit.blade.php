@section('headTitle', $toponym->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.toponyms'). ' / '. trans('messages.editing'). ' / '. $toponym->name)

@section('page_top')   
    <h2>{{ trans('messages.editing'). ' / '. $toponym->name }}</h2>
@endsection

@section('top_links')   
    {!! back_to_show('toponym', $toponym, $args_by_get) !!}
    @if ($toponym->fromNLadoga())
    {!! to_link(trans('navigation.nladoga'), route('toponyms.nladoga'), $args_by_get, 'top-icon to-list') !!}
    @endif
    {!! to_list('toponym', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('toponym', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($toponym, [
                    'method'=>'PUT', 
                    'route' => ['toponyms.update', $toponym->id], 
                    'id'=>'toponymForm'
    ]) !!}
    @include('dict.toponyms.form._create_edit', 
                ['submit_title' => trans('messages.save'),
                 'action' => 'edit',
                 'topnames' => $toponym->topnames()->count() ? $toponym->topnames : [],
                 'wrongnames' => $toponym->wrongnames()->count() ? $toponym->wrongnames : []])
        {!! Form::close() !!}
@endsection
