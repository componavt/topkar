@extends('layouts.master')

@section('headTitle', trans('navigation.sources'))
@section('header', trans('navigation.sources'))

@section('search_form')   
        @include("misc.sources._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection
    
@section('buttons')   
    @if (user_can_edit())
        {!! create_button('m', 'sources', $args_by_get) !!}
    @endif
@endsection
    
@if (sizeof($sources))    
    @section('table_block')   
        <h2>{{ __('search.search_results') }}</h2>
        <table class="table table-striped table-hover">
            <tr>
                <td>&numero;</td>
                <td>{{trans('toponym.name')}}</td>
                <td class='up-first'>{{trans('toponym.short_name')}}</td>
                <th class='up-first'>{{trans('general.in_english')}}</th>
                <td class='up-first'>{{trans('general.short_en')}}</td>
                <td>{{trans('navigation.toponyms')}}</td>
                @if (user_can_edit())
                <td>{{ trans('messages.actions') }}</td>
                @endif
             </tr>

            @foreach( $sources as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('toponym.name')}}">{!!to_link($r->name_ru, route('sources.show', $r).$args_by_get)!!}</td>
                <td data-th="{{trans('toponym.short_name')}}">{{$r->short_ru}}</td>
                <td data-th="{{trans('general.in_english')}}">{{$r->name_en}}</td>
                <td data-th="{{trans('general.short_en')}}">{{$r->short_en}}</td>
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: left">
                    @if ($r->toponyms->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_sources[]={{$r->id}}">{{ $r->toponyms->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('sources.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'sources.destroy', 
                             'args'=>['source' => $r->id]])             
                </td>
                @endif
             </tr>
            @endforeach
        </table>
        {{ $sources->appends($url_args)->onEachSide(3)->links() }}
    @endsection
@endif

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
