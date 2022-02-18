<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{trans('general.Settlements_in_1926_year')}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="table table-striped table-hover"><tr><th>&numero;</th>
                           <th>{{trans('general.region')}}</th>
                           <th>{{trans('general.districts_1926')}}</th>
                           <th>{{trans('general.selsovets_1926')}}</th>
                           <th>{{trans('general.in_english')}}</th>
                           <th>{{trans('general.in_russian')}}</th>
                           <th>{{trans('general.in_karelian')}}</th>
                        </tr>
                    
                        @foreach( $settlements1926 as $r )
                        <tr>
                            <td>{{ $loop->iteration }}{{-- Starts with 1 --}}</td>
                            <td>{{ $r->selsovet1926->district1926->region->name}}</td>
                            <td>{{ $r->selsovet1926->district1926->name }}</td>
                            <td>{{ $r->selsovet1926->name }}</td>
                            
                            <td>{{ $r->name_en }}</td>
                            <td>{{ $r->name_ru }}</td>
                            <td>{{ $r->name_krl }}</td>
                        </tr>
                        @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
