<?php
$pres_time = trans('participant.pres_time');
$participants = [
    [
        'zakharova' => [820, 'illhportal', '2022', $pres_time],
        'mullonen' => [53, 'illhportal', '2024', $pres_time],
        'kongoeva' => [null, null, '2022', '2023']
    ],
    [
        'andrew' => [804, 'mathem', '2022', $pres_time],
        'shibanova' => [99, 'illhportal', '2023', '2024'],
        //        'khorokhorin' => [1379, 'illhportal'],
    ],
    [
        'nataly' => [22, 'mathem', '2022', $pres_time],
        'kundozerova' => [858, 'illhportal', '2022', '2023']
    ]
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
    <div class="col-sm-4" style="text-align: center">
        <a data-fancybox="gallery" href="/photos/big/{{$n}}.jpg" data-caption="{{trans('participant.'.$n.'_name')}}, {{trans('participant.'.$n.'_info')}}">
            <img class="img-fluid img-responsive img-rounded" src="/photos/{{$n}}.jpg" alt=""><br>
        </a>
        @if (!empty($i[0]))
        <a href="http://{{ $i[1] }}.krc.karelia.ru/member.php?id={{$i[0]}}&plang={{ app()->getLocale()=='en' ? 'e' : 'r'}}">
            @endif
            <b>{{ __('participant.'.$n.'_name') }}</b>
            @if (isset($i[0]))
        </a>
        @endif
        <br>{!! __('participant.'.$n.'_info') !!}
        <br><i>{{ __('participant.work_on_project') }}: {{ $i[2] }} — {!! $i[3] !!}</i><br><br>
    </div>
    @endforeach
</div>
@endforeach
@endsection

@section('footScriptExtra')
{!! js('fancybox.umd') !!}
@stop