<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>
@include('widgets.form.formitem._text', 
        ['name' => 'name_ru', 
         'title'=>trans('messages.name').' '.trans('messages.in_russian')])
@include('widgets.form.formitem._text', 
        ['name' => 'name_en', 
         'title'=>trans('messages.name').' '.trans('messages.in_english')])
