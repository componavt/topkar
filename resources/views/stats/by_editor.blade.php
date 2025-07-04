@extends('layouts.master')

@section('headTitle', trans('stats.stats_by_editors'))
@section('header', trans('stats.stats_by_editors'))

@section('headExtra')
        {!! css('stats') !!}  
@endsection

@section('main')   
    по редактору: <b>{{ $user->full_name }}</b> 
    
    {!! Form::open(array('method'=>'GET', 'route' => ['stats.by_editor', $user])) !!}
    <div style="display: flex; padding: 10px 0 0 20px; align-items: baseline; background-color: #d1dff554;">
        <span style="margin-right: 10px">с</span>
        @include('widgets.form.formitem._DATE', 
            ['name' => 'min_date', 
             'value' => old('min_date') ? old('min_date') : ($min_date ? $min_date : date('Y-m-d')),
             'placeholder' => 'dd.mm.yyyy'])
        <span style="margin: 0 10px">по</span>
        @include('widgets.form.formitem._DATE', 
            ['name' => 'max_date', 
             'value' => old('max_date') ? old('max_date') : ($max_date ? $max_date : date('Y-m-d')),
             'placeholder' => 'dd.mm.yyyy'])
        <span style="margin: 0 10px"></span>
        @include('widgets.form.formitem._submit', ['title' => trans('messages.view')])
    </div>
    {!! Form::close() !!}
    <p><a href="{{ route('stats.by_editor', $user).$quarter_query }}">В текущем квартале</a></p>
    <p><a href="{{ route('stats.by_editor', $user).$year_query }}">В текущем году</a></p>
    
    <h3>Создано</h3>
    @foreach ($models as $model => $title)
        @if(!empty($history_created[$model]))
    <p>{{ $title }}: <b>{{ format_number($history_created[$model]->count) }}</b></p>
        @endif
    @endforeach
    
    
    <h3>Изменено</h3>
    @foreach ($history_updated as $title => $count)
        @if (!empty($count))
    <p>{{ $title }}: <b>{{ format_number($count) }}</b></p>
        @endif
    @endforeach
    
    <p><a href="{{ route('stats.by_editors') }}">К списку редакторов</a></p>
@stop
