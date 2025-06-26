@extends('layouts.master')

@section('headTitle', trans('stats.stats_by_editors'))
@section('header', trans('stats.stats_by_editors'))

@section('headExtra')
        {!! css('stats') !!}  
@endsection

@section('table_block')   
    <table class="table table-striped table-hover wide-md stats-table">
        <tr>
            <th>Редактор</th>
            <th>Количество правок</th>
            <th style="text-align: right">Последняя редакция</th>
        </tr>
        @foreach ($editors as $editor)
        <tr>
            <td><a href="{{ route('stats.by_editor', $editor) }}">{{ $editor->name }}</a></td>
            <td>{{ $editor->count }}</td>
            <td>{{ $editor->last_time }}</td>
        </tr>
        @endforeach
    </table>

<p><a href="{{ route('stats') }}">Вернуться к общей статистике</a>
@stop
