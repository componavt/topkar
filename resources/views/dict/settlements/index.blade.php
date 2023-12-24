@extends('layouts.master')

@section('headTitle', trans('navigation.settlements'))
@section('header', trans('navigation.settlements'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection

@section('search_form')   
        @include("dict.settlements._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection
    
@section('buttons')   
    @if (user_can_edit())
        {!! create_button('m', 'structs', $args_by_get) !!}
    @endif
@endsection
    
@if (sizeof($settlements))
    @section('table_block')  
        <h2>{{ __('search.search_results') }}</h2>
        <table class="table table-striped table-hover">
            <tr><td>&numero;</td>
                <td>{{trans('toponym.region')}}</td>
                <td>{{trans('navigation.districts')}}</td>
                <td>{{trans('misc.type')}}</td>
                <th>{{trans('general.in_russian')}}</th>
                <td class='up-first'>{{trans('general.in_karelian')}}</td>
                <td class='up-first'>{{trans('messages.in_vepsian')}}</td>
                <td class='up-first'>{{trans('general.in_english')}}</td>
                <td>{{trans('misc.record_place')}}</td>
                <td>{{trans('navigation.toponyms')}}</td>
                @if (user_can_edit())
                <td>{{ trans('messages.actions') }}</td>
                @endif
            </tr>

            @foreach( $settlements as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('toponym.region')}}">{{ $r->regionsToString() }}</td>
                
                <td data-th="{{trans('navigation.districts')}}">
                @if ($r->districts)
                    {{$r->districtNamesWithDates()}}
                @endif
                </td>

                <td data-th="{{trans('misc.type')}}">{{ $r->geotype ? $r->geotype->short_name : '' }}</td>
                <td data-th="{{trans('general.in_russian')}}">
                    {!!to_link($r->name_ru, route('settlements.show', $r).$args_by_get)!!}@if ($r->longitude & $r->latitude)*@endif
                </td>
                <td data-th="{{trans('general.in_karelian')}}">{{ $r->name_krl }}</td>
                <td data-th="{{trans('messages.in_vepsian')}}">{{ $r->name_vep }}</td>
                <td data-th="{{trans('general.in_english')}}">{{ $r->name_en }}</td>
                
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
        selectDistrict('search_regions', '{{$locale}}', '{{trans('toponym.district')}}', false);
@endsection
