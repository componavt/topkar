<x-app-layout>   
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>
    
    <x-slot name="header">
        {{trans('navigation.toponyms')}} / Создание нового
    </x-slot>
    
    {!! Form::open(array('method'=>'POST', 'route' => array('toponyms.store'))) !!}
    @include('dict.toponyms.form._create_edit', 
                ['submit_title' => trans('messages.create_new_m'),
                 'action' => 'create',
                 'district_value' => []])
    {!! Form::close() !!}
    
    <x-slot name="footScriptExtra">
          {!!Html::script('js/select2.min.js')!!}
          {!!Html::script('js/lists.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
           selectDistrict('region_id','{{trans('toponym.district')}}');
    </x-slot>
</x-app-layout>