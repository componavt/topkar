@extends('layouts.master')

@section('headTitle', $toponym->name)

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
@stop

@section('header', trans('navigation.toponyms'))

@section('main')   
    <h3>
        {{ $toponym->name }} 
        @if ($toponym->lang)
        ({{$toponym->lang->short}})
        @endif
    </h3>

    <div class='top-links'>        
        <a href="{{ route('toponyms.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('toponyms.edit', $toponym)])
            | @include('widgets.form.button._delete', ['route' => 'toponyms.destroy', 'args'=>['toponym' => $toponym->id]])             
            | <a href="{{ route('toponyms.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
            | <a href="{{ route('toponyms.create') }}{{$toponym->argsForAnotherOne($args_by_get)}}">{{ mb_strtolower(trans('toponym.create_new_m_in_this_settl')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>

    @if ($toponym->objOnMap())
    <div class="row">
        <div class="col-sm-6">
    @endif
            <p><span class='field-name'>{{trans('toponym.topnames')}}:</span> 
            <span class='field-value'>{!! join(', ', $toponym->topnamesWithLangs()) !!}</span></p>

            <p><span class='field-name'>{{trans('toponym.wrongnames')}}:</span> 
            <span class='field-value'>{{ join(', ', $toponym->wrongnamesWithLangs()) }}</span></p>

            <p><span class='field-name'>{{trans('misc.geotype')}}:</span> 
            <span class='field-value'>{{ optional($toponym->geotype)->name }}</span></p>

            <p><span class='field-name'>{{trans('toponym.location')}}:</span> 
            <span class='field-value'>{{ $toponym->location }}</span></p>

            <p><span class='field-name'>{{trans('toponym.location_1926')}}:</span> 
            <span class='field-value'>{{ $toponym->location1926 }}</span></p>

            <p><span class='field-name'>{{trans('toponym.caseform')}}:</span> 
            <span class='field-value'>{{ $toponym->caseform }}</span></p>

            <p><span class='field-name'>{{trans('misc.ethnos_territory')}}:</span> 
            <span class='field-value'>{{ optional($toponym->ethnosTerritory)->name }}</span></p>

            <p><span class='field-name'>{{trans('misc.etymology_nation')}}:</span> 
            <span class='field-value'>{{ optional($toponym->etymologyNation)->name }}</span></p>

            <p><span class='field-name'>{{trans('toponym.etymology')}}:</span> 
            <span class='field-value'>{{ $toponym->etymology }}</span></p>

            @if (preg_match("/\n/", $toponym->main_info))
            <div style="display: flex; margin-bottom: 10px"><span class='field-name'>{{trans('toponym.main_info')}}:</span> 
                <span class='field-value'>{!! preg_replace("/\n/", "<br>\n",$toponym->main_info) !!}</span></div>
            @else
            <p><span class='field-name'>{{trans('toponym.main_info')}}:</span> 
                <span class='field-value'>{{ $toponym->main_info }}</span></p>
            @endif
            
            <p><span class='field-name'>{{trans('toponym.legend')}}:</span> 
            <span class='field-value'>{{ $toponym->legend }}</span></p>

            <p><span class='field-name'>{{trans('toponym.legend_collect')}}:</span> 
            <span class='field-value'>{!! $toponym->textURLs() !!}</span></p>

            <p><span class='field-name'>{{trans('toponym.wd_URL')}}:</span> 
            <span class='field-value'>{!! $toponym->wdURL() !!}</span>; 
            TopKar ID: {{ $toponym->id }}
            </p>

            <p>
                <span class='field-name'>{{trans('toponym.sources')}}:</span>
                @foreach ($toponym->sourceToponyms as $st)
                <br>{{ $st->sequence_number }}. 
                <span class='field-value'>
                    @if ($st->sourceToString())
                    {{$st->sourceToString()}}@if ($st->mention): @endif
                    @endif
                    <i>{{ $st->mention }}</i> 
                </span> 
                @endforeach
            </p>
    @if ($toponym->objOnMap())
        </div>
        <div class="col-sm-6">
            <div id="mapid" style="width: 100%; height: 500px;"></div>
        </div>
    </div>    
    @endif

    <?php $count=1;?>
    <p><span class='field-name'>{{trans('misc.events')}}:</span></p>
    <table class="table table-bordered">
        <tr>
            <td>No</td>
            <td>{{trans('misc.record_place')}}</td>
            <td>{{mb_ucfirst(trans('messages.year'))}}</td>
            <td>{{trans('navigation.informants')}}</td>            
            <td>{{trans('navigation.recorders')}}</td>     
        </tr>
        @foreach ($toponym->events as $event)
        <tr>
            <td class='field-value'>{{ $count++ }}</td>
            <td class='field-value'>{{$event->settlementsToString()}}</td>
            <td class='field-value'>{{$event->date}}</td>
            <td class='field-value'>{{$event->informantsToString()}}</td>
            <td class='field-value'>{{$event->recordersToString()}}</td>
        @endforeach
        </table>

    <!--hr-->{{-- Structure of toponym word --}}
    <p><span class='field-name'>{{trans('misc.struct')}}</span></p>
    <ol>
    @foreach ($toponym->structs as $struct)
    <li>
        <span class='field-value'>{{ optional($struct)->name }}</span> 
        ({{ $struct && $struct->structhier ? $struct->structhier->parent->name. ' '. mb_strtolower($struct->structhier->name) : '' }})
    </li>
    @endforeach 
    </ol>
    
    @php 
        $others = $toponym->anothersInSettlement($toponym->geotype_id); 
    @endphp
    @if (sizeof($others))
    <h3>Другие топонимы в этом же поселении</h3>
    <ol>
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
