<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Регионы
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table><tr><th>N</th>
                           <th>{{trans('general.in_english')}}</th>
                           <th>{{trans('general.in_russian')}}</th>
                        </tr>
                    
                        @foreach( $regions as $r )
                        <tr>
                            <td>{{ $loop->iteration }}{{-- Starts with 1 --}}</td>
                            <td>{{$r->name_en}}</td>
                            <td>{{$r->name_ru}}</td>
                        </tr>
                        @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
