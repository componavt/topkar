<x-app-layout>   
    <x-slot name="header">
        {{trans('navigation.districts')}} / {{__('messages.new_m')}} {{mb_strtolower(__('toponym.district'))}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('districts.index') }}{{$args_by_get}}">{{ __('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('districts.create') }}{{$args_by_get}}">{{ mb_strtolower(__('messages.create_new_m')) }}</a>
        @else
            | {{ __('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::open(['method'=>'POST', 'route' => ['districts.store'], 'id'=>'districtForm']) !!}
    @include('dict.districts._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
</x-app-layout>