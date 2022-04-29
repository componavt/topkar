<x-app-layout>
    <x-slot name="header">
            {{trans('toponym.Districts_in_1926_year')}}
    </x-slot>

                <table class="table table-striped table-hover"><tr><th>&numero;</th>
                           <th>{{trans('toponym.region')}}</th>
                           <th>{{trans('general.in_english')}}</th>
                           <th>{{trans('general.in_russian')}}</th>
                        </tr>
                    
                        @foreach( $districts1926 as $r )
                        <tr>
                            <td>{{ $loop->iteration }}{{-- Starts with 1 --}}</td>
                            <td>{{ $r->region->name }}</td>
                            <td>{{ $r->name_en }}</td>
                            <td>{{ $r->name_ru }}</td>
                        </tr>
                        @endforeach
                </table>
</x-app-layout>
