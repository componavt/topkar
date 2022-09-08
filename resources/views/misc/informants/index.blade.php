<x-app-layout>
    <x-slot name="header">
        {{trans('navigation.informants')}}
    </x-slot>

    <x-slot name="search_form">
        @include("misc.informants._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
    </x-slot>
    
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('informants.create')}}">{{__('messages.create_new_m')}}</a>
    </div>
    @endif
    
    <x-slot name="table_block">
        <table class="table table-striped table-hover">
            <tr>
                <th>&numero;</th>
                <th>{{trans('general.in_english')}}</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('misc.birth_date')}}</th>
                <th>{{trans('navigation.toponyms')}}</th>
                @if (user_can_edit())
                <th>{{ trans('messages.actions') }}</th>
                @endif
             </tr>

            @foreach( $informants as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('general.in_english')}}">{{$r->name_en}}</td>
                <td data-th="{{trans('general.in_russian')}}">{!!to_link($r->name_ru, route('informants.show', $r).$args_by_get)!!}</td>
                <td data-th="{{trans('misc.birth_date')}}">{{$r->birth_date}}</td>
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: left">
                    @if ($r->toponyms()->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_informants[]={{$r->id}}">{{ $r->toponyms()->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('informants.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'informants.destroy', 
                             'args'=>['informant' => $r->id]])             
                </td>
                @endif
             </tr>
            @endforeach
        </table>
        {{ $informants->appends($url_args)->onEachSide(3)->links() }}
    </x-slot>
    <x-slot name="footScriptExtra">
        {!!Html::script('js/rec-delete-link.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
    </x-slot>    
</x-app-layout>
