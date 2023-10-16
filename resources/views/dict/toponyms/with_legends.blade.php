@extends('layouts.master')

@section('headTitle', trans('navigation.toponyms'). ' '. mb_strtolower(trans('navigation.with_legends')))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection
    
@section('search_form')   
        @include("dict.toponyms.form._search_with_legends")
        @include('widgets.found_records', ['n_records'=>$n_records, 'template'=>'toponyms'])
@endsection
        
@section('header', trans('navigation.toponyms'). ' '. mb_strtolower(trans('navigation.with_legends')))

@section('main')   
    @section('table_block')   
        @if ($toponyms->count())
        <table class="table table-striped table-hover wide-md">
            <tr><th>&numero;</th>    
                <th>{{trans('toponym.toponym')}}</th>
                <th>{{trans('toponym.legend')}}</th>
                <th>{{trans('misc.geotype')}}</th>
                <th>{{trans('toponym.location')}} / <br>
                    {{trans('toponym.location_1926')}}</th>       
            </tr>

            @foreach( $toponyms as $r ) 
            <tr>
                <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
                <td>
                    <a href="{{route('toponyms.show', $r).$args_by_get}}">{{ $r->name }}</a>
                    @if ($r->topnames()->count())
                    ({{join(', ', $r->topnames()->pluck('name')->toArray())}})
                    @endif
                </td>
                <td>
                    {{ $r->legend }}
                    @if ($r->legend && $r->textUrls())
                    <br>
                    @endif
                    @if ($r->textUrls())
                    {{ __('toponym.on_vepkar') }}
                    {!! $r->textUrls() !!}
                    @endif
                </td>
                <td>{{ optional($r->geotype)->name }}</td>
                <td>{{ $r->location }} / <br>
                    {{ $r->location1926 }}</td>                
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
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        
        selectDistrict('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
@stop
