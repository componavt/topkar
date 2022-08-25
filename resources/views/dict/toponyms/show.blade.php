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

    <hr>{{-- Structure of toponym word --}}
    <p><span class='field-name'>{{trans('misc.struct')}}</span></p>
    <ol>
    @foreach ($toponym->structs as $struct)
    <li>
        <span class='field-value'>{{ optional($struct)->name }}</span> 
        ({{ optional($struct->structhier->parent)->name }} {{ mb_strtolower(optional($struct->structhier)->name) }})
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
