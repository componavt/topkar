<x-app-layout>    
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>
    
    <x-slot name="header">
        {{trans('navigation.settlements_1926')}} / {{trans('messages.editing')}} / {{$settlement->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('settlements1926.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('settlements1926.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($settlement, array('method'=>'PUT', 'route' => ['settlements1926.update', $settlement->id], 'id'=>'settlement1926Form')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.settlements1926._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/toponym.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        selectDistrict1926('region1926_id', '{{app()->getLocale()}}', '', true, '.select-district1926');
        selectSelsovet1926('region1926_id', 'district1926_id', '{{app()->getLocale()}}', '', true, '.select-selsovet1926');
    </x-slot>
</x-app-layout>