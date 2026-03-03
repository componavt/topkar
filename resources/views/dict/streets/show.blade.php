@extends('layouts.page')

@section('headTitle', $street->name)
@section('header', 'Улицы Петрозаводска')

@section('page_top')
    <h2>{{ $street->name }}</h2>
    <p><span class="important">TopKar ID: {{ $street->id }}</span></p>
@endsection

@section('top_links')
    {!! to_list('street', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('street', $street, $args_by_get) !!}
        {!! to_delete('street', $street, $args_by_get) !!}
        {!! to_create('street', $args_by_get, trans('messages.create_new_f')) !!}
    @endif
@endsection

@section('content')
    @if (optional($street->geotype)->name || user_can_edit())
    <p><span class='field-name'>{{trans('misc.type')}}</span>:
    <span class='field-value'>{{ optional($street->geotype)->name }}</span></p>
    @endif

    @if (optional($street)->name_ru || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_russian')}}</span>:
    <span class='field-value'>{{ optional($street)->name_ru }}</span></p>
    @endif

    @if (optional($street)->name_krl || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_karelian')}}</span>:
    <span class='field-value'>{{ optional($street)->name_krl }}</span></p>
    @endif

    @if (optional($street)->name_fin || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_finnish')}}</span>:
    <span class='field-value'>{{ optional($street)->name_fin }}</span></p>
    @endif

    @if (optional($street)->history || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.history')}}</span>:
    <span class='field-value'>{!! nl2br(e(optional($street)->history)) !!}</span></p>
    @endif
@endsection

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@endsection

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@endsection
