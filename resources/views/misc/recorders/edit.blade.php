<x-app-layout>    
    <x-slot name="header">
        {{trans('navigation.recorders')}} / {{trans('messages.editing')}} / {{$recorder->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('recorders.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('recorders.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($recorder, array('method'=>'PUT', 'route' => ['recorders.update', $recorder->id], 'id'=>'recorderForm')) !!}
    @include('misc.recorders._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
    
</x-app-layout>