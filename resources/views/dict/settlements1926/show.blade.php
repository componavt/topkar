@extends('layouts.page')

@section('headTitle', $settlement->name)
@section('header', trans('navigation.settlements1926'))

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
    <link rel="stylesheet" href="/css/map.css"/>
@stop

@section('page_top')   
    <h2>{{ $settlement->name }}</h2>
    <p>
    @if ($settlement->wdURL() || user_can_edit())
    <b>{{trans('toponym.wd_URL')}}: <span class='field-value'>{!! $settlement->wdURL() !!}</span></b>
    <span style="padding: 0 10px">|</span> 
    @endif
        <span class="important">TopKar ID: {{ $settlement->id }}</span>
    </p>
@endsection            

@section('top_links')   
    {!! to_list('settlements1926', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('settlements1926', $settlement, $args_by_get) !!}
        {!! to_delete('settlements1926', $settlement, $args_by_get) !!}
        {!! to_create('settlements1926', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection            

@section('content')   
    @if ($settlement->hasCoords())
    <div class="row">
        <div class="col-sm-6">
            <div id="mapid" style="width: 100%; height: 500px;"></div>
        </div>
        <div class="col-sm-6">
    @endif
    
    @if (optional($settlement->selsovet1926->district1926->region)->name || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.region')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926->district1926->region)->name }}</span></p>
    @endif

    @if (optional($settlement->selsovet1926->district1926)->name || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.district1926')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926->district1926)->name }}</span></p>
    @endif

    @if (optional($settlement->selsovet1926)->name || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.selsovet1926')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926)->name }}</span></p>
    @endif

    @if (optional($settlement)->name_ru || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_ru }}</span></p>
    @endif

    @if (optional($settlement)->name_en || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_en }}</span></p>
    @endif

    @if (optional($settlement)->name_krl || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_karelian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_krl }}</span></p>
    @endif

    @if ($settlement->toponyms->count())
    <p><span class='field-name'>{{ __('toponym.in_settlement') }} </span>
        <a href="{{route('toponyms.index')}}?search_settlements1926[]={{$settlement->id}}">
            {{ $settlement->toponyms->count() }}
            {{ trans_with_choice('toponym._count', count_for_choice($settlement->toponyms->count())) }}
        </a>
    </p>               
    @endif
    
    @if (user_can_edit() && sizeof($settlement->same_settlements1926))
    <p><span class='field-name'>{{ __('toponym.same_settlement1926') }}</span>:
        @foreach($settlement->same_settlements1926 as $s)
        <a href="{{ route('settlements1926.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->same_settlements1926));@endif
        @endforeach
    @endif
    
    @if (user_can_edit() && sizeof($settlement->same_settlements))
    <p><span class='field-name'>{{ __('toponym.same_modern_settlement') }}</span>:
        @foreach($settlement->same_settlements as $s)
        <a href="{{ route('settlements.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->same_settlements));@endif
        @endforeach
    @endif
    
    @if (user_can_edit() && sizeof($settlement->possibly_same_settlements))
    <p><span class='field-name'>{{ __('toponym.possibly_same_modern_settlement') }}</span>:
        @foreach($settlement->possibly_same_settlements as $s)
        <a href="{{ route('settlements.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->possibly_same_settlements));@endif
        @endforeach
    @endif
    
    @if ($settlement->hasCoords())
        </div>
    </div>    
    @endif
@endsection
    
@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
        @include('widgets.leaflet.obj_on_map', ['obj'=>$settlement])
@endsection

@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop

