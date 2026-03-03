@extends('layouts.page')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
@endsection

@section('headTitle', 'Создать улицу')
@section('header', 'Улицы Петрозаводска')

@section('page_top')
    <h2>Создать улицу</h2>
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
