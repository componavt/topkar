<x-app-layout>
    <x-slot name="header">
            {{trans('toponym.Settlements_in_1926_year')}}
    </x-slot>

                <table class="table table-striped table-hover"><tr><th>&numero;</th>
                           <th>{{trans('toponym.region')}}</th>
                           <th>{{trans('toponym.districts_1926')}}</th>
                           <th>{{trans('toponym.selsovets_1926')}}</th>
                           <th>{{trans('general.in_english')}}</th>
                           <th>{{trans('general.in_russian')}}</th>
                           <th>{{trans('general.in_karelian')}}</th>
                        </tr>
                    
                        @foreach( $settlements1926 as $r )
                        <tr>
                            <td>{{ $loop->iteration + $portion*($page - 1) }}{{-- Starts with 1 --}}</td>
                            <td>{{ $r->selsovet1926->district1926 ? $r->selsovet1926->district1926->region->name : "empty district1926"}}</td>
                            <td>{{ $r->selsovet1926->district1926->name }}</td>
                            <td>{{ $r->selsovet1926->name }}</td>
                            
                            <td>{{ $r->name_en }}</td>
                            <td>{{ $r->name_ru }}</td>
                            <td>{{ $r->name_krl }}</td>
                        </tr>
                        @endforeach
                </table>
                
                {{-- $settlements1926->links() --}}
                {{ $settlements1926->onEachSide(3)->links() }}
</x-app-layout>
