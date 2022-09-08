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
        
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('toponyms.create')}}{{$args_by_get}}">Создать новый</a>
    </div>
    @endif
    
    <x-slot name="table_block">
    @if ($toponyms->count())
        <table class="table table-bordered table-hover wide-md">
            <tr><th>&numero;</th>    
                <th>{{trans('toponym.toponym')}}</th>
                <th>{{trans('misc.geotype')}}</th>
                <th>{{trans('toponym.location')}} / <br>
                    {{trans('toponym.location_1926')}}</th>       
                @if (user_can_edit())
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>

            @foreach( $toponyms as $r ) <?php //dd($r) ?>
            <tr>
                <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
                <td>
                    <a href="{{route("toponyms.show", $r).$args_by_get}}">{{ $r->name }}</a>
                    @if ($r->topnames()->count())
                    ({{join(', ', $r->topnames()->pluck('name')->toArray())}})
                    @endif
                </td>
                <td>{{ optional($r->geotype)->name }}</td>
                <td>{{ $r->location }} / <br>
                    {{ $r->location1926 }}</td>
                
                @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('toponyms.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'toponyms.destroy', 
                             'args'=>['toponym' => $r->id]])             
                </td>
                @endif
            </tr>
            @endforeach
        </table>

        {{-- $toponyms->links() --}}
        {{ $toponyms->appends($url_args)->onEachSide(3)->links() }}
        @endif
    </x-slot>
                
    <x-slot name="footScriptExtra">
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
        {!!Html::script('js/lists.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-geotype').select2({allowClear: false, placeholder: '{{trans('misc.geotype')}}'});
        $('.select-informant').select2({allowClear: false, placeholder: '{{trans('navigation.informants')}}'});
        $('.select-recorder').select2({allowClear: false, placeholder: '{{trans('navigation.recorders')}}'});
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        $('.select-region1926').select2({allowClear: false, placeholder: '{{trans('toponym.region1926')}}'});
        $('.select-structhier').select2({allowClear: false, placeholder: '{{trans('misc.structhier')}}'});
        $('.select-ethnos_territory').select2({allowClear: false, placeholder: '{{trans('misc.ethnos_territory')}}'});
        $('.select-etymology_nation').select2({allowClear: false, placeholder: '{{trans('misc.etymology_nation')}}'});
        
        selectDistrict('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        selectDistrict1926('search_regions1926', '{{app()->getLocale()}}', '{{trans('toponym.district1926')}}', false);
        selectSelsovet1926('search_regions1926', 'search_districts1926', '{{app()->getLocale()}}', '{{trans('toponym.selsovet1926')}}', false);
        selectSettlement1926('search_regions1926', 'search_districts1926', 'search_selsovets1926', '{{app()->getLocale()}}', '{{trans('toponym.settlement1926')}}', false);
        selectStruct('search_structhiers', '{{app()->getLocale()}}', '{{trans('misc.struct')}}', false);

    </x-slot>
</x-app-layout>