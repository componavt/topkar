<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.settlements_1926')}}
    </x-slot>

    <h3>{{ $settlement->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('settlements1926.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('settlements1926.edit', $toponym)])
            | @include('widgets.form.button._delete', ['route' => 'settlements1926.destroy', 'args'=>['settlements1926' => $settlement->id]])             
            | <a href="{{ route('settlements1926.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_g')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <p><span class='field-name'>{{trans('toponym.region')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926->district1926->region)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.district1926')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926->district1926)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.selsovet1926')}}</span>: 
    <span class='field-value'>{{ optional($settlement->selsovet1926)->name }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_ru }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_en }}</span></p>

    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_karelian')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->name_krl }}</span></p>

    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/rec-delete-link.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
    </x-slot>                                                        
</x-app-layout>
