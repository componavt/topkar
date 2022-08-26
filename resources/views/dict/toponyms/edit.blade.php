<x-app-layout>   
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>
    
    <x-slot name="header">
        {{trans('navigation.toponyms')}} / {{trans('messages.editing')}} / {{$toponym->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('toponyms.show', $toponym) }}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a>
        | <a href="{{ route('toponyms.index') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.back_to_list')) }}</a>
        @if (user_can_edit())
            | <a href="{{ route('toponyms.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    @include('dict.toponyms.modals_for_edition', ['selsovet'=>null, 'settlement'=>null])
    
    {!! Form::model($toponym, [
                    'method'=>'PUT', 
                    'route' => ['toponyms.update', $toponym->id], 
                    'id'=>'toponymForm'
    ]) !!}
    @include('dict.toponyms.form._create_edit', 
                ['submit_title' => trans('messages.save'),
                 'action' => 'edit',
                 'topnames' => $toponym->topnames()->count() ? $toponym->topnames : []])
        {!! Form::close() !!}
    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/toponym.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        @include('dict.toponyms._jquery_func_for_create_edit')
    </x-slot>
</x-app-layout>