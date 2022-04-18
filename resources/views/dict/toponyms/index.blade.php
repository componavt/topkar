<x-app-layout>
    
    <x-slot name="headExtra">
          {!!Html::style('css/select2.min.css')!!}  
    </x-slot>
    
    <x-slot name="header">
            {{trans('navigation.toponyms')}}
    </x-slot>
    
    
    
    @include("dict.toponyms.form._search")
    
    @include('widgets.found_records', ['n_records'=>$n_records])

                <table class="table table-striped table-hover">
                    <tr><th>&numero;</th>    
                        <th>{{trans('toponym.toponym')}}</th>
                        <th>{{trans('toponym.location')}} / <br>
                            {{trans('toponym.location_1926')}}</th>       
                    </tr>
                    
                    @foreach( $toponyms as $r ) <?php //dd($r) ?>
                    <tr>
                        <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
                        <td><a href="{{route("toponyms.show", $r)}}">{{ $r->name }}</a></td>
                        <td>{{ $r->location }} / <br>
                            {{ $r->location1926 }}</td>
                    </tr>
                    @endforeach
                </table>
                
                {{-- $toponyms->links() --}}
                {{ $toponyms->onEachSide(3)->links() }}
                
    <x-slot name="footScriptExtra">
          {!!Html::script('js/select2.min.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
           $(".select-district").select2();
    </x-slot>
</x-app-layout>