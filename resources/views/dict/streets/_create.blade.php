@section('headTitle', 'Создать урбононим')


@section('page_top')
    <h2>Создать урбононим</h2>
@endsection

@section('top_links')
    {!! to_list('street', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('street', $args_by_get, trans('messages.create_new_f')) !!}
    @endif
@endsection

@section('content')
    {!! Form::open(['method'=>'POST', 'route' => ['streets.store']]) !!}
    @include('dict.streets._form_create_edit', [
        'street'=>null,
        'action'=>'creation'])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@endsection
