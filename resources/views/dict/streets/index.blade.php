@extends('layouts.master')

@section('headTitle', trans('navigation.streets'))
@section('header', trans('navigation.streets'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}
@endsection

@section('search_form')
        @include("dict.streets._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection

@section('buttons')
    @if (user_can_edit())
        {!! create_button('f', 'streets', $args_by_get) !!}
    @endif
@endsection

@if (sizeof($streets))
    @section('table_block')
        <h2>{{ __('search.search_results') }}</h2>
        <table class="table table-striped table-hover">
            <tr><td>&numero;</td>
                <td></td>
                <td>{{ __('misc.type') }}</td>
                <td>{{ __('toponym.name') }} (рус.)</td>
                <td class='up-first'>{{ __('general.in_karelian') }}</td>
                <td class='up-first'>{{ __('messages.in_finnish') }}</td>
        @if (user_can_edit())
                <td>{{ __('messages.actions') }}</td>
        @endif
            </tr>

        @foreach( $streets as $r )
            <tr>
                <td data-th="No">
                    {{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}
                </td>
                <td>
            @if ($r->geometry)
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
            @endif
                </td>

                <td data-th="{{ __('misc.type') }}">
                    {{ $r->geotype ? $r->geotype->short_name : '' }}
                </td>
                <td data-th="{{ __('general.in_russian') }}">
                    {!! to_route($r->name_ru, 'streets.show', $r, $args_by_get)!!}
                </td>
                <td data-th="{{ __('general.in_karelian') }}">
                    {{ $r->name_krl }}
                </td>
                <td data-th="{{ __('messages.in_finnish') }}">
                    {{ $r->name_fi }}
                </td>

            @if (user_can_edit())
                <td data-th="{{ __('messages.actions') }}">
                    @include('widgets.form.button._edit',
                            ['without_text' => 1,
                             'route' => route('streets.edit', $r)])
                    @include('widgets.form.button._delete',
                            ['without_text' => 1,
                             'route' => 'streets.destroy',
                             'args'=>['street' => $r->id]])
                </td>
            @endif
            </tr>
        @endforeach
        </table>
        {{ $streets->appends($url_args)->onEachSide(3)->links() }}
    @endsection
@endif

@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/rec-delete-link.js')!!}
        {!!Html::script('js/lists.js')!!}
@endsection

@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
        $('.select-geotype').select2({allowClear: false, placeholder: '{{trans('misc.type')}}'});
        @foreach ($structhier_values as $hier_id => $hier_name)
            selectStruct('', '{{app()->getLocale()}}', '{{ $hier_name }}', false, '.select-struct{{ $hier_id }}', '', {{ $hier_id }});
        @endforeach

        $('input[type=reset]').on('click', function (e) {
            $('#search_geotypes').val(null).trigger('change');
        @foreach ([7,8] as $hier_id)
            $('.select-struct{{ $hier_id }}').val(null).trigger('change');
        @endforeach
            $('#search_name').attr('value','');
        });
@endsection
