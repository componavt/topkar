<x-app-layout>    
    <x-slot name="header">
        {{trans('navigation.districts')}} / {{trans('messages.editing')}} / {{$district->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('districts.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('districts.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($district, array('method'=>'PUT', 'route' => ['districts.update', $district->id], 'id'=>'districtForm')) !!}
    @include('dict.districts._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
    
</x-app-layout>