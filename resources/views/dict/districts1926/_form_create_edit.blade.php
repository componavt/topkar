@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form.formitem._text', 
        ['name' => 'name_en', 
         'title'=>trans('toponym.name').' '.trans('messages.in_english')])

@include('widgets.form.formitem._text', 
        ['name' => 'name_ru', 
         'title'=>trans('toponym.name').' '.trans('messages.in_russian')])

@include('widgets.form.formitem._select', 
        ['name' => 'region_id', 
         'values' =>$region_values,
         'title' => trans('toponym.region')]) 

