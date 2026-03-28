@section('page_top')
    <h2>{{ $street->name }}</h2>
    <p><span class="important">TopKar ID: {{ $street->id }}</span></p>
@endsection

@section('top_links')
    {!! to_list('street', $args_by_get) !!}
    @if (user_can_edit())
        {!! back_to_show('street', $street, $args_by_get) !!}
        {!! to_delete('street', $street, $args_by_get) !!}
        {!! to_create('street', $args_by_get, trans('messages.create_new_f')) !!}
    @endif
@endsection

@section('content')
    {!! Form::model($street, ['method'=>'PUT', 'route' => ['streets.update', $street]]) !!}
    @include('dict.streets._form_create_edit', ['street'=>$street, 'action'=>'edit'])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}

    <div id="map" style="height: 500px;"></div>
    <script src="{{ asset('js/map.js') }}"></script>
@endsection
