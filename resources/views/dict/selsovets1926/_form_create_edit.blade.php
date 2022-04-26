<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form.formitem._select', 
        ['name' => 'region1926_id', 
         'values' =>$region_values,
         'title' => trans('toponym.region1926')]) 

@include('widgets.form.formitem._select2', 
        ['name' => 'district1926_id', 
         'values' => $district1926_values,
         'title' => trans('toponym.district1926'),
         'is_multiple' => false,
         'class'=>'select-selsovet-district1926 form-control'
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

