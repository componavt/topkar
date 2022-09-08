<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.informants')}}
    </x-slot>

    <h3>{{ $informant->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('informants.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('informants.edit', $informant)])
            | @include('widgets.form.button._delete', ['route' => 'informants.destroy', 'args'=>['informant' => $informant->id]])             
            | <a href="{{ route('informants.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ $informant->name_ru }}</span></p>

    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ $informant->name_en }}</span></p>

    <p><span class='field-name'>{{trans('misc.birth_date')}}</span>: 
    <span class='field-value'>{{ $informant->birth_date }}</span></p>

    <x-slot name="footScriptExtra">
        {!!Html::script('js/rec-delete-link.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
    </x-slot>                                                        
</x-app-layout>
