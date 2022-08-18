<x-app-layout>   
    <x-slot name="header">
        {{trans('navigation.districts_1926')}} / {{__('messages.new_m')}} {{mb_strtolower(__('toponym.district1926'))}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('districts1926.index') }}{{$args_by_get}}">{{ __('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('districts1926.create') }}{{$args_by_get}}">{{ mb_strtolower(__('messages.create_new_m')) }}</a>
        @else
            | {{ __('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::open(['method'=>'POST', 'route' => ['districts1926.store'], 'id'=>'districtForm']) !!}
    @include('dict.districts1926._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
</x-app-layout>