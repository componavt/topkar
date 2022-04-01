<x-app-layout>
    <x-slot name="header">
            {{-- ucfirst, see https://stackoverflow.com/a/12066075/1173350 --}}
            {{mb_convert_case(trans('general.regions'), MB_CASE_TITLE, 'UTF-8')}}
    </x-slot>

                <table class="table table-striped table-hover"><tr><th>&numero;</th>
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
</x-app-layout>
