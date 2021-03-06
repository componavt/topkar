<x-app-layout>
    <x-slot name="header">
        {{trans('navigation.geotypes')}}
    </x-slot>

    <x-slot name="search_form">
        @include("aux.geotypes._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
    </x-slot>
    
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('geotypes.create')}}">{{__('messages.create_new_m')}}</a>
    </div>
    @endif
    
    <x-slot name="table_block">
        <table class="table table-striped table-hover">
            <tr>
                <th>&numero;</th>
                <th>{{trans('general.in_english')}}</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('navigation.toponyms')}}</th>
                @if (user_can_edit())
                <th>{{ trans('messages.actions') }}</th>
                @endif
             </tr>

            @foreach( $geotypes as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('general.in_english')}}">{{$r->name_en}}</td>
                <td data-th="{{trans('general.in_russian')}}">{!!to_link($r->name_ru, route('geotypes.show', $r).$args_by_get)!!}</td>
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: left">
                    @if ($r->toponyms->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_geotypes[]={{$r->id}}">{{ $r->toponyms->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('geotypes.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'geotypes.destroy', 
                             'args'=>['geotype' => $r->id]])             
                </td>
                @endif
             </tr>
            @endforeach
        </table>
        {{ $geotypes->appends($url_args)->onEachSide(3)->links() }}
    </x-slot>
    <x-slot name="footScriptExtra">
        {!!Html::script('js/rec-delete-link.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
    </x-slot>    
</x-app-layout>
