<x-app-layout>
    <x-slot name="header">
            {{trans('toponym.regions')}}
    </x-slot>

    <table class="table table-striped table-hover">
        <tr>
            <th>&numero;</th>
            <th>{{trans('general.in_russian')}}</th>
            <th>{{trans('general.in_english')}}</th>
            <th style="text-align: right">{{trans('navigation.toponyms')}}</th>
        </tr>

        @foreach( $regions as $r )
        <tr>
            <td>{{ $loop->iteration }}{{-- Starts with 1 --}}</td>
            <td>{{$r->name_ru}}</td>
            <td>{{$r->name_en}}</td>
            <td data-th="{{trans('navigation.toponyms')}}" style="text-align: right">
                @if ($r->toponyms()->count() > 0)
                <a href="{{route('toponyms.index')}}?search_regions[]={{$r->id}}">
                    {{ number_format($r->toponyms()->count(), 0, ',', ' ') }}
                </a>
                @else
                0
                @endif
            </td>
        </tr>
        @endforeach
 </table>
</x-app-layout>
