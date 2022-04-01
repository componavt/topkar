<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.toponyms')}}
    </x-slot>

                <h2>{{ $toponym->name }}</h2>
                
                <p><b>{{trans('toponym.location')}}</b>: 
                {{ $toponym->location }}</p>
                
                <p><b>{{trans('toponym.location_1926')}}</b>: 
                {{ $toponym->location1926 }}</p>
                
                <p><b>{{trans('toponym.geotype')}}</b>: 
                {{ optional($toponym->geotype)->name }}</p>
</x-app-layout>
