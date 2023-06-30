@extends('layouts.master')

@section('header', trans('navigation.sources'))

@section('search_form')   
        @include("misc.sources._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection
    
@section('main')   
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('sources.create')}}">{{__('messages.create_new_m')}}</a>
    </div>
    @endif
    
    @section('table_block')   
        <table class="table table-striped table-hover">
            <tr>
                <th>&numero;</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('general.short_ru')}}</th>
                <th>{{trans('general.in_english')}}</th>
                <th>{{trans('general.short_en')}}</th>
                <th>{{trans('navigation.toponyms')}}</th>
                @if (user_can_edit())
                <th>{{ trans('messages.actions') }}</th>
                @endif
             </tr>

            @foreach( $sources as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('general.in_russian')}}">{!!to_link($r->name_ru, route('sources.show', $r).$args_by_get)!!}</td>
                <td data-th="{{trans('general.short_ru')}}">{{$r->short_ru}}</td>
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
@endsection
@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
