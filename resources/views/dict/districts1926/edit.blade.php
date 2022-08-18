<x-app-layout>    
    <x-slot name="header">
        {{trans('navigation.districts_1926')}} / {{trans('messages.editing')}} / {{$district1926->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('districts1926.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('districts1926.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($district1926, array('method'=>'PUT', 'route' => ['districts1926.update', ['districts1926'=>$district1926]], 'id'=>'districtForm')) !!}
    @include('dict.districts1926._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
    
</x-app-layout>