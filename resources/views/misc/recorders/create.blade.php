<x-app-layout>   
    <x-slot name="header">
        {{trans('navigation.recorders')}} / {{__('messages.new_m')}} {{mb_strtolower(__('misc.recorder'))}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('recorders.index') }}{{$args_by_get}}">{{ __('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('recorders.create') }}{{$args_by_get}}">{{ mb_strtolower(__('messages.create_new_m')) }}</a>
        @else
            | {{ __('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::open(['method'=>'POST', 'route' => ['recorders.store'], 'id'=>'recorderForm']) !!}
    @include('misc.recorders._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
</x-app-layout>