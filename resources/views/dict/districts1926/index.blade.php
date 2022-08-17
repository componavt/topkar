<x-app-layout>
    <x-slot name="header">
            {{trans('toponym.Districts_in_1926_year')}}
    </x-slot>

    <x-slot name="table_block">
        <table class="table table-striped table-hover">
            <tr>
                <th>&numero;</th>
                <th>{{trans('toponym.region')}}</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('general.in_english')}}</th>
                <th style="text-align: right">{{trans('navigation.toponyms')}}</th>
            </tr>

            @foreach( $districts1926 as $r )
            <tr>
                <td>{{ $loop->iteration }}{{-- Starts with 1 --}}</td>
                <td>{{ $r->region->name }}</td>
                <td>{{ $r->name_ru }}</td>
                <td>{{ $r->name_en }}</td>
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: right">
                    @if ($r->toponyms()->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_districts1926[]={{$r->id}}">
                        {{ number_format($r->toponyms()->count(), 0, ',', ' ') }}
                    </a>
                    @else
                    0
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </x-slot>
</x-app-layout>
