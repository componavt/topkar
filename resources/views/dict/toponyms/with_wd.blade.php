@extends('layouts.master')

@section('headTitle', trans('navigation.with_wd'))

@section('search_form')   
        @include("dict.toponyms.form._search_wd")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection
        
@section('header', trans('navigation.toponyms'). ' '. mb_strtolower(trans('navigation.with_wd')))

@section('main')   
    @section('table_block')   
        @if ($toponyms->count())
        <table class="table table-striped table-hover wide-md">
            <tr><th>&numero;</th>    
                <th>{{trans('toponym.toponym')}}</th>
                <th>{{trans('toponym.wd_URL')}}</th>
                <th>{{trans('misc.geotype')}}</th>
                <th>{{trans('toponym.location')}} / <br>
                    {{trans('toponym.location_1926')}}</th>       
            </tr>

            @foreach( $toponyms as $r ) <?php //dd($r) ?>
            <tr>
                <td>{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}{{-- Starts with 1 --}}</td>
                <td>
                    <a href="{{route("toponyms.show", $r).$args_by_get}}">{{ $r->name }}</a>
                    @if ($r->topnames()->count())
                    ({{join(', ', $r->topnames()->pluck('name')->toArray())}})
                    @endif
                </td>
                <td>
                    {!! $r->wdURL() !!}
                    @if ($r->latitude || $r->longitude)
                    *
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
                
