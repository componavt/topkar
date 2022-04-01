<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{trans('navigation.toponyms')}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    
                <h2>{{ $toponym->name }}</h2>
                
                <p><b>{{trans('toponym.location')}}</b>: 
                {{ $toponym->location }}</p>
                
                <p><b>{{trans('toponym.location_1926')}}</b>: 
                {{ $toponym->location1926 }}</p>
                
                <p><b>{{trans('toponym.geotype')}}</b>: 
                {{ optional($toponym->geotype)->name }}</p>
                
            </div>
        </div>
    </div>
</x-app-layout>
