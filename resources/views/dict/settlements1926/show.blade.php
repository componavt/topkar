@extends('layouts.master')

@section('header', trans('navigation.settlements_1926'))

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
@stop

@section('main')   
    <h3>{{ $settlement->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('settlements1926.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('settlements1926.edit', $settlement)])
            | @include('widgets.form.button._delete', ['route' => 'settlements1926.destroy', 'args'=>['settlements1926' => $settlement->id]])             
            | <a href="{{ route('settlements1926.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_g')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    @if ($settlement->latitude && $settlement->longitude)
    <div class="row">
        <div class="col-sm-6">
    @endif
    
    <p><span class='field-name'>{{trans('toponym.region')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926->district1926->region)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.district1926')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926->district1926)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.selsovet1926')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_ru }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_en }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_karelian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_krl }}</span></p>

    @if ($settlement->toponyms->count())
    <p><span class='field-name'>{{ __('toponym.settlement') }} </span>
        <a href="{{route('toponyms.index')}}?search_settlements1926[]={{$settlement->id}}">
            {{ trans_with_choice('toponym.in_count', $settlement->toponyms->count()) }}
        </a>
    </p>               
    @endif
    
    @if (sizeof($settlement->same_settlements1926))
    <p><span class='field-name'>{{ __('toponym.same_settlement1926') }}</span>:
        @foreach($settlement->same_settlements1926 as $s)
        <a href="{{ route('settlements1926.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->same_settlements1926));@endif
        @endforeach
    @endif
    
    @if (sizeof($settlement->same_settlements))
    <p><span class='field-name'>{{ __('toponym.same_modern_settlement') }}</span>:
        @foreach($settlement->same_settlements as $s)
        <a href="{{ route('settlements.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->same_settlements));@endif
        @endforeach
    @endif
    
    @if (sizeof($settlement->possibly_same_settlements))
    <p><span class='field-name'>{{ __('toponym.possibly_same_modern_settlement') }}</span>:
        @foreach($settlement->possibly_same_settlements as $s)
        <a href="{{ route('settlements.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->possibly_same_settlements));@endif
        @endforeach
    @endif
    
    @if ($settlement->latitude && $settlement->longitude)
        </div>
        <div class="col-sm-6">
            <div id="mapid" style="width: 100%; height: 500px;"></div>
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

