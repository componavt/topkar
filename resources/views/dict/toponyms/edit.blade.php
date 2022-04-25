<x-app-layout>   
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>
    
    <x-slot name="header">
        {{trans('navigation.toponyms')}} / {{trans('messages.editing')}} / {{$toponym->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('toponyms.show', $toponym) }}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a>
        | <a href="{{ route('toponyms.index') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.back_to_list')) }}</a>
        @if (user_dict_edit())
            | <a href="{{ route('toponyms.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    {!! Form::model($toponym, array('method'=>'PUT', 'route' => ['toponyms.update', $toponym->id])) !!}
    @include('dict.toponyms.form._create_edit', 
                ['submit_title' => trans('messages.save'),
                 'action' => 'edit'])
        {!! Form::close() !!}
    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        $('.select-geotype').select2({allowClear: true, placeholder: ''});
        $('.select-region').select2({allowClear: true, placeholder: ''});
        $('.select-region1926').select2({allowClear: true, placeholder: ''});
        selectDistrict('region_id', '', true);
        selectDistrict1926('region1926_id', '', true);
        selectSelsovet1926('region1926_id', 'district1926_id', '', true);
        selectSettlement1926('region1926_id', 'district1926_id', 'selsovet1926_id', '', true);
        $('.select-etymology-nation').select2({allowClear: true, placeholder: ''});
        $('.select-ethnos-territory').select2({allowClear: true, placeholder: ''});
    </x-slot>
</x-app-layout>