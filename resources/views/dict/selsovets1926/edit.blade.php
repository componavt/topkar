<x-app-layout>    
    <x-slot name="header">
        {{trans('navigation.selsovets_1926')}} / {{trans('messages.editing')}} / {{$selsovet->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('selsovets1926.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('selsovets1926.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($selsovet, array('method'=>'PUT', 'route' => ['selsovets1926.update', $selsovet->id], 'id'=>'selsovet1926Form')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.selsovets1926._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
    
</x-app-layout>