<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.toponyms')}}
    </x-slot>

                <table class="table table-striped table-hover"><tr><th>&numero;</th>
                           <th>{{trans('toponym.location')}}</th>
                           <th>{{trans('toponym.location_1926')}}</th>
                           
                           <th>{{trans('toponym.toponym')}}</th>
                        </tr>
                    
                        @foreach( $toponyms as $r ) <?php //dd($r) ?>
                        <tr>
                            <td>{{ $loop->iteration + $portion*($page - 1) }}{{-- Starts with 1 --}}</td>
                            <td>{{ $r->location }}</td>
                            <td>{{ $r->location1926 }}</td>
                            
                            <td><a href="{{route("toponyms.show", $r)}}">{{ $r->name }}</a></td>
                        </tr>
                        @endforeach
                </table>
                
                {{-- $toponyms->links() --}}
                {{ $toponyms->onEachSide(3)->links() }}
</x-app-layout>
