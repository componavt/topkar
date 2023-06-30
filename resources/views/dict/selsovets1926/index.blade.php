@extends('layouts.master')

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@stop

@section('header', trans('toponym.Selsovets_in_1926_year'))

@section('search_form')
        @include("dict.selsovets1926._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@stop
    
@section('main')   
    @if (user_can_edit())
    <div class="page-buttons">
        <a class="btn btn-secondary btn-default" href="{{route('selsovets1926.create')}}">{{__('messages.create_new_m')}}</a>
    </div>
    @endif
    
    @section('table_block')
        <table class="table table-striped table-hover">
            <tr><th>&numero;</th>
                <th>{{trans('toponym.region')}}</th>
                <th>{{trans('toponym.district1926')}}</th>
                <th>{{trans('general.in_russian')}}</th>
                <th>{{trans('general.in_karelian')}}</th>
                <th>{{trans('general.in_english')}}</th>
                <th>{{trans('navigation.settlements_1926')}}</th>
                <th>{{trans('navigation.toponyms')}}</th>
                @if (user_can_edit())
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
                    
            @foreach( $selsovets1926 as $r )
            <tr>
                <td data-th="No">{{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}</td>
                <td data-th="{{trans('toponym.region')}}">{{ optional($r->district1926->region)->name }}</td>
                <td data-th="{{trans('toponym.district1926')}}">{{ optional($r->district1926)->name }}</td>
                <td data-th="{{trans('general.in_russian')}}">{{ $r->name_ru }}</td>
                <td data-th="{{trans('general.in_karelian')}}">{{ $r->name_krl }}</td>
                <td data-th="{{trans('general.in_english')}}">{{ $r->name_en }}</td>
                <td data-th="{{trans('navigation.settlements_1926')}}" style="text-align: left">
                    @if ($r->toponyms->count() > 0)
                    <a href="{{route('settlements1926.index')}}?search_selsovets1926[]={{$r->id}}">{{ $r->settlements1926->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                <td data-th="{{trans('navigation.toponyms')}}" style="text-align: left">
                    @if ($r->toponyms->count() > 0)
                    <a href="{{route('toponyms.index')}}?search_selsovets1926[]={{$r->id}}">{{ $r->toponyms->count() }}</a>
                    @else
                    0
                    @endif
                </td>
                 @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['without_text' => 1,
                             'route' => route('selsovets1926.edit', $r)])
                    @include('widgets.form.button._delete', 
                            ['without_text' => 1,
                             'route' => 'selsovets1926.destroy', 
                             'args'=>['selsovets1926' => $r->id]])             
                </td>
                @endif
            </tr>
            @endforeach
        </table>
        {{ $selsovets1926->appends($url_args)->onEachSide(3)->links() }}
    @endsection
@endsection
@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
        {!!Html::script('js/lists.js')!!}
@stop
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        selectDistrict1926('search_regions', '{{trans('toponym.district1926')}}', false);
@stop
