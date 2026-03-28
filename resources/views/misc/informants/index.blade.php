@extends('layouts.master')

@section('headTitle', trans('navigation.informants'))
@section('header', trans('navigation.informants'))

@section('search_form')
        @include("misc.informants._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection

@section('buttons')
    @if (user_can_edit())
        {!! create_button('m', 'informants', $args_by_get) !!}
    @endif
@endsection

@if (sizeof($informants))
    @section('table_block')
        <h2>{{ __('search.search_results') }}</h2>
        <table class="table table-striped table-hover">
            <tr>
                <td>&numero;</td>
                <th>{{ __('general.in_russian') }}</th>
                <td class='up-first'>{{ __('general.in_english') }}</td>
                <td class='up-first'>{{ __('misc.birth_date') }}</td>
                <td>{{ __('navigation.toponyms') }}</td>
        @if (user_can_edit())
                <td>{{ __('messages.actions') }}</td>
        @endif
             </tr>

        @foreach( $informants as $r )
            <tr>
                <td data-th="No">
                    {{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}
                </td>
                <td data-th="{{ __('general.in_russian') }}">
                    {!! to_route($r->name_ru, 'informants.show', $r, $args_by_get) !!}
                </td>
                <td data-th="{{ __('general.in_english') }}">
                    {{ $r->name_en }}
                </td>
                <td data-th="{{ __('misc.birth_date') }}">
                    {{ $r->birth_date }}
                </td>
                <td data-th="{{ __('navigation.toponyms') }}" style="text-align: left">
            @if ($r->toponyms()->count() > 0)
                    <a href="{{ route('toponyms.index') }}?search_informants[]={{$r->id}}">{{ $r->toponyms()->count() }}</a>
            @else
                    0
            @endif
                </td>
            @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit',
                            ['without_text' => 1,
                             'route' => route('informants.edit', $r)])
                    @include('widgets.form.button._delete',
                            ['without_text' => 1,
                             'route' => 'informants.destroy',
                             'args'=>['informant' => $r->id]])
                </td>
            @endif
             </tr>
        @endforeach
        </table>
        {{ $informants->appends($url_args)->onEachSide(3)->links() }}
    @endsection
@endif

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
