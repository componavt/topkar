<x-app-layout>
    <x-slot name="header">
        {{trans('navigation.geotypes')}}
    </x-slot>

    <table class="table table-striped table-hover">
        <tr>
            <th>&numero;</th>
            <th>{{trans('general.in_english')}}</th>
            <th>{{trans('general.in_russian')}}</th>
         </tr>

        @foreach( $geotypes as $r )
        <tr>
            <td>{{ $loop->iteration }}{{-- Starts with 1 --}}</td>
            <td>{{$r->name_en}}</td>
            <td>{{$r->name_ru}}</td>
        </tr>
        @endforeach
    </table>
</x-app-layout>
