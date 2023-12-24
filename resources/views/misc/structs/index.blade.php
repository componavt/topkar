@extends('layouts.master')

@section('headTitle', trans('navigation.structs'))
@section('header', trans('navigation.structs'))

@section('search_form')   
        @include("misc.structs._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection
    
@section('buttons')   
    @if (user_can_edit())
        {!! create_button('m', 'structs', $args_by_get) !!}
    @endif
@endsection
    
@if ($structs)
    @section('table_block')   
        <h2>{{ __('search.search_results') }}</h2>
        <table class="table table-striped table-hover">
            <tr>
                <td>&numero;</td>
                <td>{{trans('misc.structhier')}}</td>
                <th>{{trans('toponym.name')}}</th>
                <td>{{trans('navigation.toponyms')}}</td>
                @if (user_can_edit())
                <td>{{ trans('messages.actions') }}</td>
                @endif
             </tr>

            @foreach( $structs as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('misc.structhier')}}">{{$r->structhier ? $r->structhier->nameToString() : ''}}</td>
                <td data-th="{{trans('toponym.name')}}"><b>{{$r->name_ru}}</b></td>
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: left">
                    @if ($r->toponyms->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_structs[]={{$r->id}}">{{ $r->toponyms->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('structs.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'structs.destroy', 
                             'args'=>['struct' => $r->id]])             
                </td>
                @endif
             </tr>
            @endforeach
        </table>
        {{ $structs->appends($url_args)->onEachSide(3)->links() }}
    @endsection
@endif

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
