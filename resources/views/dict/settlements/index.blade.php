@extends('layouts.master')

@section('header', trans('navigation.settlements'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection

@section('search_form')   
        @include("dict.settlements._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection
    
@section('main')   
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('settlements.create')}}{{$args_by_get}}">{{__('messages.create_new_m')}}</a>
    </div>
    @endif
    
    @if (sizeof($settlements))
    @section('table_block')  
        <table class="table table-striped table-hover">
            <tr><th>&numero;</th>
                <th>{{trans('toponym.region')}}</th>
                <th>{{trans('navigation.districts')}}</th>
                <th>{{trans('misc.type')}}</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('general.in_karelian')}}</th>
                <th>{{trans('messages.in_vepsian')}}</th>
                <th>{{trans('general.in_english')}}</th>
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
                
                <td data-th="{{trans('navigation.districts')}}">
                @if ($r->districts)
                    {{$r->districtNamesWithDates()}}
                @endif
                </td>

                <td data-th="{{trans('misc.type')}}">{{ $r->geotype ? $r->geotype->short_name : '' }}</td>
                <td data-th="{{trans('general.in_russian')}}">
                    {!!to_link($r->name_ru, route('settlements.show', $r).$args_by_get)!!}
                    @if ($r->longitude & $r->latitude)
                    *
                    @endif
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
@endsection

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
