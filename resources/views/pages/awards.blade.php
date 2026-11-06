@extends('layouts.master')

@section('headTitle', trans('navigation.awards'))
@section('header', trans('navigation.awards'))

@section('headExtra')
{!! css('fancybox') !!}
@stop

@section('main')
<div style="overflow: hidden;">
    <div style="float: left; margin-right: 20px; margin-bottom: 10px; max-width: 200px;">
        <a data-fancybox="gallery" href="/images/heritage_big.jpg">
            <img class="img-fluid img-responsive img-rounded" src="/images/heritage.jpg" alt="Heritage Award" style="width: 100%; height: auto;">
        </a>
    </div>
    <div style="overflow: auto;">
        {!!trans('page.award_heritage')!!}
    </div>
</div>
<div style="clear: both;"></div>

<div>
    {!! __('page.award_nation') !!}
    <div style="display:flex; flex-wrap:wrap; justify-content: center">
        <div>
            <a data-fancybox="gallery" href="/images/pride_nation_z_big.png">
                <img class="img-fluid img-responsive img-rounded" src="/images/pride_nation_z.png" alt="" style="width: 100%; height: auto;">
            </a>
        </div>
        <div>
            <a data-fancybox="gallery" href="/images/pride_nation_m_big.png">
                <img class="img-fluid img-responsive img-rounded" src="/images/pride_nation_m.png" alt="" style="width: 100%; height: auto;">
            </a>
        </div>
        <div>
            <a data-fancybox="gallery" href="/images/pride_nation_k_big.png">
                <img class="img-fluid img-responsive img-rounded" src="/images/pride_nation_k.png" alt="" style="width: 100%; height: auto;">
            </a>
        </div>
    </div>

</div>
@stop

@section('footScriptExtra')
{!! js('fancybox.umd') !!}
@stop