<?php $locale = LaravelLocalization::getCurrentLocale(); 
    $participants = [
        'zakharova' => [820, 'illhportal'],
        'mullonen' => [53, 'illhportal'],
        'kongoeva' => null,
        'khorokhorin' => [1379, 'illhportal'],
        'nataly' => [22, 'mathem'],
        'andrew' => [804, 'mathem'],
        'shibanova' => [99, 'illhportal'],
    ];
?>

@extends('layouts.page')

@section('page_title')
{{ trans('navigation.participants') }}
@endsection

@section('headExtra')
    {!!Html::style('css/fancybox.css')!!}
@stop

@section('body')
<div class="row">
    @foreach ($participants as $n => $i)
    <div class="col-md-4" style="height:570px; text-align: center">
        <a data-fancybox="gallery" href="/images/participants/big/{{$n}}.jpg" data-caption="{{trans('participant.'.$n.'_name')}}, {{trans('participant.'.$n.'_info')}}">
            <img class="img-fluid img-responsive img-rounded" src="/images/participants/{{$n}}.jpg" alt=""><br>
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

@endsection

@section('footScriptExtra')
    {!!Html::script('js/fancybox.umd.js')!!}
@stop

@section('jqueryFunc')
@stop

