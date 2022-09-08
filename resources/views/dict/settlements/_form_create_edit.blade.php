<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form.formitem._select', 
        ['name' => 'region_id', 
         'values' =>$region_values,
         'title' => trans('toponym.region')]) 

@include('widgets.form.formitem._select2', 
        ['name' => 'district_id', 
         'values' => $district_values,
         'title' => trans('toponym.district'),
         'is_multiple' => false,
         'class'=>'select-district form-control'
]) 
             
@include('widgets.form.formitem._text', 
        ['name' => 'name_ru', 
         'title'=>trans('toponym.name').' '.trans('messages.in_russian')])

@include('widgets.form.formitem._text', 
        ['name' => 'name_en', 
         'title'=>trans('toponym.name').' '.trans('messages.in_english')])

@include('widgets.form.formitem._text', 
        ['name' => 'name_krl', 
         'title'=>trans('toponym.name').' '.trans('messages.in_karelian')])

