@extends('layouts.master')

@section('headTitle', trans('navigation.nladoga'))
@section('header', trans('navigation.nladoga'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection
    
@section('search_form')   
    <h2>{{ trans('navigation.search_by_nladoga') }}</h2>
    @include("dict.toponyms.form._search_nladoga", ['route' => 'nladoga'])
     <div class="row" style='line-height: 26px;'>  
         <div class="col-sm-4">
            @include('widgets.found_records', ['n_records'=>$n_records, 'template'=>'toponyms'])
         </div>
         <div class="col-sm-8 output_in">
            @if ($n_records)
            <a class="big" href="{{ route('toponyms.nladoga.on_map').$args_by_get }}">{!! trans_choice('toponym.output_on_map',$n_records) !!}</a>
            @endif 
         </div>
    </div>
@endsection
        
@section('main')   
    @section('table_block')   
        @if ($toponyms->count())
        <table class="table table-striped table-hover wide-md">
            <tr><th>&numero;</th>    
                <th>{{trans('toponym.toponym')}}</th>
                <th>{{trans('misc.geotype')}}</th>
                <th>{{trans('toponym.location')}} / <br>
                    {{trans('toponym.location_nladoga')}}</th>       
            @if (user_can_edit())
                <td>{{ trans('messages.actions') }}</td>
            @endif
            </tr>

            @foreach( $toponyms as $r ) <?php //dd($r) ?>
            <tr>
                <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
                <td>
                    <a href="{{route('toponyms.show', $r).$args_by_get}}">{{ $r->name }}</a>
                    @if ($r->topnames()->count())
                    ({{join(', ', $r->topnames()->pluck('name')->toArray())}})
                    @endif
                </td>
                <td>{{ optional($r->geotype)->name }}</td>
                <td>{{ $r->location }} / <br>
                    {{ $r->location1926 }}</td>                
            @if (user_can_edit())
            <td data-th="{{ trans('messages.actions') }}" style='text-align: center'>
                @include('widgets.form.button._edit', 
                        ['without_text' => 1,
                         'route' => route('toponyms.edit', $r)])
            </td>
            @endif
            </tr>
            @endforeach
        </table>

        {{-- $toponyms->links() --}}
        {{ $toponyms->appends($url_args)->onEachSide(3)->links() }}
        @endif
    @endsection
@endsection
                
@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/special_symbols.js')!!}
@endsection

@section('jqueryFunc')
        $('.select-geotype').select2({allowClear: false, placeholder: '{{trans('misc.geotype')}}'});       
        selectDistrict('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false);
        selectSettlementForDistricts('search_districts', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        selectSelsovet1926ForRegions([{{ $nladoga_region1926 }}], 'search_districts1926', '{{app()->getLocale()}}', '{{trans('toponym.selsovet_volost')}}', false);
        selectSettlement1926ForRegions([{{ $nladoga_region1926 }}], 'search_districts1926', 'search_selsovets1926', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        
        $('input[type=reset]').on('click', function (e) {
        @foreach (['geotypes', 'districts', 'settlements', 'record_places', 'districts1926', 'selsovets1926', 'settlements1926'] as $f)
            $('#search_{{ $f }}').val(null).trigger('change');
        @endforeach
            $('#search_toponym').attr('value','');
        });
@stop
                
