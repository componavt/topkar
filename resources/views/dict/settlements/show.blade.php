@extends('layouts.master')

@section('header', trans('navigation.settlements'))

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
@stop

@section('main')   
    <h3>{{ $settlement->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('settlements.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('settlements.edit', $settlement)])
            | @include('widgets.form.button._delete', ['route' => 'settlements.destroy', 'args'=>['settlement' => $settlement->id]])             
            | <a href="{{ route('settlements.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_g')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    @if ($settlement->latitude && $settlement->longitude)
    <div class="row">
        <div class="col-sm-6">
    @endif
    <p><span class='field-name'>{{trans('toponym.region')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->regionsToString() }}</span></p>

    <p><span class='field-name'>{{trans('navigation.districts')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->districtsToString() }}</span></p>

    <p><span class='field-name'>{{trans('misc.type')}}</span>: 
    <span class='field-value'>{{ optional($settlement->geotype)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_ru }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_en }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_karelian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_krl }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_vepsian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_vep }}</span></p>
    
    <p><span class='field-name'>{{trans('toponym.wd_URL')}}:</span> 
    <span class='field-value'>{!! optional($settlement)->wdURL() !!}</span>; 
    TopKar ID: {{ optional($settlement)->id }}
    </p>
    
    @if ($settlement->toponyms->count())
    <p><span class='field-name'>{{ __('toponym.in_settlement') }} </span>
        <a href="{{route('toponyms.index')}}?search_settlements[]={{$settlement->id}}">
            {{ $settlement->toponyms->count() }}
            {{ trans_with_choice('toponym._count', count_for_choice($settlement->toponyms->count())) }}
        </a>
    </p> 
    @endif
    
    @if ($settlement->recordPlaces()->count())
    <p><span class='field-name'>{{ __('misc.record_place') }} </span>
        <a href="{{route('toponyms.index')}}?search_record_places[]={{$settlement->id}}">
            {{ $settlement->recordPlaces()->count() }}
            {{ trans_with_choice('toponym._gen', count_for_choice($settlement->recordPlaces()->count())) }}
        </a>
    </p>  
    @endif
    
    @if (user_can_edit() && sizeof($settlement->same_settlements))
    <p><span class='field-name'>{{ __('toponym.same_settlement') }}</span>:
        @foreach($settlement->same_settlements as $s)
        <a href="{{ route('settlements.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration < sizeof($settlement->same_settlements));@endif
        @endforeach
    @endif
    
    @if (user_can_edit() && sizeof($settlement->same_settlements1926))
    <p><span class='field-name'>{{ __('toponym.same_settlement1926') }}</span>:
        @foreach($settlement->same_settlements1926 as $s)
        <a href="{{ route('settlements1926.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->same_settlements1926));@endif
        @endforeach
    @endif
    
    @if (user_can_edit() && sizeof($settlement->possibly_same_settlements1926))
    <p><span class='field-name'>{{ __('toponym.possibly_same_settlement1926') }}</span>:
        @foreach($settlement->possibly_same_settlements1926 as $s)
        <a href="{{ route('settlements1926.show', $s) }}">{{ $s->name }}</a>@if ($loop->iteration<sizeof($settlement->possibly_same_settlements1926));@endif
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
