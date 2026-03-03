@extends('layouts.page')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
@endsection

@section('headTitle', $street->name)
@section('header', 'Улицы Петрозаводска')

@section('page_top')
    <h2>{{ $street->name }}</h2>
    <p><span class="important">TopKar ID: {{ $street->id }}</span></p>
@endsection

@section('top_links')
    {!! to_list('street', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_show('street', $street, $args_by_get) !!}
        {!! to_delete('street', $street, $args_by_get) !!}
        {!! to_create('street', $args_by_get, trans('messages.create_new_f')) !!}
    @endif
@endsection

@section('content')
    {!! Form::model($street, ['method'=>'PUT', 'route' => ['streets.update', $street]]) !!}
    @include('dict.streets._form_create_edit', ['street'=>$street, 'action'=>'edit'])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@endsection

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
@endsection

@section('jqueryFunc')
    $('.select-geotype').select2({
        allowClear: true,
        placeholder: '{{trans('misc.type')}}',
        width: '100%'
    });
@stop
