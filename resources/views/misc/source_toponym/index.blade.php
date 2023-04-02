<x-app-layout>
    <x-slot name="header">
            {{trans('toponym.sources')}}
    </x-slot>

    <x-slot name="table_block">
        <table class="table table-striped table-hover">
            <tr><th>&numero;</th>
                <th>{{trans('toponym.source')}}</th>
                <th>{{trans('toponym.source_text')}}</th>
                <th>{{trans('navigation.toponyms')}}</th>
            </tr>

            @foreach( $sources as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration }}</td>
                <td data-th="{{trans('toponym.source')}}">{{ optional($r->source)->name }}</td>
                <td data-th="{{trans('toponym.source_text')}}">{{ $r->source_text }}</td>
                <td data-th="количество" style="text-align: right">
                    <a href="{{route('toponyms.index')}}?search_source_text={{$r->source_text}}&search_sources[]={{$r->source_id}}">{{ $r->count }}</a>
                </td>
            </tr>
            @endforeach
        </table>
    </x-slot>
</x-app-layout>
