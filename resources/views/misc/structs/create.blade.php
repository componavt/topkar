<x-app-layout>   
    <x-slot name="header">
        {{trans('navigation.structs')}} / {{__('messages.new_m')}} {{mb_strtolower(__('misc.struct'))}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('structs.index') }}{{$args_by_get}}">{{ __('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('structs.create') }}{{$args_by_get}}">{{ mb_strtolower(__('messages.create_new_m')) }}</a>
        @else
            | {{ __('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::open(['method'=>'POST', 'route' => ['structs.store'], 'id'=>'structForm']) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('misc.structs._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
</x-app-layout>