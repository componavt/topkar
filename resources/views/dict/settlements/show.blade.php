<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.settlements')}}
    </x-slot>

    <h3>{{ $settlement->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('settlements.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('settlements.edit', $settlement)])
            | @include('widgets.form.button._delete', ['route' => 'settlements.destroy', 'args'=>['settlement' => $settlement->id]])             
            | <a href="{{ route('settlements.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_g')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <p><span class='field-name'>{{trans('toponym.region')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->regionsToString() }}</span></p>

    <p><span class='field-name'>{{trans('navigation.districts')}}</span>: 
    <span class='field-value'>{{ optional($settlement)->districtsToString() }}</span></p>

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