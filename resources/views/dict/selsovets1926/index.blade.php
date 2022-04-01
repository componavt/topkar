<x-app-layout>
    <x-slot name="header">
            {{trans('general.Selsovets_in_1926_year')}}
    </x-slot>

                <table class="table table-striped table-hover"><tr><th>&numero;</th>
                           <th>{{trans('general.region')}}</th>
                           <th>{{trans('general.districts_1926')}}</th>
                           <th>{{trans('general.in_english')}}</th>
                           <th>{{trans('general.in_russian')}}</th>
                           <th>{{trans('general.in_karelian')}}</th>
                        </tr>
                    
                        @foreach( $selsovets1926 as $r )
                        <tr>
                            <td>{{ $loop->iteration }}{{-- Starts with 1 --}}</td>
                            <td>{{ $r->district1926->region->name }}</td>
                            <td>{{ $r->district1926->name }}</td>
                            
                            <td>{{ $r->name_en }}</td>
                            <td>{{ $r->name_ru }}</td>
                            <td>{{ $r->name_krl }}</td>
                        </tr>
                        @endforeach
                </table>
</x-app-layout>
