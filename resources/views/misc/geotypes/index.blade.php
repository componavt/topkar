@extends('layouts.master')

@section('headTitle', trans('navigation.geotypes'))
@section('header', trans('navigation.geotypes'))

@section('search_form')
        @include("misc.geotypes._form_search")
        @include('widgets.found_records', ['n_records'=>$n_records])
@endsection

@section('buttons')
    @if (user_can_edit())
        {!! create_button('m', 'geotypes', $args_by_get) !!}
    @endif
@endsection

@if (sizeof($geotypes))
    @section('table_block')
        <h2>{{ __('search.search_results') }}</h2>
        <table class="table table-striped table-hover">
            <tr>
                <td>&numero;</td>
                <th colspan="2">{{ __('general.in_russian') }}</th>
                <td colspan="2" class='up-first'>{{ __('general.in_english') }}</td>
                <td>{{ __('navigation.toponyms') }}</td>
        @if (user_can_edit())
                <td>{{ __('messages.actions') }}</td>
        @endif
             </tr>

        @foreach( $geotypes as $r )
            <tr>
                <td data-th="No">
                    {{ $loop->iteration + $url_args['portion']*($url_args['page'] - 1) }}
                </td>
                <td data-th="{{ __('general.in_russian') }}">
                    {!! to_route($r->name_ru, 'geotypes.show', $r, $args_by_get) !!}
                </td>
                <td data-th="{{ __('general.in_russian') }}">
                    {{ $r->short_ru }}
                </td>
                <td data-th="{{ __('general.in_english') }}">
                    {{ $r->name_en }}
                </td>
                <td data-th="{{ __('general.in_english') }}">
                    {{ $r->short_en }}
                </td>
                <td data-th="{{ __('navigation.toponyms') }}" style="text-align: left">
            @if ($r->toponyms->count() > 0)
                    <a href="{{ route('toponyms.index') }}?search_geotypes[]={{$r->id}}">{{ $r->toponyms->count() }}</a>
            @else
                    0
            @endif
                </td>
            @if (user_can_edit())
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit',
                            ['without_text' => 1,
                             'route' => route('geotypes.edit', $r)])
                    @include('widgets.form.button._delete',
                            ['without_text' => 1,
                             'route' => 'geotypes.destroy',
                             'args'=>['geotype' => $r->id]])
                </td>
            @endif
             </tr>
        @endforeach
        </table>
        {{ $geotypes->appends($url_args)->onEachSide(3)->links() }}
    @endsection
@endif

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
