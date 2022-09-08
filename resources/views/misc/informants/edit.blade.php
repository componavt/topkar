<x-app-layout>    
    <x-slot name="header">
        {{trans('navigation.informants')}} / {{trans('messages.editing')}} / {{$informant->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('informants.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('informants.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($informant, array('method'=>'PUT', 'route' => ['informants.update', $informant->id], 'id'=>'informantForm')) !!}
    @include('misc.informants._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
    
</x-app-layout>