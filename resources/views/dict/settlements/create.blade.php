<x-app-layout>   
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>

    <x-slot name="header">
        {{trans('navigation.settlements')}} / {{__('messages.new_g')}} {{mb_strtolower(__('toponym.settlement1926'))}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('settlements.index') }}{{$args_by_get}}">{{ __('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('settlements.create') }}{{$args_by_get}}">{{ mb_strtolower(__('messages.create_new_m')) }}</a>
        @else
            | {{ __('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::open(['method'=>'POST', 'route' => ['settlements.store'], 'id'=>'settlementForm']) !!}
    @include('dict.settlements._form_create_edit', ['settlement'=>null, 'district_value'=>[], 'action'=>'creation'])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/toponym.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        selectDistrict('region_id', '{{app()->getLocale()}}', '{{trans('toponym.select_district')}}', true, '.select-district-0');
      $('.select-district-0').select2({
        allowClear: true,
        placeholder: '{{trans('toponym.select_district')}}',
        width: '100%'
    });      
    </x-slot>
</x-app-layout>