<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.toponyms')}}
    </x-slot>

    <h3>{{ $toponym->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('toponyms.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_dict_edit())
            | @include('widgets.form.button._edit', ['route' => route('toponyms.edit', $toponym)])
            | @include('widgets.form.button._delete', ['route' => 'toponyms.destroy', 'obj' => $toponym, 'args'=>['id' => $toponym->id]])             
            | <a href="{{ route('toponyms.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <p><b>{{trans('toponym.location')}}</b>: 
    {{ $toponym->location }}</p>

    <p><b>{{trans('toponym.location_1926')}}</b>: 
    {{ $toponym->location1926 }}</p>

    <p><b>{{trans('aux.geotype')}}</b>: 
    {{ optional($toponym->geotype)->name }}</p>

    <p><b>{{trans('toponym.caseform')}}</b>: 
    {{ $toponym->caseform }}</p>

    <p><b>{{trans('aux.etymology_nation')}}</b>: 
    {{ optional($toponym->etymologyNation)->name }}</p>

    <p><b>{{trans('aux.ethnos_territory')}}</b>: 
    {{ optional($toponym->ethnosTerritory)->name }}</p>

    <p><b>{{trans('toponym.etymology')}}</b>: 
    {{ $toponym->etymology }}</p>

    <hr>{{-- Structure of toponym word --}}
    <p><b>{{trans('aux.struct')}}</b></p>
        <ol>
        @foreach ($toponym->structs as $struct)
        <li>
            {{ optional($struct)->name }} ({{ optional($struct->structhier)->name }})
        </li>
        @endforeach 
        </ol>
                    
                
                
</x-app-layout>
