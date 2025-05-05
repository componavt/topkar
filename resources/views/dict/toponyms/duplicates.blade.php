@extends('layouts.master')

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection
    
@section('headTitle', trans('navigation.duplicates'))
@section('header', trans('navigation.duplicates'))

@section('search_form')   
    <h2>{{ trans('navigation.search_by_toponyms') }}</h2>
    @include("dict.toponyms.form._search_duplicates", ['route' => route('toponyms.duplicates')])
     <div class="row" style='line-height: 26px;'>  
         <div class="col-sm-4">
            @include('widgets.found_records', ['n_records'=>$n_records, 'template'=>'toponyms'])
         </div>
         <div class="col-sm-8 output_in">
            @if ($n_records)
            <a class="big" href="{{ route('toponyms.on_map').$args_by_get }}">{!! trans_choice('toponym.output_on_map',$n_records) !!}</a>
            @endif 
         </div>
    </div>
@endsection
        
@section('buttons')   
    @if (user_can_edit())
        {!! create_button('m', 'toponym', $args_by_get) !!}
    @endif
@endsection
    
@section('table_block')   
    @if ($toponyms->count())
    <h2>{{ __('search.search_results') }}</h2>
    <table class="table table-striped table-hover wide-md">
        <tr><td>&numero;</td>    
            <th>{{trans('toponym.toponym')}}</th>
            <td>{{trans('misc.geotype')}}</td>
            <td>{{trans('toponym.location')}} / <br>
                <i>{{trans('toponym.location_1926')}}</i></td>       
            <td>{{ trans('messages.created_at') }}</td>
            <td>{{ trans('messages.updated_at') }}</td>
            @if (user_can_edit())
            <td>{{ trans('messages.actions') }}</td>
            @endif
        </tr>

        @foreach( $toponyms as $r ) <?php //dd($r) ?>
        <tr>
            <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
            <td style="font-weight: bold">
                <a href="{{route("toponyms.show", $r).$args_by_get}}">{{ $r->name }}</a>
                @if ($r->topnames()->count())
                ({{join(', ', $r->topnames()->pluck('name')->toArray())}})
                @endif
            </td>
            <td>{{ optional($r->geotype)->name }}</td>
            <td>{{ $r->location }} / <br>
                <i>{{ $r->location1926 }}</i></td>

            <td data-th="{{ trans('messages.created_at') }}">
                {{ $r->created_at }}
            </td>
            <td data-th="{{ trans('messages.updated_at') }}">
                {{ $r->updated_at }}
            </td>
            @if (user_can_edit())
            <td data-th="{{ trans('messages.actions') }}">
                @include('widgets.form.button._delete', 
                        ['without_text' => 1,
                         'route' => 'toponyms.destroy', 
                         'args'=>['toponym' => $r->id, 'back_route'=>'duplicates']])             
            </td>
            @endif
        </tr>
        @endforeach
    </table>

    {{ $toponyms->appends($url_args)->onEachSide(3)->links() }}
    @endif
@endsection
                
@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
        {!!Html::script('js/forms.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/special_symbols.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-geotype').select2({allowClear: false, placeholder: '{{trans('misc.geotype')}}'});
        $('.select-informant').select2({allowClear: false, placeholder: '{{trans('navigation.informants')}}'});
        $('.select-recorder').select2({allowClear: false, placeholder: '{{trans('navigation.recorders')}}'});
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        $('.select-region1926').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        $('.select-source').select2({allowClear: false, placeholder: '{{trans('toponym.source')}}'});
        $('.select-structhier').select2({allowClear: false, placeholder: '{{trans('misc.structhier')}}'});
        $('.select-ethnos_territory').select2({allowClear: false, placeholder: '{{trans('misc.ethnos_territory')}}'});
        $('.select-etymology_nation').select2({allowClear: false, placeholder: '{{trans('misc.etymology_nation')}}'});
        
        selectDistrict('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('misc.record_place')}}', false, '.select-record-place');
        selectDistrict1926('search_regions1926', '{{app()->getLocale()}}', '{{trans('toponym.district_county')}}', false);
        selectSelsovet1926('search_regions1926', 'search_districts1926', '{{app()->getLocale()}}', '{{trans('toponym.selsovet_volost')}}', false);
        selectSettlement1926('search_regions1926', 'search_districts1926', 'search_selsovets1926', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        selectSettlement1926('search_regions1926', 'search_districts1926', 'search_selsovets1926', '{{app()->getLocale()}}', '{{trans('misc.record_place_brief')}}', false, '.select-record-place1926');
        selectStruct('search_structhiers', '{{app()->getLocale()}}', '{{trans('misc.struct')}}', false);
        
        $('input[type=reset]').on('click', function (e) {
        @foreach (['geotypes', 'ethnos_territories', 'etymology_nations', 'regions',
                   'districts', 'settlements', 'record_places', 'regions1926', 
                   'districts1926', 'selsovets1926', 'settlements1926', 'sources', 
                   'structhiers', 'structs', 'informants', 'recorders'] as $f)
            $('#search_{{ $f }}').val(null).trigger('change');
        @endforeach
            $('#search_toponym').attr('value','');
        });
@stop
