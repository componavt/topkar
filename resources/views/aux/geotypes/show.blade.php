<x-app-layout>
    <x-slot name="header">
            {{trans('navigation.geotypes')}}
    </x-slot>

    <h3>{{ $geotype->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('geotypes.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('geotypes.edit', $geotype)])
            | @include('widgets.form.button._delete', ['route' => 'geotypes.destroy', 'args'=>['geotype' => $geotype->id]])             
            | <a href="{{ route('geotypes.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <h3>{{mb_ucfirst(trans('messages.in_russian'))}}</h3>
    <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
    <span class='field-value'>{{ $geotype->name_ru }}</span></p>

    <p><span class='field-name'>{{trans('aux.short_name')}}</span>: 
    <span class='field-value'>{{ $geotype->short_ru }}</span></p>

    <p><span class='field-name'>{{trans('aux.desc')}}</span>: 
    <span class='field-value'>{{ $geotype->desc_ru }}</span></p>

    <h3>{{mb_ucfirst(trans('messages.in_english'))}}</h3>
    <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
    <span class='field-value'>{{ $geotype->name_en }}</span></p>

    <p><span class='field-name'>{{trans('aux.short_name')}}</span>: 
    <span class='field-value'>{{ $geotype->short_en }}</span></p>

    <p><span class='field-name'>{{trans('aux.desc')}}</span>: 
    <span class='field-value'>{{ $geotype->desc_en }}</span></p>
    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/rec-delete-link.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        recDelete('{{ trans('messages.confirm_delete') }}');
    </x-slot>                                                        
</x-app-layout>
