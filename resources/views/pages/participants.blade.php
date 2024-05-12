<?php $locale = LaravelLocalization::getCurrentLocale(); 
    $participants = [
        [
        'zakharova' => [820, 'illhportal'],
        'mullonen' => [53, 'illhportal'],
        'kongoeva' => null,
        ],
        [
        'andrew' => [804, 'mathem'],
        'shibanova' => [99, 'illhportal'],
        'khorokhorin' => [1379, 'illhportal'],
        ],
        ['nataly' => [22, 'mathem']]
    ];
?>

@extends('layouts.master')

@section('headTitle', trans('navigation.participants'))
@section('header', trans('navigation.participants'))

@section('headExtra')
    {!! css('fancybox') !!}
@stop

@section('main')
    @foreach ($participants as $chunk)
    <div class="row">
        @foreach ($chunk as $n => $i)
        <div class="col-md-4" style="text-align: center">
            <a data-fancybox="gallery" href="/photos/big/{{$n}}.jpg" data-caption="{{trans('participant.'.$n.'_name')}}, {{trans('participant.'.$n.'_info')}}">
                <img class="img-fluid img-responsive img-rounded" src="/photos/{{$n}}.jpg" alt=""><br>
            </a>
            @if (isset($i[0]))
            <a href="http://{{$i[1]}}.krc.karelia.ru/member.php?id={{$i[0]}}&plang={{$locale=='en' ? 'e' : 'r'}}">
            @endif
            <b>{{trans('participant.'.$n.'_name')}}</b>
            @if (isset($i[0]))
            </a>
            @endif
            <br>{{trans('participant.'.$n.'_info')}}<br><br>
        </div>
        @endforeach
    </div>
    @endforeach
@endsection

@section('footScriptExtra')
    {!! js('fancybox.umd') !!}
@stop

