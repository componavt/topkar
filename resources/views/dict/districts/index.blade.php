@extends('layouts.master')
@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@stop

@section('header', trans('navigation.districts'))

@section('search_form')   
        @include("dict.districts._form_search", ['route'=> route('districts.index')])
        @include('widgets.found_records', ['n_records'=>$n_records])
@stop
    
@section('main')   
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('districts.create')}}">{{__('messages.create_new_m')}}</a>
    </div>
    @endif
    
    <x-slot name="table_block">
        <table class="table table-striped table-hover">
            <tr><th>&numero;</th>
                <th>{{trans('toponym.region')}}</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('general.in_english')}}</th>
                <th style="text-align: right">{{trans('navigation.toponyms')}}</th>
                @if (user_can_edit())
                <th style="text-align: center">{{ trans('messages.actions') }}</th>
                @endif
            </tr>

            @foreach( $districts as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('toponym.region')}}">{{ $r->region->name }}</td>
                <td data-th="{{trans('general.in_russian')}}">{{ $r->name_ru }}</td>
                <td data-th="{{trans('general.in_english')}}">{{ $r->name_en }}</td>
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
@stop
@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
@stop
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
@stop
