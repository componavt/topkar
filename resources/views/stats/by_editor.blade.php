@extends('layouts.master')

@section('headTitle', trans('stats.stats_by_editors'))
@section('header', trans('stats.stats_by_editors'))

@section('headExtra')
        {!! css('stats') !!}  
        {!! css('page') !!}  
@endsection

@section('main')   
    по редактору: <b>{{ $user->full_name }}</b> 
    
    {!! Form::open(array('method'=>'GET', 'route' => ['stats.by_editor', $user])) !!}
    <div style="display: flex; padding: 10px 0 0 20px; align-items: baseline; background-color: #d1dff554;">
        <span style="margin-right: 10px">с</span>
        @include('widgets.form.formitem._DATE', 
            ['name' => 'min_date', 
             'value' => old('min_date') ? old('min_date') : ($min_date ? $min_date : date('Y-m-d')),
             'placeholder' => 'dd.mm.yyyy'])
        <span style="margin: 0 10px">по</span>
        @include('widgets.form.formitem._DATE', 
            ['name' => 'max_date', 
             'value' => old('max_date') ? old('max_date') : ($max_date ? $max_date : date('Y-m-d')),
             'placeholder' => 'dd.mm.yyyy'])
        <div style='margin: 0 30px'>
        <input id="in_detail" name="in_detail" type="checkbox" value="1"{{ !empty($in_detail) && $in_detail==1 ? ' checked' : '' }}>
           <label for="in_detail" style='font-weight: normal'>{!! trans('messages.in_detail') !!}</label>
        </div>
        @include('widgets.form.formitem._submit', ['title' => trans('messages.view')])
    </div>
    {!! Form::close() !!}
    <p><a href="{{ route('stats.by_editor', $user).$quarter_query }}">В текущем квартале</a></p>
    <p><a href="{{ route('stats.by_editor', $user).$year_query }}">В текущем году</a></p>
    
    <h3>Создано</h3>
    @foreach ($models as $model => $title)
        @if(!empty($history_created[$model]))
    <p>
        {{ $title }}: <b>{{ format_number($history_created[$model]->count) }}</b>
        
        {{-- Кнопка сворачивания --}}
            @if(!empty($in_detail))
        <a href="#"
           class="toggle-btn ml-2 text-muted" 
           data-toggle="collapse" 
           style="text-decoration: none"
           data-target="#details-{{ Str::slug($title) }}">
            <small><span class="toggle-icon">▼</span> подробно</small>
        </a>
            @endif
    </p>

            @if(!empty($in_detail) && !empty($detailed_created[$title]))
                @include('stats._detailed_list', [
                    'items_by_date' => $detailed_created[$title],
                    'prefix' => ''
                ])
            @endif
        @endif
    @endforeach
    
    <h3>Изменено</h3>
    @foreach ($history_updated as $title => $count)
        @if (!empty($count))
    <p>
        {{ $title }}: <b>{{ format_number($count) }}</b>
        {{-- Кнопка сворачивания для изменённых --}}
            @if(!empty($in_detail))
        <a href="#"
           class="toggle-btn ml-2 text-muted"
           data-toggle="collapse"
           style="text-decoration: none"
           data-target="#details-upd-{{ Str::slug($title) }}">
            <small><span class="toggle-icon">▼</span> подробно</small>
        </a>
            @endif
    </p>
    {{-- Блок с детальной информацией для изменённых --}}
            @if(!empty($in_detail) && !empty($detailed_updated[$title]))
                @include('stats._detailed_list', [
                    'items_by_date' => $detailed_updated[$title],
                    'prefix' => 'upd-' 
                ])
            @endif
        @endif
    @endforeach
    
    <p><a href="{{ route('stats.by_editors') }}">К списку редакторов</a></p>
@stop

@section('jqueryFunc')
    // Настройка кнопок "подробно"
    $('[data-toggle="collapse"]').on('click', function(e) {
        e.preventDefault();

        var $this = $(this);
        var targetId = $this.data('target');
        var $target = $(targetId);

        // Переключаем видимость
        $target.toggle();

        // Сохраняем состояние в localStorage
        if ($target.is(':hidden')) {
            localStorage.setItem(targetId, 'collapsed'); // Пользователь свернул
        } else {
            localStorage.removeItem(targetId); // Пользователь развернул
        }

        // Меняем иконку
        var $icon = $this.find('.toggle-icon');
        if ($icon.length) {
            $icon.text($target.is(':hidden') ? '▶' : '▼');
        }
    });

    // Инициализация: по умолчанию ВСЕ детальные блоки СКРЫТЫ
    $('.detailed-list').hide(); // Прячем все блоки с классом detailed-list

    // Проходим по каждой кнопке и обновляем иконку в соответствии с localStorage
    $('[data-toggle="collapse"]').each(function() {
        var $this = $(this);
        var targetId = $this.data('target');
        var $target = $(targetId);
        var $icon = $this.find('.toggle-icon');

        // Если в localStorage указано, что блок должен быть скрыт — оставляем его скрытым
        if (localStorage.getItem(targetId) === 'collapsed') {
            $target.hide(); // Прячем, если нужно
            // Обновляем иконку при загрузке
            if ($icon.length) {
                 $icon.text('▶');// Меняем иконку на свернутую
            }
        } else {
            // Но! По умолчанию мы всё равно хотим, чтобы было ▶, пока пользователь не нажал
            // Однако, если кто-то *уже развернул* ранее — оставить как есть
            if ($target.is(':visible')) {
                if ($icon.length) {
                    $icon.text('▼');
                }
            } else {
                if ($icon.length) {
                    $icon.text('▶');
                }
            }
        }
    });
@stop
