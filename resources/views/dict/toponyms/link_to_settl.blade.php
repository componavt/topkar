@extends('layouts.master')

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection
    
@section('headTitle', trans('navigation.toponyms'))
@section('header', trans('navigation.toponyms'))

@section('search_form')   
    <h2>{{ trans('navigation.link_to_settl') }}</h2>
    @include("dict.toponyms.form._search", ['route' => route('toponyms.link_to_settl')])
    @include('widgets.found_records', ['n_records'=>$n_records, 'template'=>'toponyms'])
@endsection
            
@section('table_block')   
    @if ($toponyms->count())
    <h2>{{ __('search.search_results') }}</h2>
    
    {!! Form::open(['url' => route('toponyms.link_to_settl.save'), 'method' => 'post']) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    
    <table id="toponyms" class="table table-striped table-hover wide-md">
        <tr>
            <td><input id="toggler" type='checkbox' checked="" 
                       onChange="toggleChecked(this, '#toponyms input[type=checkbox]')"></td>
            <td>&numero;</td>    
            <th>{{trans('toponym.toponym')}}</th>
            @if (Auth::user() && Auth::user()->id < 4)
            <td></td>
            @endif
            <td>{{trans('misc.geotype')}}</td>
            <td>{{trans('toponym.location')}} / <br>
                <i>{{trans('toponym.location_1926')}}</i></td>       
            @if (user_can_edit())
            <td>{{ trans('messages.actions') }}</td>
            @endif
        </tr>

        @foreach( $toponyms as $r ) 
        <tr>
            <td><input type="checkbox" name="toponyms[]" value="{{ $r->id }}" checked=""></td>
            <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
            <td style="font-weight: bold">
                <a href="{{route("toponyms.show", $r).$args_by_get}}">{{ $r->name }}</a>
                @if ($r->topnames()->count())
                ({{join(', ', $r->topnames()->pluck('name')->toArray())}})
                @endif
            </td>
            @if (Auth::user() && Auth::user()->id < 4)
            <td>
                @if ($r->wd)
                {!! $r->wdURL('Q') !!}
                @endif
                @if ($r->latitude || $r->longitude)
                *
                @endif
            </td>
            @endif
            <td>{{ optional($r->geotype)->name }}</td>
            <td>{{ $r->location }} / <br>
                <i>{{ $r->location1926 }}</i></td>

            @if (user_can_edit())
            <td data-th="{{ trans('messages.actions') }}" style='text-align: center'>
                @include('widgets.form.button._edit', 
                        ['without_text' => 1,
                         'route' => route('toponyms.edit', $r)])
                @include('widgets.form.button._delete', 
                        ['without_text' => 1,
                         'route' => 'toponyms.destroy', 
                         'args'=>['toponym' => $r->id]])             
            </td>
            @endif
        </tr>
        @endforeach
    </table>
    
    <div class="row">
        <div class="col-sm-3">
            <!-- Region -->
            @include('widgets.form.formitem._select2', 
                    ['name' => 'region_link', 
                     'values' => [NULL=>'']+$region_values,
                     'value' => $url_args['region_link'],
                     'is_multiple' => false,
                     'class'=>'select-region-link form-control'
                    ])
        </div>
        <div class="col-sm-4">        
            <!-- District -->
            @include('widgets.form.formitem._select2', 
                    ['name' => 'district_link', 
                     'values' => [NULL=>'']+$district_values,
                     'value' => $url_args['district_link'],
                     'is_multiple' => false,
                     'class'=>'select-district-link form-control'
            ]) 
        </div>
        <div class="col-sm-3">                
            <!-- Settlement -->
            @include('widgets.form.formitem._select2', 
                    ['name' => 'settlement_link', 
                     'values' => [NULL=>'']+$settlement_values,
                     'value' => $url_args['settlement_link'],
                     'is_multiple' => false,
                     'class'=>'select-settlement-link form-control'
            ]) 
        </div>
        <div class="col-sm-2">                
            @include('widgets.form.formitem._submit', ['title' => trans('messages.link')])
        </div>
    </div>    
    {!! Form::close() !!}

    {{ $toponyms->appends($url_args)->onEachSide(3)->links() }}
    @endif
@endsection
                
@section('footScriptExtra')
        {!! Html::script('js/select2.min.js') !!}
        {!! js('rec-delete-link') !!}
        {!! js('forms') !!}
        {!! js('lists') !!}
        {!! js('special_symbols') !!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-geotype').select2({allowClear: false, placeholder: '{{trans('misc.geotype')}}'});
        $('.select-informant').select2({allowClear: false, placeholder: '{{trans('navigation.informants')}}'});
        $('.select-recorder').select2({allowClear: false, placeholder: '{{trans('navigation.recorders')}}'});
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        $('.select-region1926').select2({allowClear: false, placeholder: '{{trans('toponym.region1926')}}'});
        $('.select-source').select2({allowClear: false, placeholder: '{{trans('toponym.source')}}'});
        $('.select-structhier').select2({allowClear: false, placeholder: '{{trans('misc.structhier')}}'});
        $('.select-ethnos_territory').select2({allowClear: false, placeholder: '{{trans('misc.ethnos_territory')}}'});
        $('.select-etymology_nation').select2({allowClear: false, placeholder: '{{trans('misc.etymology_nation')}}'});
        
        selectDistrict('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('misc.record_place')}}', false, '.select-record-place');
        selectDistrict1926('search_regions1926', '{{app()->getLocale()}}', '{{trans('toponym.district1926')}}', false);
        selectSelsovet1926('search_regions1926', 'search_districts1926', '{{app()->getLocale()}}', '{{trans('toponym.selsovet1926')}}', false);
        selectSettlement1926('search_regions1926', 'search_districts1926', 'search_selsovets1926', '{{app()->getLocale()}}', '{{trans('toponym.settlement1926')}}', false);
        selectStruct('search_structhiers', '{{app()->getLocale()}}', '{{trans('misc.struct')}}', false);

        $('.select-region-link').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        selectDistrict('region_link', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false, '.select-district-link');
        selectSettlement('region_link', 'district_link', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false, '.select-settlement-link');
        
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
