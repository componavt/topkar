<x-app-layout>    
    <x-slot name="headExtra">
        {!!Html::style('css/select2.min.css')!!}  
    </x-slot>
    
    <x-slot name="header">
        {{trans('navigation.settlements')}} / {{trans('messages.editing')}} / {{$settlement->name}}
    </x-slot>
    
    <div class='top-links'>        
        <a href="{{ route('settlements.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('settlements.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($settlement, array('method'=>'PUT', 'route' => ['settlements.update', $settlement->id], 'id'=>'settlement1926Form')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.settlements._form_create_edit', ['district_value'=>$settlement->districtValue()])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
    
    <x-slot name="footScriptExtra">
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/special_symbols.js')!!}
        {!!Html::script('js/toponym.js')!!}
    </x-slot>
    <x-slot name="jqueryFunc">
        @for ($i=0; $i<=sizeof($settlement->districtValue()); $i++)
            selectDistrict('region_id', '{{app()->getLocale()}}', '{{trans('toponym.select_district')}}', true, '.select-district-{{$i}}');
        @endfor
    </x-slot>
</x-app-layout>