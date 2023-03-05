<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.toponyms')}}
    </x-slot>

    <h3>{{ $toponym->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('toponyms.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('toponyms.edit', $toponym)])
            | @include('widgets.form.button._delete', ['route' => 'toponyms.destroy', 'args'=>['toponym' => $toponym->id]])             
            | <a href="{{ route('toponyms.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <p><span class='field-name'>{{trans('toponym.topnames')}}</span>: 
    <span class='field-value'>{{ join(', ', $toponym->topnames()->pluck('name')->toArray()) }}</span></p>

    <p><span class='field-name'>{{trans('toponym.location')}}</span>: 
    <span class='field-value'>{{ $toponym->location }}</span></p>

    <p><span class='field-name'>{{trans('toponym.location_1926')}}</span>: 
    <span class='field-value'>{{ $toponym->location1926 }}</span></p>

    <p><span class='field-name'>{{trans('misc.geotype')}}</span>: 
    <span class='field-value'>{{ optional($toponym->geotype)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.caseform')}}</span>: 
    <span class='field-value'>{{ $toponym->caseform }}</span></p>

    <p><span class='field-name'>{{trans('misc.ethnos_territory')}}</span>: 
    <span class='field-value'>{{ optional($toponym->ethnosTerritory)->name }}</span></p>

    <p><span class='field-name'>{{trans('misc.etymology_nation')}}</span>: 
    <span class='field-value'>{{ optional($toponym->etymologyNation)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.etymology')}}</span>: 
    <span class='field-value'>{{ $toponym->etymology }}</span></p>

    <p><span class='field-name'>{{trans('toponym.main_info')}}</span>: 
    <span class='field-value'>{{ $toponym->main_info }}</span></p>

    <p><span class='field-name'>{{trans('toponym.legend')}}</span>: 
    <span class='field-value'>{{ $toponym->legend }}</span></p>

    <p><span class='field-name'>{{trans('toponym.wd_URL')}}</span>: 
    <span class='field-value'>{!! $toponym->wdURL() !!}</span></p>
    
    <p>
        <span class='field-name'>{{trans('toponym.sources')}}</span>:
        @foreach ($toponym->sources as $source)
        <br>{{ $source->sequence_number }}. <span class='field-value'><i>{{ $source->mention }}</i> 
            {!! preg_replace("/\n/", '<br>', $source->source) !!}</span> 
        @endforeach
    </p>

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
    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/rec-delete-link.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
    </x-slot>                                                        
</x-app-layout>
