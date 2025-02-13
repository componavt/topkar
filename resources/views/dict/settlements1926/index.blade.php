@extends('layouts.master')

@section('headTitle', trans('navigation.settlements1926'))
@section('header', trans('navigation.settlements1926'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection

@section('search_form')   
        @include("dict.settlements1926._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection
    
@section('buttons')   
    @if (user_can_edit())
        {!! create_button('m', 'settlements1926', $args_by_get) !!}
    @endif
@endsection
    
@if (sizeof($settlements1926))
    @section('table_block')   
        <h2>{{ __('search.search_results') }}</h2>
        <table class="table table-striped table-hover">
            <tr><td>&numero;</td>
                <td>{{trans('toponym.region')}}</td>
                <td>{{trans('toponym.district')}}</td>
                <td>{{trans('toponym.selsovet')}}</td>
                <td>{{trans('toponym.name')}}</td>
{{--                <td class='up-first'>{{trans('general.in_karelian')}}</td>
                <td class='up-first'>{{trans('general.in_english')}}</td> --}}
                <td>{{trans('navigation.toponyms')}}</td>
                @if (user_can_edit())
                <td>{{ trans('messages.actions') }}</td>
                @endif
            </tr>

            @foreach( $settlements1926 as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('toponym.region')}}">{{ $r->selsovet1926->district1926 ? $r->selsovet1926->district1926->region->name : "empty district1926"}}</td>
                <td data-th="{{trans('toponym.districts_1926')}}">{{ $r->selsovet1926->district1926->name }}</td>
                <td data-th="{{trans('toponym.selsovets_1926')}}">{{ $r->selsovet1926->name }}</td>

                <td data-th="{{trans('toponym.name')}}">
                    {!!to_link($r->name_ru, route('settlements1926.show', $r).$args_by_get)!!}@if ($r->longitude & $r->latitude)
                                                                                                *@endif
                </td>
{{--                <td data-th="{{trans('general.in_karelian')}}">{{ $r->name_krl }}</td>
                <td data-th="{{trans('general.in_english')}}">{{ $r->name_en }}</td> --}}
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: left">
                    @if ($r->toponyms->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_settlements1926[]={{$r->id}}">{{ $r->toponyms->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                 @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('settlements1926.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'settlements1926.destroy', 
                             'args'=>['settlements1926' => $r->id]])             
                </td>
                @endif
            </tr>
            @endforeach
        </table>                
        {{ $settlements1926->appends($url_args)->onEachSide(3)->links() }}
        
        <p>* - {{ __('toponym.with_coords') }}</p>        
    @endsection
@endif

@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
        {!!Html::script('js/lists.js')!!}
@endsection

@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        selectDistrict1926('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district1926')}}', false);
        selectSelsovet1926('search_regions', 'search_districts1926', '{{app()->getLocale()}}', '{{trans('toponym.selsovet1926')}}', false);
@stop

