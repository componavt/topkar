<x-app-layout>
    
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>
    
    <x-slot name="header">
        {{trans('navigation.toponyms')}}
    </x-slot>
    
    <x-slot name="search_form">
        @include("dict.toponyms.form._search")
        @include('widgets.found_records', ['n_records'=>$n_records])
    </x-slot>
        
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('toponyms.create')}}">Создать новый</a>
    </div>
    
    <x-slot name="table_block">
        <table class="table table-bordered table-hover wide-md">
            <tr><th>&numero;</th>    
                <th>{{trans('toponym.toponym')}}</th>
                <th>{{trans('toponym.location')}} / <br>
                    {{trans('toponym.location_1926')}}</th>       
                @if (user_dict_edit())
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>

            @foreach( $toponyms as $r ) <?php //dd($r) ?>
            <tr>
                <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
                <td><a href="{{route("toponyms.show", $r).$args_by_get}}">{{ $r->name }}</a></td>
                <td>{{ $r->location }} / <br>
                    {{ $r->location1926 }}</td>
                
                @if (user_dict_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('toponyms.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'toponyms.destroy', 
                             'obj' => $r, 
                             'args'=>['id' => $r->id]])             
                </td>
                @endif
            </tr>
            @endforeach
        </table>

        {{-- $toponyms->links() --}}
        {{ $toponyms->appends($url_args)->onEachSide(3)->links() }}
    </x-slot>
                
    <x-slot name="footScriptExtra">
          {!!Html::script('js/select2.min.js')!!}
          {!!Html::script('js/lists.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
           selectDistrict('search_region','{{trans('toponym.district')}}');
    </x-slot>
</x-app-layout>