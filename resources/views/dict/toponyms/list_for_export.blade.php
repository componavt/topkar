@extends('layouts.master')

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection
    
@section('headTitle', trans('navigation.toponyms'))
@section('header', trans('navigation.toponyms'))

@section('search_form')   
    <h2>{{ trans('navigation.list_for_export') }}</h2>
    @include("dict.toponyms.form._search", ['route' => route('toponyms.list_for_export')])
    @include('widgets.found_records', ['n_records'=>$n_records, 'template'=>'toponyms'])
@endsection
            
@section('table_block')   
    @if ($toponyms->count())
    <h2>{{ __('search.search_results') }}</h2>
    
    {!! Form::open(['url' => route('toponyms.export'), 'method' => 'post']) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    
    <table id="toponyms" class="table table-striped table-hover wide-md">
        <tr>
            <td><input id="toggler" type='checkbox' checked="" 
                       onChange="toggleChecked(this, '#toponyms input[type=checkbox]')"></td>
            <td>&numero;</td>    
            <td><input name="with_district" type='checkbox' checked="" value="1">
                {{ __('toponym.district') }} / {{ __('toponym.district1926') }}</td>
            <td><input name="with_settlement" type='checkbox' checked="" value="1">
                {{ __('toponym.settlement') }} / {{ __('toponym.settlement1926') }}</td>
            <th>{{ __('toponym.toponym') }}</th>
{{--            @if (Auth::user() && Auth::user()->id < 4)
            <td></td>
            @endif--}}
            <td>{{ __('misc.geotype') }}</td>
            <td>{{ __('toponym.main_info')}}</td>
            <td>{{ __('toponym.legend')}}</td>
{{--            @if (user_can_edit())
            <td>{{ __('messages.actions') }}</td>
            @endif --}}
        </tr>

        @foreach( $toponyms as $r ) 
        <tr>
            <td><input type="checkbox" name="toponyms[]" value="{{ $r->id }}" checked=""></td>
            
            <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
            
            <td>{{ $r->district_name }}@if ($r->district1926_name)
            / {{ $r->district1926_name }}@endif
            </td>
            
            <td>{{ $r->settlement_name }}@if ($r->settlement1926_name)
            / {{ $r->settlement1926_name }}@endif
            </td>
            
            <td style="font-weight: bold">
                <a href="{{route("toponyms.show", $r).$args_by_get}}">{{ $r->name }}</a>
                @if ($r->topnames()->count())
                ({{join(', ', $r->topnames()->pluck('name')->toArray())}})
                @endif
            </td>
{{--            @if (Auth::user() && Auth::user()->id < 4)
            <td>
                @if ($r->wd)
                {!! $r->wdURL('Q') !!}
                @endif
                @if ($r->latitude || $r->longitude)
                *
                @endif
            </td>
            @endif--}}
            
            <td>{{ optional($r->geotype)->name }}</td>
            <td>{!! preg_replace("/\n/", "<br>\n", $r->main_info) !!}</td>
            <td>{{ $r->legend }}</td>

{{--            @if (user_can_edit())
            <td data-th="{{ trans('messages.actions') }}" style='text-align: center'>
                @include('widgets.form.button._edit', 
                        ['without_text' => 1,
                         'route' => route('toponyms.edit', $r)])
                @include('widgets.form.button._delete', 
                        ['without_text' => 1,
                         'route' => 'toponyms.destroy', 
                         'args'=>['toponym' => $r->id]])             
            </td>
            @endif--}}
        </tr>
        @endforeach
    </table>
    
    @include('widgets.form.formitem._submit', ['title' => trans('messages.export_to_file')])
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
