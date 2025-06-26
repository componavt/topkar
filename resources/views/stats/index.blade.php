@extends('layouts.master')

@section('headTitle', trans('navigation.stats'))
@section('header', trans('navigation.stats'))

@section('headExtra')
        {!! css('stats') !!}  
@endsection

@section('table_block')
{{--    <p><a href="{{LaravelLocalization::localizeURL('/stats/by_dict')}}">{{trans('stats.stats_by_dict')}}</a></p>
    <p><a href="{{LaravelLocalization::localizeURL('/stats/by_corp')}}">{{trans('stats.stats_by_corp')}}</a></p>
    <p><a href="{{LaravelLocalization::localizeURL('/stats/by_user')}}">{{trans('stats.by_user')}}</a></p> --}}
    <table class="table table-striped table-hover wide-md stats-table">
        <tr>
            <td>{{trans('stats.total_toponyms')}}</td>
            <td><a href="{{ LaravelLocalization::localizeURL('/dict/toponyms') }}">{{number_format($total_toponyms, 0, ',', ' ')}}</a></td>
        </tr>
        <tr>
            <td>{{trans('stats.total_toponyms_with_coords')}}</td>
            <td><a href="{{ LaravelLocalization::localizeURL('/dict/toponyms/with_coords') }}">{{number_format($total_toponyms_with_coords, 0, ',', ' ')}}</a></td>
        </tr>
        @foreach ($total_langs as $name => $info)
        <tr>
            <td>{{ $name }}</td>
            <td><a href="{{ LaravelLocalization::localizeURL('/dict/toponyms?search_lang='.$info['id']) 
                   }}">{{number_format($info['count'], 0, ',', ' ')}}</a></td>
        </tr>
        @endforeach
        <tr>
            <td>{{trans('stats.total_toponyms_with_etimology')}}</td>
            <td>{{number_format($total_toponyms_with_etymology, 0, ',', ' ')}}</td>
        </tr>
        <tr>
            <td>{{trans('stats.total_settl_with_toponyms')}}</td>
            <td>{{number_format($total_settlements_with_toponyms, 0, ',', ' ')}}</td>
        </tr>
        <tr>
            <td>{{ trans('stats.total_toponyms_with_sources') }}</td>
            <td>{{number_format($total_toponyms_with_sources, 0, ',', ' ')}}</td>
        </tr>
        <tr>
            <td>{{ trans('stats.total_wd') }}</td>
            <td><a href="{{ LaravelLocalization::localizeURL('/dict/toponyms/with_wd') 
                   }}">{{number_format($total_wd, 0, ',', ' ')}}</a></td>
        </tr>
        <tr>
            <td>{{ trans('stats.total_toponyms_with_legends') }}</td>
            <td><a href="{{ LaravelLocalization::localizeURL('/dict/toponyms/with_legends') 
                   }}">{{number_format($total_toponyms_with_legends, 0, ',', ' ')}}</a></td>
        </tr>
    </table>
    
    @if (user_can_edit())
    <p style='margin-top: 20px'><a href="{{ route('stats.by_editors') }}">{{ trans('stats.stats_by_editors') }}</a></p>
    @endif

@stop
