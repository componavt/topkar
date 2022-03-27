<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{trans('navigation.toponyms')}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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
                
            </div>
        </div>
    </div>
</x-app-layout>
