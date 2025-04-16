@extends('layouts.page')

@section('headTitle', $toponym->name)
@section('header', trans('navigation.toponyms'))

@section('headExtra')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
         integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
         crossorigin=""/>
    <link rel="stylesheet" href="/css/map.css"/>
@stop

@section('page_top')   
    <h2>
        {{ $toponym->name }} 
        @if ($toponym->lang)
        ({{$toponym->lang->short}})
        @endif
    </h2>
    <p>
    @if ($toponym->wdURL() || user_can_edit())
    <b>{{trans('toponym.wd_URL')}}: <span class='field-value'>{!! $toponym->wdURL() !!}</span></b>
    <span style="padding: 0 10px">|</span> 
    @endif
        <span class="important">TopKar ID: {{ $toponym->id }}</span>
    </p>
@endsection            

@section('top_links')   
    @if ($toponym->fromNLadoga())
        {!! to_link(trans('navigation.nladoga'), route('toponyms.nladoga'), $args_by_get, 'top-icon to-list') !!}
    @endif
    {!! to_list('toponym', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('toponym', $toponym, $args_by_get) !!}
        {!! to_delete('toponym', $toponym, $args_by_get) !!}
        {!! to_create('toponym', $args_by_get, trans('messages.create_new_m')) !!}
        {!! to_create('toponym', $toponym->argsForAnotherOne($args_by_get), trans('toponym.in_this_settl')) !!}
    @endif             
@endsection            

@section('content')   
    @if ($toponym->objOnMap())
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-sm-6" style="padding-bottom: 20px">
            <div id="mapid" style="width: 100%; height: 500px; margin-top: 5px"></div>
        </div>
        <div class="col-sm-6">
    @endif
    @if (sizeof($toponym->topnamesWithLangs()) || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.topnames')}}:</span> 
            <span class='field-value'>{!! join(', ', $toponym->topnamesWithLangs()) !!}</span></p>
    @endif
    @if (sizeof($toponym->wrongnamesWithLangs()) || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.wrongnames')}}:</span> 
            <span class='field-value'>{{ join(', ', $toponym->wrongnamesWithLangs()) }}</span></p>
        @endif
    @if (optional($toponym->geotype)->name || user_can_edit())
            <p><span class='field-name'>{{trans('misc.geotype')}}:</span> 
            <span class='field-value'>{{ optional($toponym->geotype)->name }}</span></p>
        @endif
    @if ($toponym->location || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.location')}}:</span> 
            <span class='field-value'>{{ $toponym->location }}</span></p>
    @endif
    @if ($toponym->location1926 || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.location_1926')}}:</span> 
            <span class='field-value'>{{ $toponym->location1926 }}</span></p>
    @endif
    @if ($toponym->caseform || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.caseform')}}:</span> 
            <span class='field-value'>{{ $toponym->caseform }}</span></p>
    @endif
    @if (optional($toponym->ethnosTerritory)->name || user_can_edit())
            <p><span class='field-name'>{{trans('misc.ethnos_territory')}}:</span> 
            <span class='field-value'>{{ optional($toponym->ethnosTerritory)->name }}</span></p>
    @endif
    @if (optional($toponym->etymologyNation)->name || user_can_edit())
            <p><span class='field-name'>{{trans('misc.etymology_nation')}}:</span> 
            <span class='field-value'>{{ optional($toponym->etymologyNation)->name }}</span></p>
    @endif
    @if ($toponym->etymology || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.etymology')}}:</span> 
            <span class='field-value'>{{ $toponym->etymology }}</span></p>
    @endif
    @if ($toponym->main_info || user_can_edit())
            @if (preg_match("/\n/", $toponym->main_info))
            <div style="display: flex; margin-bottom: 10px"><span class='field-name'>{{trans('toponym.main_info')}}:</span> 
                <span class='field-value'>{!! preg_replace("/\n/", "<br>\n",$toponym->main_info) !!}</span></div>
            @else
            <p><span class='field-name'>{{trans('toponym.main_info')}}:</span> 
                <span class='field-value'>{{ $toponym->main_info }}</span></p>
            @endif
    @endif
    @if ($toponym->legend || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.legend')}}:</span> 
            <span class='field-value'>{{ $toponym->legend }}</span></p>
    @endif
    @if ($toponym->textURLs() || user_can_edit())
            <p><span class='field-name'>{{trans('toponym.legend_collect')}}:</span> 
            <span class='field-value'>{!! $toponym->textURLs() !!}</span></p>
    @endif
    @if (sizeof($toponym->sourceToponyms) || user_can_edit())
        <p style="margin-bottom: 0"><span class='field-name'>{{trans('toponym.sources')}}:</span></p>
        <ol style="padding-left: 20px">
        @foreach ($toponym->sourceToponyms as $st)
            <li class='field-value'>
                @if ($st->sourceToString())
                <i>{{ $st->mention }}</i> 
                @if ($st->mention) // @endif
                {{$st->sourceToString()}}
                @endif
            </li> 
        @endforeach
        </ol>
    @endif
    @if (sizeof($toponym->structs) || user_can_edit())
        {{-- Structure of toponym word --}}
        <p><span class='field-name'>{{trans('misc.struct')}}</span></p>
        <ol>
        @foreach ($toponym->structs as $struct)
        <li>
            <span class='field-value'>{{ optional($struct)->name }}</span> 
            ({{ $struct && $struct->structhier ? $struct->structhier->parent->name. ' '. mb_strtolower($struct->structhier->name) : '' }})
        </li>
        @endforeach 
        </ol>
    @endif
    
    @if ($toponym->objOnMap())
        </div>
    </div>    
    @endif

    @if (sizeof($toponym->events) || user_can_edit())
    <?php $count=1;?>
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>{{trans('misc.record_place')}}</th>
            <th>{{mb_ucfirst(trans('messages.year'))}}</th>
            <th>{{trans('navigation.informants')}}</th>            
            <th>{{trans('navigation.recorders')}}</th>     
        </tr>
        @foreach ($toponym->events as $event)
        <tr>
            <td>{{ $count++ }}</td>
            <td>
                {{ $event->settlementsToString() }}@if ($event->settlementsToString() && $event->settlements1926ToString()), @endif
                {{ $event->settlements1926ToString() }}
            </td>
            <td>{{$event->date}}</td>
            <td>{{$event->informantsToString()}}</td>
            <td>{{$event->recordersToString()}}</td>
        @endforeach
        </table>
    @endif

    @php 
        $others = $toponym->anothersInSettlement($toponym->geotype_id); 
    @endphp
    @if (sizeof($others))
    <h3>Другие топонимы в этом же поселении</h3>
    <ol class="other-toponyms">
        @if (sizeof($others)>10)
        <div class='row'>
            <div class="col-sm-6">
        @endif
        @for ($i=0; $i<sizeof($others); $i++) 
        <li>{{$others[$i]->geotype_name}} <a href="{{route("toponyms.show", $others[$i]).$args_by_get}}">{{$others[$i]->name}}</a>
            @if ($others[$i]->topnames()->count())
            ({{join(', ', $others[$i]->topnames()->pluck('name')->toArray())}})
            @endif
        </li>
            @if (sizeof($others)>10 && $i==round(sizeof($others)/2)-1)
            </div>
            <div class="col-sm-6">
            @endif
        @endfor
        @if (sizeof($others)>10)
            </div>
        </div>
        @endif
    </ol>
    @endif
@stop

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
        @include('widgets.leaflet.obj_on_map', ['obj'=>$toponym->objOnMap(), 'color' => $toponym->objOnMap() === $toponym ? 'blue' : 'grey'])
@stop

@section('jqueryFunc')
        recDelete("{{ trans('messages.confirm_delete') }}");
@stop
