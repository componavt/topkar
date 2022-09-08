<x-app-layout>
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>

    <x-slot name="header">
            {{trans('navigation.settlements')}}
    </x-slot>

    <x-slot name="search_form">
        @include("dict.settlements._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
    </x-slot>
    
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('settlements.create')}}">{{__('messages.create_new_m')}}</a>
    </div>
    @endif
    
    <x-slot name="table_block">
        <table class="table table-striped table-hover">
            <tr><th>&numero;</th>
                <th>{{trans('toponym.region')}}</th>
                <th>{{trans('navigation.districts')}}</th>
                <th>{{trans('general.in_english')}}</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('general.in_karelian')}}</th>
                <th>{{trans('misc.record_place')}}</th>
                <th>{{trans('navigation.toponyms')}}</th>
                @if (user_can_edit())
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>

            @foreach( $settlements as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('toponym.region')}}">{{ $r->regionsToString() }}</td>
                <td data-th="{{trans('navigation.districts')}}">{{ $r->districtsToString() }}</td>

                <td data-th="{{trans('general.in_english')}}">{{ $r->name_en }}</td>
                <td data-th="{{trans('general.in_russian')}}">{!!to_link($r->name_ru, route('settlements.show', $r).$args_by_get)!!}</td>
                <td data-th="{{trans('general.in_karelian')}}">{{ $r->name_krl }}</td>
                
                <td data-th="{{trans('misc.record_place')}}" style="text-align: left">
                    @if ($r->recordPlaces()->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_record_places[]={{$r->id}}">{{ $r->recordPlaces()->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: left">
                    @if ($r->toponyms->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_settlements[]={{$r->id}}">{{ $r->toponyms->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                
                 @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('settlements.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'settlements.destroy', 
                             'args'=>['settlement' => $r->id]])             
                </td>
                @endif
            </tr>
            @endforeach
        </table>                
        {{ $settlements->appends($url_args)->onEachSide(3)->links() }}
    </x-slot>
    <x-slot name="footScriptExtra">
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
        {!!Html::script('js/lists.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        selectDistrict('search_regions', '{{trans('toponym.district')}}', false);
    </x-slot>    
</x-app-layout>
