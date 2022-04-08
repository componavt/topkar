<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.toponyms')}}
    </x-slot>

                <h2>{{ $toponym->name }}</h2>
                
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
