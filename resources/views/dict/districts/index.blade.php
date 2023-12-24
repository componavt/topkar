@extends('layouts.master')
@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@stop

@section('header', trans('navigation.districts'))

@section('search_form')   
        @include("dict.districts._form_search", ['route'=> route('districts.index')])
        @include('widgets.found_records', ['n_records'=>$n_records])
@stop
    
@section('buttons')   
    @if (user_can_edit())
        {!! create_button('m', 'district', $args_by_get) !!}
    @endif
@endsection
    
@section('table_block')   
    @if ($districts->count())
    <h2>{{ __('search.search_results') }}</h2>
    <table class="table table-striped table-hover">
        <tr><td>&numero;</td>
            <td>{{trans('toponym.region')}}</td>
            <th>{{trans('general.in_russian')}}</th>
            <td class='up-first'>{{trans('general.in_english')}}</td>
            <td style="text-align: right">{{trans('navigation.settlements')}}</td>
            <td style="text-align: right">{{trans('navigation.toponyms')}}</td>
            @if (user_can_edit())
            <td style="text-align: center">{{ trans('messages.actions') }}</td>
            @endif
        </tr>

        @foreach( $districts as $r )
        <tr>
            <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
            <td data-th="{{trans('toponym.region')}}">{{ $r->region->name }}</td>
            <td data-th="{{trans('general.in_russian')}}"><b>{{ $r->name_ru }}</b></td>
            <td data-th="{{trans('general.in_english')}}"><b>{{ $r->name_en }}</b></td>
            <td data-th="{{trans('navigation.settlements')}}" style="text-align: right">
                @if ($r->settlements()->count() > 0)
                <a href="{{route('settlements.index')}}?search_districts[]={{$r->id}}">
                    {{ number_format($r->settlements()->count(), 0, ',', ' ') }}
                </a>
                @else
                0
                @endif
            </td>
            <td data-th="{{trans('navigation.toponyms')}}" style="text-align: right">
                @if ($r->toponyms->count() > 0)
                <a href="{{route('toponyms.index')}}?search_districts[]={{$r->id}}">
                    {{ number_format($r->toponyms->count(), 0, ',', ' ') }}
                </a>
                @else
                0
                @endif
            </td>
            @if (user_can_edit())
            <td data-th="{{ trans('messages.actions') }}" style="text-align: center">
                @include('widgets.form.button._edit', 
                        ['without_text' => 1,
                         'route' => route('districts.edit', $r)])
                @include('widgets.form.button._delete', 
                        ['without_text' => 1,
                         'route' => 'districts.destroy', 
                         'args'=>['district' => $r->id]])             
            </td>
            @endif
        </tr>
        @endforeach
    </table>
    {{ $districts->appends($url_args)->onEachSide(3)->links() }}
    @endif
@stop

@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
@stop
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
@stop
